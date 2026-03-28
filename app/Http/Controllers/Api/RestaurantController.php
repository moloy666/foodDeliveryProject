<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

use App\Models\Restaurant;
use App\Models\RestaurantDocument;
use App\Models\RestaurantImages;
use App\Models\RestaurantMenus;

use App\Services\CloudinaryService;

use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    use ApiResponse;
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function index(Request $request)
    {
        // Get approved restaurants with related user and images
        $data = Restaurant::with(['user', 'images', 'addresses'])
            ->where('status', 'approved')
            ->where('is_open', 1)
            ->get();

        $filteredData = $data->map(function ($restaurant) {
            return [
                'uid'         => $restaurant->uid,
                'name'        => $restaurant->name,
                'phone'       => $restaurant->phone,
                'email'       => $restaurant->email,
                'description' => $restaurant->description,
                'status'      => $restaurant->status,
                'user_name'   => $restaurant->user ? $restaurant->user->name : null,
                'images' => $restaurant->images->mapWithKeys(function ($image) {
                    return [
                        $image->type => $image->file_path,
                    ];
                }),
                'addresses'   => $restaurant->addresses->mapWithKeys(function ($addresses) {
                    return [
                        'label'           => ucwords($addresses->label),
                        'address_line_1'  => ucwords($addresses->address_line_1),
                        'address_line_2'  => ucwords($addresses->address_line_2),
                        'city'            => ucwords($addresses->city),
                        'state'           => ucwords($addresses->state),
                        'postal_code'     => $addresses->postal_code,
                    ];
                }),
            ];
        });

        return $this->successResponse(
            200,
            'Approved Restaurants',
            ['restaurants' => $filteredData]
        );
    }


    public function addRestaurant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'nullable|string|max:255',
            'phone'          => 'nullable|numeric',
            'email'          => 'nullable|string|max:255|email',
            'description'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                422,
                'Validation failed',
                [
                    'errors'  => $validator->errors()
                ]
            );
        }

        $user = $request->user();

        try {
            $hasPending = Restaurant::where('user_uid', $user->uid)
                ->where('status', 'pending')
                ->exists();

            if ($hasPending) {
                return $this->errorResponse(
                    403,
                    'You already have a restaurant with pending status. Please wait for approval before creating a new one.'
                );
            }

            $data = $validator->validated();
            $data['user_uid'] = $user->uid;

            $restaurants = Restaurant::create($data);

            return $this->successResponse(
                201,
                'Restaurant created successfully',
                [
                    'addresses' => $restaurants
                ]
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(404, 'Restaurant not found');
        } catch (\Throwable $e) {
            return $this->errorResponse(
                500,
                'Something went wrong while create restaurant',
                $e->getMessage()
            );
        }
    }

    public function getRestaurants(Request $request)
    {
        $user = $request->user();

        // Eager-load restaurant images to avoid N+1 queries
        $restaurants = $user->restaurants()
            ->with('images')
            ->with('addresses')
            ->get();

        // Format response data
        $filteredData = $restaurants->map(function ($restaurant) {
            return [
                'uid'         => $restaurant->uid,
                'name'        => $restaurant->name,
                'phone'       => $restaurant->phone,
                'email'       => $restaurant->email,
                'description' => $restaurant->description,
                'status'      => $restaurant->status,
                'images' => $restaurant->images->mapWithKeys(function ($image) {
                    return [
                        $image->type => $image->file_path,
                    ];
                }),
                'addresses'   => $restaurant->addresses->mapWithKeys(function ($addresses) {
                    return [
                        'label'           => ucwords($addresses->label),
                        'address_line_1'  => ucwords($addresses->address_line_1),
                        'address_line_2'  => ucwords($addresses->address_line_2),
                        'city'            => ucwords($addresses->city),
                        'state'           => ucwords($addresses->state),
                        'postal_code'     => $addresses->postal_code,
                    ];
                }),
            ];
        });

        return $this->successResponse(
            200,
            'User Restaurants',
            ['restaurants' => $filteredData]
        );
    }


    public function addRestaurantDocuments(Request $request, string $uid)
    {
        // Validate files and types
        $validator = Validator::make($request->all(), [
            'fssai'        => 'required|file|mimes:pdf,jpg,jpeg,png',
            'pan'          => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);

        if ($validator->fails()) {

            return $this->errorResponse(
                422,
                'Validation failed',
                $validator->errors()
            );
        }

        // Find target restaurant by UID
        $restaurant = Restaurant::where('uid', $uid)->first();
        if (!$restaurant) {

            return $this->errorResponse(
                404,
                'Restaurant not found',
                $validator->errors()
            );
        }

        // Types to process
        $types = ['fssai', 'pan'];
        $documents = [];

        foreach ($types as $type) {
            if ($request->hasFile($type)) {
                $file     = $request->file($type);
                $filePath = $file->store('restaurants/documents', 'public');

                $document = RestaurantDocument::create([
                    'restaurant_uid' => $restaurant->uid,
                    'type'           => $type,
                    'file_path'      => $filePath,
                ]);
                $documents[] = $document;
            }
        }


        return $this->successResponse(
            200,
            'Documents uploaded',
            [
                'documents' => $documents
            ]
        );
    }


    public function addRestaurantImages(Request $request, string $uid)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'for'  => 'required|string|in:banner,front_image,logo,inside_image',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(422, 'Validation failed', $validator->errors());
        }

        try {
            $restaurant = Restaurant::where('uid', $uid)->firstOrFail();

            $imageUrl = $this->cloudinary->upload(
                $request->file('file'),
                'restaurants-products'
            );

            if (!$imageUrl) {
                throw new \Exception('Cloudinary upload failed');
            }

            $image = RestaurantImages::updateOrCreate(
                [
                    'restaurant_uid' => $restaurant->uid,
                    'type'           => $request->for,
                ],
                [
                    'file_path' => $imageUrl['url'], // Cloudinary URL
                ]
            );

            return $this->successResponse(
                200,
                'Image uploaded successfully',
                ['image' => $image]
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(404, 'Restaurant not found');
        } catch (\Throwable $e) {
            return $this->errorResponse(
                500,
                'Something went wrong while uploading image',
                $e->getMessage()
            );
        }
    }

    public function createMenu(Request $request, string $restaurantId)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                422,
                'Validation failed',
                $validator->errors()
            );
        }

        // Find restaurant
        $restaurant = Restaurant::where('uid', $restaurantId)->first();
        if (!$restaurant) {
            return $this->errorResponse(
                404,
                'Restaurant not found',
                []
            );
        }

        $name  = $request->input('name');

        try {
            $menu = RestaurantMenus::create([
                'restaurant_uid' => $restaurant->uid,
                'name'           => strtolower(trim($name)),
            ]);

            return $this->successResponse(
                200,
                "Menu created successfully",
                ['menu' => $menu]
            );
        } catch (\Throwable $th) {

            return $this->errorResponse(
                500,
                "Something went wrong",
                $th->getMessage()
            );
        }
    }

    public function listMenu(Request $request, string $restaurantId)
    {
        $menus = RestaurantMenus::where('restaurant_uid', $restaurantId)
            ->select('uid', 'name', 'status')
            ->get();

        return $this->successResponse(
            200,
            "Menu list",
            ['menus' => $menus]
        );
    }

    public function updateMenu(Request $request, string $restaurantId, string $menuId)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                422,
                'Validation failed',
                $validator->errors()
            );
        }

        $menu = RestaurantMenus::where('restaurant_uid', $restaurantId)
            ->where('uid', $menuId)
            ->first();

        if (!$menu) {
            return $this->errorResponse(404, "Menu not found");
        }

        $menu->name = $request->input('name');
        $menu->status = $request->input('status', $menu->status);

        $menu->save();

        return $this->successResponse(
            200,
            "Menu updated successfully",
            ['menu' => $menu]
        );
    }

    public function softDeleteMenu(string $restaurantId, string $menuId)
    {
        $menu = RestaurantMenus::where('restaurant_uid', $restaurantId)
            ->where('uid', $menuId)
            ->first();

        if (!$menu) {
            return $this->errorResponse(404, "Menu not found");
        }

        $menu->delete(); // soft delete

        return $this->successResponse(200, "Menu soft deleted successfully");
    }

    public function restoreMenu(string $restaurantId, string $menuId)
    {
        $menu = RestaurantMenus::withTrashed()
            ->where('restaurant_uid', $restaurantId)
            ->where('uid', $menuId)
            ->first();

        if (!$menu) {
            return $this->errorResponse(404, "Menu not found");
        }

        $menu->restore();

        return $this->successResponse(200, "Menu restored successfully");
    }

    public function hardDeleteMenu(string $restaurantId, string $menuId)
    {
        $menu = RestaurantMenus::withTrashed()
            ->where('restaurant_uid', $restaurantId)
            ->where('uid', $menuId)
            ->first();

        if (!$menu) {
            return $this->errorResponse(404, "Menu not found");
        }

        $menu->forceDelete(); // hard delete

        return $this->successResponse(200, "Menu permanently deleted");
    }

    public function restaurantBasicDetails(Request $request, $restaurantId)
    {
        $restaurants = Restaurant::where('uid', $restaurantId)->first();

        return $this->successResponse(
            200,
            'Restaurant basic details',
            ['basicDetails' => $restaurants]
        );
    }

    public function restaurantImages(Request $request, $restaurantId)
    {
        $data = RestaurantImages::where('restaurant_uid', $restaurantId)->get();

        $filteredData = $data->map(function ($data) {
            return [
                'uid'   => $data->uid,
                'type'  => $data->type,
                'image' => $data->file_path,
            ];
        });

        return $this->successResponse(
            200,
            'Restaurant images',
            ['images' => $filteredData]
        );
    }


    public function toggleStatus(Request $request, $restaurantId)
    {
        $restaurant = Restaurant::where('uid', $restaurantId)->first();

        if (!$restaurant) {
            return $this->errorResponse(
                404,
                'Restaurant not found',
                ['restaurant_not_found']
            );
        }

        $restaurant->is_open = !$restaurant->is_open;
        $restaurant->save();
        return $this->successResponse(
            200,
            'Restaurant status changed successfully',
            [
                'is_open' => $restaurant->is_open
            ]
        );
    }
}
