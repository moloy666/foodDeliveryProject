<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @include('layouts.headerLink')
</head>

<body
    class="font-sans bg-gradient-to-br from-blue-50 via-indigo-50 to-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-4 rounded-xl shadow-lg w-80 max-w-full">
        <h2 class="text-2xl font-bold text-gray-700 mb-5 text-center">
            Create Account
        </h2>

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="block text-sm text-gray-600 mb-1">
                Name
            </label>
            <input type="text" id="name" name="name" placeholder="Enter Name" autocomplete="off" required
                class="w-full px-3 py-3 border border-gray-300 rounded-md focus:border-gray-500 focus:outline-none" />
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="block text-sm text-gray-600 mb-1">
                Email
            </label>
            <input type="email" id="email" name="email" placeholder="Enter Email" autocomplete="off" required
                class="w-full px-3 py-3 border border-gray-300 rounded-md focus:border-gray-500 focus:outline-none" />
        </div>

        <!-- Password -->
        <div class="relative mb-3">
            <label for="password" class="block text-sm text-gray-600 mb-1">
                Password
            </label>

            <input type="password" id="password" name="password" placeholder="Enter Password" autocomplete="off"
                required
                class="w-full px-3 py-3 pr-10 border border-gray-300 rounded-md focus:border-gray-500 focus:outline-none" />

            <button type="button" id="togglePwd" class="absolute right-3 top-[38px] text-gray-400 hover:text-blue-600">
                👁
            </button>
        </div>

        <!-- Confirm Password -->
        <div class="relative mb-3">
            <label for="confirm_password" class="block text-sm text-gray-600 mb-1">
                Confirm Password
            </label>

            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password"
                autocomplete="off" required
                class="w-full px-3 py-3 pr-10 border border-gray-300 rounded-md focus:border-gray-500 focus:outline-none" />

            <button type="button" id="toggleConfirmPwd"
                class="absolute right-3 top-[38px] text-gray-400 hover:text-blue-600">
                👁
            </button>
        </div>

        <!-- Register Button -->
        <button type="button" id="btnRegister"
            class="w-full py-3 bg-gray-800 hover:bg-gray-900 transition text-white font-semibold rounded-md shadow-sm">
            Register
        </button>

        <!-- Login Link -->
        <div class="mt-3 text-center">
            <span class="text-sm text-gray-600">
                Already have an account?
            </span>
            <a href="{{ route('loginPage') }}" class="ml-1 text-sm text-blue-600 font-medium hover:underline">
                Login
            </a>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("confirm_password");

        document.getElementById("togglePwd").addEventListener("click", () => {
            passwordInput.type =
                passwordInput.type === "password" ? "text" : "password";
        });

        document.getElementById("toggleConfirmPwd").addEventListener("click", () => {
            confirmPasswordInput.type =
                confirmPasswordInput.type === "password" ? "text" : "password";
        });
    </script>

    <script type="module">
        import {
            httpRequest,
            showToast
        } from '/js/httpClient.js';

        const nameInput = document.getElementById("name");
        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");

        const btnRegister = document.getElementById("btnRegister");

        btnRegister.addEventListener("click", register);

        async function register() {
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();
            const name = nameInput.value.trim();

            if (!name) {
                showToast('warning', 'Enter name!');
                nameInput.focus();
                return;
            }

            if (!email) {
                showToast('warning', 'Enter email!');
                emailInput.focus();
                return;
            }

            if (!password) {
                showToast('warning', 'Enter password!');
                passwordInput.focus();
                return;
            }

            try {
                btnRegister.textContent = "Processing...";
                btnRegister.disabled = true;

                const res = await httpRequest('/api/users', {
                    method: "POST",
                    body: {
                        email,
                        password,
                        name
                    }
                });

                location.href = `{{ route('verifyRegisterCodePage') }}?email=${email}`;

            } catch (err) {
                console.error(err);
            } finally {
                btnRegister.textContent = "Register";
                btnRegister.disabled = false;
            }
        }
    </script>
</body>

</html>
