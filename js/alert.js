// alert.js - รวมฟังก์ชันแจ้งเตือน SweetAlert2

// ฟังก์ชันแสดงแจ้งเตือน SweetAlert2
function showAlert(title, message, icon = "warning") {
    Swal.fire({
        title: title,
        text: message,
        icon: icon,
        confirmButtonText: "ตกลง",
        confirmButtonColor: "#CD5C5C"
    });
}

// ✅ ตรวจสอบการแนบไฟล์
function checkImageAttachment() {
    showAlert("แจ้งเตือน", "คุณยังไม่ได้แนบรูปถ่ายหน้าตรง กรุณาแนบไฟล์", "error");
}

function checkImageSize() {
    showAlert("แจ้งเตือน", "รูปถ่ายหน้าตรง มีขนาดใหญ่กว่า 10 Mb กรุณาเลือกรูปใหม่", "error");
}

function checkImageType() {
    showAlert("แจ้งเตือน", "รูปถ่ายหน้าตรง ต้องมีนามสกุล .jpg, .jpeg, .png เท่านั้น", "error");
}

function checkIDCard() {
    showAlert("แจ้งเตือน", "คุณกรอกเลขประจำตัวประชาชนไม่ครบ 13 หลัก", "error");
}

function checkAddressAttachment() {
    showAlert("แจ้งเตือน", "คุณยังไม่ได้แนบสำเนาทะเบียนบ้าน กรุณาแนบไฟล์", "error");
}

function checkIDCardAttachment() {
    showAlert("แจ้งเตือน", "คุณยังไม่ได้แนบสำเนาบัตรประชาชน กรุณาแนบไฟล์", "error");
}

// ✅ แจ้งเตือนการยินยอม
function checkAgree() {
    Swal.fire({
        title: "แจ้งเตือน",
        html: 'ท่านเลือก "<span class="text-danger">ไม่ยินยอม</span>" ระบบจะไม่บันทึกข้อมูลสมัครงานของท่านได้ <br>หากต้องการส่งข้อมูลสมัครงานโปรดเลือก "<span class="text-success">ยินยอม</span>"',
        icon: "warning",
        confirmButtonText: "ตกลง",
        confirmButtonColor: "#CD5C5C"
    });
}

// ✅ แจ้งเตือนการโหลดข้อมูล
function showLoading() {
    Swal.fire({
        title: "โปรดรอ...",
        html: '<div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div><p>กำลังรับข้อมูลสมัครงานของคุณ กรุณารอสักครู่...</p>',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// ✅ ตรวจสอบก่อนส่งฟอร์ม
function validateFormBeforeSubmit() {
    let imgUpload = document.getElementById("img_upload").files.length;
    let idCard = document.getElementById("idCard").value;
    let agree = document.querySelector('input[name="radioagree"]:checked');

    if (imgUpload === 0) {
        checkImageAttachment();
        return false;
    }
    if (idCard.length !== 13) {
        checkIDCard();
        return false;
    }
    if (!agree || agree.value !== "ยินยอม") {
        checkAgree();
        return false;
    }
    
    showLoading(); // แสดงโหลดข้อมูลก่อนส่งฟอร์ม
    document.getElementById("applicationForm").submit();
}


// ตรวจสอบการอัปโหลดรูปภาพ
function handleImageUpload(event) {
    let file = event.target.files[0]; // รับไฟล์ที่อัปโหลด
    let imgPreview = document.getElementById("previewImg");
    
    // ตรวจสอบว่ามีไฟล์ถูกเลือกหรือไม่
    if (!file) {
        Swal.fire({
            title: "แจ้งเตือน",
            text: "กรุณาเลือกไฟล์รูปภาพก่อน",
            icon: "warning",
            confirmButtonText: "ตกลง"
        });
        return;
    }

    // ตรวจสอบประเภทไฟล์ (เฉพาะ PNG, JPG, JPEG)
    let validExtensions = ["image/png", "image/jpg", "image/jpeg"];
    if (!validExtensions.includes(file.type)) {
        Swal.fire({
            title: "แจ้งเตือน",
            text: "อนุญาตเฉพาะไฟล์ .jpg, .jpeg, .png เท่านั้น",
            icon: "error",
            confirmButtonText: "ตกลง"
        });
        event.target.value = ""; // ล้างค่า input file
        return;
    }

    // ตรวจสอบขนาดไฟล์ (ต้องไม่เกิน 10MB)
    let maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
        Swal.fire({
            title: "แจ้งเตือน",
            text: "ขนาดไฟล์ต้องไม่เกิน 10MB กรุณาเลือกไฟล์ใหม่",
            icon: "error",
            confirmButtonText: "ตกลง"
        });
        event.target.value = ""; // ล้างค่า input file
        return;
    }

    // แสดงตัวอย่างภาพ (Preview)
    let reader = new FileReader();
    reader.onload = function(e) {
        imgPreview.src = e.target.result;
    };
    reader.readAsDataURL(file);

    // แจ้งเตือนว่าอัปโหลดสำเร็จ
    Swal.fire({
        title: "อัปโหลดสำเร็จ",
        text: "รูปภาพถูกอัปโหลดเรียบร้อย",
        icon: "success",
        timer: 1500,
        showConfirmButton: false
    });
}
