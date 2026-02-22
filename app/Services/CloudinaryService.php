<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloudinaryService
{
    public function upload(
        string $path,
        string $folder = 'uploads',
        array $options = []
    ): array {
        $result = Cloudinary::upload($path, array_merge([
            'folder' => $folder,
        ], $options));

        return [
            'public_id' => $result->getPublicId(),
            'url'       => $result->getSecurePath(),
            'format'    => $result->getExtension(),
            'size'      => $result->getSize(),
        ];
    }

    public function uploadFromRequest($file, string $folder = 'uploads'): array
    {
        return $this->upload(
            $file->getRealPath(),
            $folder
        );
    }

    public function delete(string $publicId): void
    {
        Cloudinary::destroy($publicId);
    }
}
