<?php
session_start(); // Ensure session is started to access session errors
$login_errors = [];
if (isset($_SESSION['login_errors'])) {
    $login_errors = $_SESSION['login_errors'];
    unset($_SESSION['login_errors']); // Clear errors after retrieving them
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Ornate Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <!-- Favicon -->
    <link rel="icon" href="images/LOGO_ORNATE1.png" type="image/png">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }
        .form-input:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            outline: 0;
        }
        .password-toggle-icon {
            cursor: pointer;
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280; /* gray-500 */
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 to-sky-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-6 sm:p-8 md:p-10 rounded-xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 flex items-center justify-center bg-white-600 rounded-full text-white text-3xl font-bold mb-4">
                <img src="images/LOGO_ORNATE1.png" alt="Ornate Group Logo" class="object-contain mx-auto" />
            </div>
            <h1 class="text-3xl font-bold text-sky-700">เข้าสู่ระบบ</h1>
            <p class="text-gray-600 mt-2">กรุณากรอกอีเมลและรหัสผ่านเพื่อเข้าใช้งาน</p>
        </div>

        <form id="loginForm" action="guest_process_login.php" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" placeholder="example@email.com" required
                       class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div class="relative">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" placeholder="กรอกรหัสผ่านของคุณ" required
                       class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
                <i class="fa-solid fa-eye password-toggle-icon" id="togglePassword"></i>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    </div>
                <div class="text-sm">
                    <a href="guest_forgot_password.php" class="font-medium text-sky-600 hover:text-sky-500 underline">
                        ลืมรหัสผ่าน?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition duration-150 ease-in-out">
                    เข้าสู่ระบบ
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            ยังไม่มีบัญชี?
            <a href="guest_register.php" class="font-medium text-sky-600 hover:text-sky-500 underline">
                สมัครสมาชิกที่นี่
            </a>
        </p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Script for password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        // PHP-generated JavaScript to display login errors
        <?php if (!empty($login_errors)): ?>
            Swal.fire({
                title: 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ',
                html: '<?php echo implode("<br>", array_map('htmlspecialchars', $login_errors)); ?>',
                icon: 'error',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#d33'
            });
        <?php endif; ?>
    </script>
</body>
</html>
