@extends('layouts.profile')

@section('title', 'My Profile')

@section('content')

    <h1 class="text-2xl font-semibold mb-6">My Profile</h1>

    <form id="form-profile" class="space-y-8 max-w-3xl mx-auto">

        <!-- Profile Section -->
        <div class="bg-gray-50 p-6 rounded-xl flex flex-col sm:flex-row items-center sm:items-start gap-4">
            <div class="relative group cursor-pointer w-28 h-28 sm:w-32 sm:h-32">
                <label for="profileImageInput" class="block w-full h-full cursor-pointer">

                    <img id="profilePreview"
                        src="https://res.cloudinary.com/dnzosmlal/image/upload/v1771069494/restaurants-foods/ldofofogzjadp9cjfyvk.png"
                        class="w-full h-full rounded-full object-cover border shadow">
                    <div
                        class="absolute inset-0 bg-black/40 rounded-full 
                            opacity-0 group-hover:opacity-100 
                            transition flex items-center justify-center 
                            text-white text-sm">
                        Change
                    </div>
                </label>
            </div>

            <input type="file" id="profileImageInput" name="profile_image" class="hidden" accept="image/*">

            <div class="flex-1 w-full space-y-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ $authUser['name'] ?? '' }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-gray-800 outline-none">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" value="{{ $authUser['email'] ?? '' }}" readonly
                        class="w-full border border-gray-300 bg-gray-100 rounded-lg px-4 py-2 cursor-not-allowed">
                </div>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            <div>
                <label class="block font-medium text-gray-700 mb-1">Gender</label>
                <select name="gender" id="gender" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="{{ $authUser['dob'] ?? '' }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
        </div>

        <div class="text-right">
            <button type="submit" id="btnSave"
                class="bg-gray-800 hover:bg-black text-white px-8 py-2 rounded-lg font-semibold transition w-full sm:w-auto">
                Save
            </button>
        </div>

    </form>

    <script type="module">
        import {
            httpRequest,
            showToast
        } from '/js/httpClient.js';

        const form = document.getElementById("form-profile");
        const imageInput = document.getElementById("profileImageInput");
        const preview = document.getElementById("profilePreview");

        const gender = document.getElementById("gender");
        const dob = document.getElementById("dob");

        const btnSave = document.getElementById("btnSave");

        const default_image =
            'https://res.cloudinary.com/dnzosmlal/image/upload/v1771069494/restaurants-foods/ldofofogzjadp9cjfyvk.png';

        async function getUserProfile() {
            try {
                const res = await httpRequest("api/auth/profile");
                const user = res?.data?.user;

                if (user) {
                    dob.value = user?.dob || "";
                    gender.value = user?.gender || "";
                    preview.src = user?.profile_image || default_image;
                }

            } catch (err) {
                console.log("error", "Profile details not found");
            }
        }

        getUserProfile();

        preview.addEventListener("click", () => {
            imageInput.click();
        });

        imageInput.addEventListener("change", async () => {

            const file = imageInput.files[0];
            if (!file) return;

            preview.src = URL.createObjectURL(file);

            const formData = new FormData();
            formData.append("profile_image", file);

            try {
                await httpRequest("api/users/profile-image", {
                    method: "POST",
                    body: formData
                });

                showToast("success", "Profile image updated");
            } catch (err) {
                showToast("error", "Image upload failed");
            }
        });


        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const data = {
                name: document.getElementById("name").value,
                gender: document.getElementById("gender").value,
                dob: document.getElementById("dob").value,
            };

            btnSave.disabled = false;
            btnSave.textContent = "Saving...";


            try {
                const res = await httpRequest("api/users", {
                    method: "PUT",
                    body: data
                });

                const message = res?.message || "Profile updated successfully";
                showToast("success", message);

            } catch (err) {
                showToast("error", "Profile update failed");
            } finally {

                btnSave.textContent = "Save";
                btnSave.disabled = false;
            }
        });
    </script>

@endsection
