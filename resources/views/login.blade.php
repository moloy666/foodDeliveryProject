<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @include('layouts.headerLink')
</head>

<body
    class="font-sans bg-gradient-to-br from-blue-50 via-indigo-50 to-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white w-full sm:w-96 max-w-md p-6 sm:p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">
            Login
        </h2>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm text-gray-600 mb-1">
                Email
            </label>
            <input type="email" id="email" name="email" placeholder="Enter Email" autocomplete="off" required
                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:border-gray-500 focus:outline-none" />
        </div>

        <!-- Password -->
        <div class="relative mb-4">
            <label for="password" class="block text-sm text-gray-600 mb-1">
                Password
            </label>

            <input type="password" id="password" name="password" placeholder="Enter Password" autocomplete="off"
                required
                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-md focus:border-gray-500 focus:outline-none" />

            <!-- Toggle -->
            <button type="button" id="togglePwd"
                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-blue-600 mt-5">
                <!-- Eye -->

                <svg id="eyeOpen" class="h-5 w-5" fill="#000000" version="1.1" id="Capa_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 456.793 456.793" xml:space="preserve">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <g>
                            <g>
                                <path
                                    d="M448.947,218.474c-0.922-1.168-23.055-28.933-61-56.81c-50.707-37.253-105.879-56.944-159.551-56.944 c-53.673,0-108.845,19.691-159.551,56.944c-37.944,27.876-60.077,55.642-61,56.81L0,228.396l7.845,9.923 c0.923,1.168,23.056,28.934,61,56.811c50.707,37.254,105.878,56.943,159.551,56.943c53.672,0,108.844-19.689,159.551-56.943 c37.945-27.877,60.078-55.643,61-56.811l7.846-9.923L448.947,218.474z M228.396,312.096c-46.152,0-83.699-37.548-83.699-83.699 c0-46.152,37.547-83.699,83.699-83.699s83.7,37.547,83.7,83.699C312.096,274.548,274.548,312.096,228.396,312.096z M41.685,228.396c9.197-9.872,25.32-25.764,46.833-41.478c13.911-10.16,31.442-21.181,51.772-30.305 c-15.989,19.589-25.593,44.584-25.593,71.782s9.604,52.193,25.593,71.782c-20.329-9.124-37.861-20.146-51.771-30.306 C67.002,254.159,50.878,238.265,41.685,228.396z M368.273,269.874c-13.912,10.16-31.443,21.182-51.771,30.306 c15.988-19.589,25.594-44.584,25.594-71.782s-9.605-52.193-25.594-71.782c20.33,9.124,37.861,20.146,51.771,30.305 c21.516,15.715,37.639,31.609,46.832,41.477C405.91,238.268,389.785,254.161,368.273,269.874z">
                                </path>
                                <path
                                    d="M223.646,168.834c-27.513,4-50.791,31.432-41.752,59.562c8.23-20.318,25.457-33.991,45.795-32.917 c16.336,0.863,33.983,18.237,33.59,32.228c1.488,22.407-12.725,39.047-32.884,47.191c46.671,15.21,73.197-44.368,51.818-79.352 C268.232,175.942,245.969,166.23,223.646,168.834z">
                                </path>
                            </g>
                        </g>
                    </g>
                </svg>

                <!-- Eye Off -->

                <svg id="eyeClosed" class="h-5 w-5 hidden" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M14.7649 6.07595C14.9991 6.22231 15.0703 6.53078 14.9239 6.76495C14.4849 7.46742 13.9632 8.10644 13.3702 8.66304L14.5712 9.86405C14.7664 10.0593 14.7664 10.3759 14.5712 10.5712C14.3759 10.7664 14.0593 10.7664 13.8641 10.5712L12.6011 9.30816C11.8049 9.90282 10.9089 10.3621 9.93374 10.651L10.383 12.3276C10.4544 12.5944 10.2961 12.8685 10.0294 12.94C9.76266 13.0115 9.4885 12.8532 9.41703 12.5864L8.95916 10.8775C8.48742 10.958 8.00035 10.9999 7.5 10.9999C6.99964 10.9999 6.51257 10.958 6.04082 10.8775L5.58299 12.5864C5.51153 12.8532 5.23737 13.0115 4.97063 12.94C4.7039 12.8685 4.5456 12.5944 4.61706 12.3277L5.06624 10.651C4.09111 10.3621 3.19503 9.90281 2.3989 9.30814L1.1359 10.5711C0.940638 10.7664 0.624058 10.7664 0.428797 10.5711C0.233537 10.3759 0.233537 10.0593 0.428797 9.86404L1.62982 8.66302C1.03682 8.10643 0.515113 7.46742 0.0760677 6.76495C-0.0702867 6.53078 0.000898544 6.22231 0.235064 6.07595C0.46923 5.9296 0.777703 6.00078 0.924057 6.23495C1.40354 7.00212 1.989 7.68056 2.66233 8.2427C2.67315 8.25096 2.6837 8.25971 2.69397 8.26897C4.00897 9.35527 5.65536 9.9999 7.5 9.9999C10.3078 9.9999 12.6563 8.50629 14.0759 6.23495C14.2223 6.00078 14.5308 5.9296 14.7649 6.07595Z"
                            fill="#000000"></path>
                    </g>
                </svg>
            </button>
        </div>

        <!-- Links -->
        <div class="flex justify-between text-sm mb-6">
            <a href="{{ route('homePage') }}" class="text-blue-600 hover:underline">
                Forgot password?
            </a>
        </div>

        <!-- Login Button -->
        <button type="button" id="btnLogin"
            class="w-full py-3 bg-gray-800 hover:bg-gray-900 transition text-white font-semibold rounded-md shadow-sm">
            Login
        </button>

        <div class="mt-4 text-center">
            <a href="{{ route('registerPage') }}" class="ml-1 text-sm text-blue-600 font-medium hover:underline">
                Create Account
            </a>
        </div>
    </div>
    <script type="module">
        import {
            httpRequest,
            showToast
        } from '/js/httpClient.js';

        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");
        const loginBtn = document.getElementById("btnLogin");

        const togglePwd = document.getElementById("togglePwd");
        const eyeOpen = document.getElementById("eyeOpen");
        const eyeClosed = document.getElementById("eyeClosed");

        togglePwd.addEventListener("click", () => {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            eyeOpen.classList.toggle("hidden", isPassword);
            eyeClosed.classList.toggle("hidden", !isPassword);
        });

        loginBtn.addEventListener("click", login);

        async function login() {
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();

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
                loginBtn.textContent = "Loading...";
                loginBtn.disabled = true;

                const res = await httpRequest('/api/auth/login', {
                    method: "POST",
                    body: {
                        email,
                        password
                    }
                });

                showToast('success', res.message);

                setTimeout(() => {
                    const params = new URLSearchParams(window.location.search);
                    const source = params.get('source');

                    if (source === 'cart') {
                        location.href = "{{ url('/cart') }}";
                    } else {
                        location.href = "{{ route('homePage') }}";
                    }
                }, 350);

            } catch (err) {
                console.error(err);
            } finally {
                loginBtn.textContent = "Login";
                loginBtn.disabled = false;
            }
        }
    </script>

</body>

</html>
