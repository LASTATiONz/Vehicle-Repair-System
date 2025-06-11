<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน - Ornate Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }
        .form-input:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            outline: 0;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 to-sky-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-6 sm:p-8 md:p-10 rounded-xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 flex items-center justify-center bg-white-600 rounded-full text-white text-3xl font-bold mb-4">
                <img src="images/LOGO_ORNATE1.png" alt="Ornate Group Logo" class="object-contain mx-auto" />
            </div>
            <h1 class="text-3xl font-bold text-sky-700">ลืมรหัสผ่าน</h1>
            <p class="text-gray-600 mt-2">กรุณากรอกอีเมลที่ท่านใช้ในการสมัครสมาชิก <br>เราจะส่งลิงก์สำหรับตั้งรหัสผ่านใหม่ไปให้</p>
        </div>

        <form id="forgotPasswordForm" action="guest_process_forgot_password.php" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" placeholder="example@email.com" required
                       class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition duration-150 ease-in-out">
                    ส่งลิงก์รีเซ็ตรหัสผ่าน
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            จำรหัสผ่านได้แล้ว?
            <a href="guest_login.php" class="font-medium text-sky-600 hover:text-sky-500 underline">
                กลับไปหน้าเข้าสู่ระบบ
            </a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
