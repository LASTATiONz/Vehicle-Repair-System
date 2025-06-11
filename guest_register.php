<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - Ornate Group</title>
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
        .form-label {
            font-weight: 500;
        }
        /* Custom focus style to match Bootstrap's blue glow */
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
        /* Styling for the modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed for the modal itself (e.g. small screens) */
            background-color: rgba(0,0,0,0.6); /* Black w/ opacity */
            padding-top: 30px; 
            padding-bottom: 30px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto; /* Centered */
            padding: 20px;
            border: 1px solid #888;
            width: 90%; 
            max-width: 800px; 
            border-radius: 0.5rem;
            position: relative;
            overflow: auto; /* Allow scrolling for content like a large image */
            max-height: calc(100vh - 60px); /* Ensure modal content itself can scroll if needed */
        }
        .modal-image {
            display: block; /* Remove extra space below image */
            margin: auto; /* Center image if its natural width is smaller than modal-content */
            width: 100%; /* Initially, try to fill the width of modal-content */
            height: auto;
            max-height: 80vh; 
            object-fit: contain; 
            cursor: zoom-in; /* This cursor will be active when it's NOT zoomed */
            transition: transform 0.2s ease-out; /* Smooth transition for any transform effects */
        }
        .modal-image.zoomed {
            width: auto; /* Use natural width */
            height: auto; /* Use natural height */
            max-width: none; /* No max-width constraint from its parent's width */
            max-height: none; /* No max-height constraint */
            object-fit: initial; /* Display at natural size */
            cursor: zoom-out; /* This cursor will be active when it IS zoomed */
            /* The .modal-content with overflow:auto will handle scrolling */
        }
        .close-button {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10; /* Ensure close button is above the image */
        }
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 to-sky-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-6 sm:p-8 md:p-10 rounded-xl shadow-2xl w-full max-w-lg">
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 flex items-center justify-center bg-white-600 rounded-full text-white text-3xl font-bold mb-4">
                <img src="images/LOGO_ORNATE1.png" alt="Ornate Group Logo" class="object-contain mx-auto" />
            </div>
            <h1 class="text-3xl font-bold text-sky-700">สร้างบัญชีผู้ใช้ใหม่</h1>
            <p class="text-gray-600 mt-2">กรุณากรอกข้อมูลเพื่อสมัครสมาชิก</p>
        </div>

        <form id="registrationForm" action="guest_process_registration.php" method="POST" class="space-y-6">

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">คำนำหน้า <span class="text-red-500">*</span></label>
                <select id="title" name="title" required
                        class="form-select w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
                    <option value="" disabled selected>-- เลือกคำนำหน้า --</option>
                    <option value="นาย">นาย</option>
                    <option value="นาง">นาง</option>
                    <option value="นางสาว">นางสาว</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อ <span class="text-red-500">*</span></label>
                    <input type="text" id="first_name" name="first_name" placeholder="กรอกชื่อจริง" required
                           class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">นามสกุล <span class="text-red-500">*</span></label>
                    <input type="text" id="last_name" name="last_name" placeholder="กรอกนามสกุล" required
                           class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" placeholder="example@email.com" required
                       class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์มือถือ <span class="text-red-500">*</span></label>
                <input type="tel" id="phone" name="phone_formatted" placeholder="0XX-XXX-XXXX" required 
                       title="กรุณากรอกเบอร์โทรศัพท์มือถือ 10 หลัก" maxlength="12" 
                       class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
                <input type="hidden" id="phone_actual" name="phone">
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="สร้างรหัสผ่าน" required minlength="8"
                           class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
                    <i class="fa-solid fa-eye password-toggle-icon" id="togglePassword"></i>
                </div>
                <p class="text-xs text-gray-500 mt-1">อย่างน้อย 8 ตัวอักษร</p>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่าน <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="ยืนยันรหัสผ่านอีกครั้ง" required
                           class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-sky-500 focus:border-sky-500">
                    <i class="fa-solid fa-eye password-toggle-icon" id="toggleConfirmPassword"></i>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="terms_conditions_checkbox" name="terms_conditions_checkbox" type="checkbox" required
                           class="focus:ring-sky-500 h-4 w-4 text-sky-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms_conditions_checkbox" class="font-medium text-gray-700">ฉันยอมรับ <a href="pdpa/ornate_privacy_notice.png" id="termsLink" class="text-sky-600 hover:text-sky-500 underline">ข้อกำหนดและเงื่อนไข</a> และ <a href="pdpa/ornate_privacy_notice.png" id="privacyLink" class="text-sky-600 hover:text-sky-500 underline">นโยบายความเป็นส่วนตัว</a> <span class="text-red-500">*</span></label>
                </div>
            </div>

            <div>
                <button type="submit" id="submitButton"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition duration-150 ease-in-out">
                    สมัครสมาชิก
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            มีบัญชีอยู่แล้ว?
            <a href="guest_login.php" class="font-medium text-sky-600 hover:text-sky-500 underline">
                เข้าสู่ระบบที่นี่
            </a>
        </p>
    </div>

    <div id="pdpaModal" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closePdpaModal">&times;</span>
            <img id="pdpaImage" src="" alt="PDPA Notice" class="modal-image">
        </div>
    </div>

    <script>
        // Script for password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirm_password');

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

        setupPasswordToggle(togglePassword, passwordInput);
        setupPasswordToggle(toggleConfirmPassword, confirmPasswordInput);


        // Script for phone number formatting (0XX-XXX-XXXX)
        const phoneInputFormatted = document.getElementById('phone');
        const phoneInputActual = document.getElementById('phone_actual');

        if (phoneInputFormatted && phoneInputActual) {
            phoneInputFormatted.addEventListener('input', function (e) {
                let input = e.target.value.replace(/\D/g, ''); // Remove non-digits
                
                phoneInputActual.value = input; // Store the raw digits

                let formattedInput = '';
                if (input.length > 0) {
                    formattedInput = input.substring(0, 3);
                }
                if (input.length >= 4) {
                    formattedInput += '-' + input.substring(3, 6);
                }
                if (input.length >= 7) {
                    formattedInput += '-' + input.substring(6, 10);
                }
                e.target.value = formattedInput;
            });
        }


        // Script for password confirmation validation and form submission
        const registrationForm = document.getElementById('registrationForm');
        // const confirmPasswordInput = document.getElementById('confirm_password'); // Already declared above

        if (registrationForm && passwordInput && confirmPasswordInput) {
            registrationForm.addEventListener('submit', function (event) {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    alert('รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง');
                    confirmPasswordInput.focus();
                    event.preventDefault(); 
                    return false;
                }

                const termsCheckbox = document.getElementById('terms_conditions_checkbox');
                if (termsCheckbox && !termsCheckbox.checked) {
                    alert('กรุณายอมรับข้อกำหนดและเงื่อนไข และนโยบายความเป็นส่วนตัว');
                    event.preventDefault();
                    return false;
                }

                if (phoneInputActual && phoneInputActual.value.length !== 10) {
                    alert('กรุณากรอกเบอร์โทรศัพท์มือถือให้ครบ 10 หลัก');
                    phoneInputFormatted.focus();
                    event.preventDefault();
                    return false;
                }
            });
        }

        // PDPA Modal Logic
        const pdpaModal = document.getElementById('pdpaModal');
        const pdpaImage = document.getElementById('pdpaImage');
        const termsLink = document.getElementById('termsLink');
        const privacyLink = document.getElementById('privacyLink');
        const closeModalButton = document.getElementById('closePdpaModal');

        function openPdpaModal(event) {
            event.preventDefault(); 
            const imageUrl = event.target.getAttribute('href');
            if (pdpaImage && pdpaModal && imageUrl) {
                pdpaImage.src = imageUrl;
                pdpaImage.classList.add('zoomed'); // Auto zoom when modal opens
                pdpaModal.style.display = "block";
                 // Scroll modal content to top when (re)opened
                const modalContent = pdpaModal.querySelector('.modal-content');
                if (modalContent) {
                    modalContent.scrollTop = 0;
                }
            }
        }

        if (termsLink) {
            termsLink.addEventListener('click', openPdpaModal);
        }
        if (privacyLink) {
            privacyLink.addEventListener('click', openPdpaModal);
        }

        if (closeModalButton && pdpaModal) {
            closeModalButton.onclick = function() {
                pdpaModal.style.display = "none";
            }
        }

        window.onclick = function(event) {
            if (event.target == pdpaModal) {
                pdpaModal.style.display = "none";
            }
        }

        // Click to zoom image logic
        if (pdpaImage) {
            pdpaImage.addEventListener('click', function() {
                this.classList.toggle('zoomed');
                if (!this.classList.contains('zoomed')) { 
                    const modalContent = this.closest('.modal-content');
                    if (modalContent) {
                       // modalContent.scrollTop = 0; 
                    }
                }
            });
        }

    </script>

</body>
</html>
