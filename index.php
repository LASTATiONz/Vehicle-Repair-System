<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ระบบสมัครงาน</title>
        <link rel="icon" href="images/LOGO_ORNATE1.png" type="image/png">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdn.jsdelivr.net/npm/daisyui@3.0.1/dist/full.css" rel="stylesheet">
        <style>
            /* SweetAlert Button Styling */
            .swal2-confirm {
                background-color: #007BFF !important;
                color: white !important;
                border-radius: 8px !important;
                padding: 10px 20px !important;
                font-size: 16px !important;
            }

            /* Login Box Styling */
            .login-box {
                animation: fadeInUp 0.8s ease-in-out;
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.9);
                box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
                padding: 40px;
                width: 100%;
                max-width: 400px;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive Styling */
            @media (max-width: 425px) {
                .login-box {
                    padding: 20px;
                    max-width: 90%;
                }
                .login-box h2 {
                    font-size: 1.6rem;
                }
                .login-box img {
                    width: 80px;
                }
            }

            @media (max-width: 375px) {
                .login-box {
                    padding: 15px;
                    max-width: 92%;
                }
                .login-box h2 {
                    font-size: 1.4rem;
                }
                .login-box img {
                    width: 70px;
                }
                .login-box button {
                    font-size: 14px;
                    padding: 10px;
                }
            }
        </style>
    </head>
    <body class="bg-gradient-to-br from-blue-500 to-blue-300 min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md p-10 rounded-xl border border-gray-300 login-box">
            <div class="flex flex-col items-center mb-5">
                <img src="images/LOGO_ORNATE1.png" alt="Website Logo" class="w-20 h-20 md:w-16 md:h-16 sm:w-14 sm:h-14">
                <h2 class="text-3xl font-extrabold text-center text-blue-900">ระบบสมัครงาน</h2>
            </div>
            
            <form id="loginForm" class="space-y-4" action="login.php" method="POST" onsubmit="return validateForm()">
                <label class="block text-gray-700 font-semibold">ชื่อผู้ใช้</label>
                <input type="text" id="employee_id" name="employee_id" class="w-full px-4 py-3 bg-gray-100 text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <label class="block text-gray-700 font-semibold">รหัสผ่าน</label>
                <div class="relative">
                    <input type="password" id="employee_pass" name="employee_pass" class="w-full px-4 py-3 bg-gray-100 text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-3 flex items-center text-gray-600 hover:text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2C5.58 2 1.73 5.11.29 9.5c1.44 4.39 5.29 7.5 9.71 7.5s8.27-3.11 9.71-7.5C18.27 5.11 14.42 2 10 2zm0 13c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                        </svg>
                    </button>
                </div>
                
                <div class="flex items-center mt-2">
                    <input type="checkbox" id="rememberMe" name="rememberMe" class="form-checkbox text-blue-600">
                    <span class="ml-2 text-gray-700">จดจำฉัน</span>
                </div>
                
                <button type="submit" id="loginBtn" class="w-full mt-5 bg-blue-700 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition duration-300 flex items-center justify-center">
                    <span id="loginText">เข้าสู่ระบบ</span>
                    <svg id="loadingIcon" class="w-5 h-5 ml-2 hidden animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </button>
            </form>
            
            <footer class="text-center text-gray-500 text-sm mt-4">
                © 2025 OrnateGroup System. All rights reserved.
            </footer>
        </div>

        <script>
            function togglePassword() {
                let passwordField = document.getElementById("employee_pass");
                passwordField.type = passwordField.type === "password" ? "text" : "password";
            }

            function validateForm() {
                let employee_id = document.getElementById("employee_id").value;
                let employee_pass = document.getElementById("employee_pass").value;
                
                if (employee_id.trim() === "" || employee_pass.trim() === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                        text: 'ช่องชื่อผู้ใช้และรหัสผ่านต้องไม่ว่าง',
                        timer: 1500,
                        timerProgressBar: true
                    });
                    return false;
                }
                document.getElementById("loginText").textContent = "กำลังเข้าสู่ระบบ...";
                document.getElementById("loadingIcon").classList.remove("hidden");
                return true;
            }
        </script>
    </body>
</html>
