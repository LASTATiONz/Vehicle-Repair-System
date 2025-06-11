<?php
// Start the session to potentially store/display messages later if needed
// session_start(); // Not strictly necessary for this page unless handling complex error feedback

// Get the token from the URL
$token = isset($_GET['token']) ? htmlspecialchars($_GET['token']) : '';

// Basic check if token is present. More robust validation will be done in the processing script.
if (empty($token)) {
    // You could display an error message here or redirect.
    // For now, we'll let the form display, and the processing script will handle invalid/missing tokens.
    // echo "Error: No reset token provided.";
    // exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตั้งรหัสผ่านใหม่ - Ornate Group</title>
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
            <h1 class="text-3xl font-bold text-sky-700">ตั้งรหัสผ่านใหม่</h1>
            <p class="text-gray-600 mt-2">กรุณากรอกรหัสผ่านใหม่ของคุณด้านล่าง</p>
        </div>

        <form id="resetPasswordForm" action="guest_process_reset_password.php" method="POST" class="space-y-6">
            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <div class="relative">
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านใหม่ <span class="text-red-500">*</span></label>
                <input type="password" id="new_password" name="new_password" placeholder="กรอกรหัสผ่านใหม่อย่างน้อย 8 ตัว" required minlength="8"
                       class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
                <i class="fa-solid fa-eye password-toggle-icon" id="toggleNewPassword"></i>
                <p class="text-xs text-gray-500 mt-1">อย่างน้อย 8 ตัวอักษร</p>
            </div>

            <div class="relative">
                <label for="confirm_new_password" class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่านใหม่ <span class="text-red-500">*</span></label>
                <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="ยืนยันรหัสผ่านใหม่อีกครั้ง" required
                       class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
                <i class="fa-solid fa-eye password-toggle-icon" id="toggleConfirmNewPassword"></i>
            </div>
            
            <?php if (empty($token)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                    <p class="font-bold">ข้อผิดพลาด</p>
                    <p>ไม่พบโทเค็นสำหรับรีเซ็ตรหัสผ่าน ลิงก์อาจไม่ถูกต้องหรือหมดอายุ กรุณาลอง <a href="guest_forgot_password.php" class="underline font-medium">ขอลิงก์ใหม่</a></p>
                </div>
            <?php endif; ?>


            <div>
                <button type="submit" <?php if (empty($token)) echo 'disabled'; ?>
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition duration-150 ease-in-out <?php if (empty($token)) echo 'opacity-50 cursor-not-allowed'; ?>">
                    บันทึกรหัสผ่านใหม่
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            <a href="guest_login.php" class="font-medium text-sky-600 hover:text-sky-500 underline">
                กลับไปหน้าเข้าสู่ระบบ
            </a>
        </p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Script for password visibility toggle
        const toggleNewPassword = document.getElementById('toggleNewPassword');
        const newPasswordInput = document.getElementById('new_password');
        const toggleConfirmNewPassword = document.getElementById('toggleConfirmNewPassword');
        const confirmNewPasswordInput = document.getElementById('confirm_new_password');

        function setupPasswordToggle(toggleElement, inputElement) {
            if (toggleElement && inputElement) {
                toggleElement.addEventListener('click', function () {
                    const type = inputElement.getAttribute('type') === 'password' ? 'text' : 'password';
                    inputElement.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        }

        setupPasswordToggle(toggleNewPassword, newPasswordInput);
        setupPasswordToggle(toggleConfirmNewPassword, confirmNewPasswordInput);

        // Client-side validation for password match
        const resetPasswordForm = document.getElementById('resetPasswordForm');
        if (resetPasswordForm && newPasswordInput && confirmNewPasswordInput) {
            resetPasswordForm.addEventListener('submit', function(event) {
                if (newPasswordInput.value !== confirmNewPasswordInput.value) {
                    // Using SweetAlert for consistency if available, otherwise standard alert
                    Swal.fire({
                        title: 'ข้อผิดพลาด',
                        text: 'รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง',
                        icon: 'error',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#d33'
                    });
                    confirmNewPasswordInput.focus();
                    event.preventDefault(); // Prevent form submission
                }
                // Further client-side validation (e.g., password strength) can be added here if needed
            });
        }
    </script>
</body>
</html>
