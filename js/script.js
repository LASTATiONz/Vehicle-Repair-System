// new code

// guest

$(document).ready(function() {
    // Function to initialize form validation
    function initFormValidation() {
       'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        }); 
    }
  
    //เช็คการโชว์ Error และ เช็คการกรอกเลขบัตรประชาชน 13 หลัก
   function handleInputValidation() {
        document.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.addEventListener('input', function() {
                if (input.id === 'idCard') { // ตรวจสอบเฉพาะฟิลด์เลขบัตรประชาชน
                    if (input.value.length === 17) { // ถ้าจำนวนตัวอักษรเท่ากับ 13
                        input.classList.add('is-valid');
                        input.classList.remove('is-invalid');
                        input.setCustomValidity(''); // เคลียร์ข้อความแจ้งเตือน
                    } else {
                        input.setCustomValidity('กรุณากรอกเลขบัตรประชาชน 13 หลัก'); // ตั้งค่าข้อความแจ้งเตือน
                        input.classList.add('is-invalid');
                        input.classList.remove('is-valid');
                    }
                    //input.reportValidity(); // เรียกเพื่อให้แน่ใจว่าจะแสดงการแจ้งเตือน
                } else {
                    // ตรวจสอบฟิลด์อื่นๆ โดยใช้ checkValidity ตามปกติ
                    if (input.checkValidity()) {
                        input.classList.add('is-valid');
                        input.classList.remove('is-invalid');
                    } else {
                        input.classList.add('is-invalid');
                        input.classList.remove('is-valid');
                    }
                }
            });
        });
    } 
       

    function saveSessionData(currentSession) {
        var formData = $('#applicationForm :input').serialize();
    
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: 'save_session.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error || "Unknown error");
                }
            });
        });
    }
    
        
    





    // Navigate between form sessions
    function goToNextSession(currentSession, nextSession) {
        var currentForm = document.getElementById(currentSession);
        var inputs = currentForm.querySelectorAll('input, select,textarea');  // Only validate inputs in the current session
        var isValid = true;

        $('#currentSession').val(currentSession); // Update hidden field with the current session value

        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.add('is-valid');
            }
        });

        if (isValid) {
            saveSessionData(currentSession)
                .then(function(response) {
                    if (response.includes("successfully") || response.includes("บันทึก")) {
                        if (currentSession === 'session6') {
                            // Don't show Swal, just move forward or do nothing (since you show waitingModal)
                            // Optional: You could auto-hide session6 UI if needed
                            return;
                        }
                        // ✅ Show success
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกเรียบร้อย',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            $('#' + currentSession).addClass('d-none');
                            $('#' + nextSession).removeClass('d-none');
    
                            const sessionIndex = parseInt(nextSession.replace('session', ''));
                            updateStepIndicator(sessionIndex);
                            scrollToCurrentStep(sessionIndex);
                        });
                    } else {
                        // ❌ Show fail
                        Swal.fire({
                            icon: 'error',
                            title: 'ไม่สามารถบันทึกได้',
                            text: 'โปรดเช็คการเชื่อมต่อและลองอีกครั้ง',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                })
                .catch(function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่สามารถบันทึกได้',
                        text: 'โปรดเช็คการเชื่อมต่อและลองอีกครั้ง',
                        confirmButtonText: 'ตกลง'
                    });
                });
    
        } else {
            currentForm.classList.add('was-validated');
                // ✅ Show your general modal if required fields are missing
            // var generalModal = new bootstrap.Modal(document.getElementById('check_required_fields'), {});
            // generalModal.show();
            Swal.fire({
                icon: 'warning',
                title: 'กรอกข้อมูลไม่ครบ',
                text: 'กรุณากรอกข้อมูลที่จำเป็นให้ครบก่อนดำเนินการต่อ',
                confirmButtonText: 'ตกลง'
              });
              

        }
    }

    // Initialize form navigation
    function initFormNavigation() {
        $('#nextToSession2').click(function() {
                var imgUploadElement = document.getElementById("img_upload");
                var isNewFileSelected = imgUploadElement.value !== "";

                // Only show the error if the input is marked as 'required'
                // AND no new file has been selected.
                if (imgUploadElement.required && !isNewFileSelected) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'แจ้งเตือน',
                        text: 'คุณยังไม่ได้แนบรูปถ่ายหน้าตรง กรุณาแนบไฟล์',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#d33'
                    });
                    return; // Stop further execution
                }
                // If we reach here, it means either:
                // 1. The field was not required (an old image exists, and PHP removed the 'required' attribute), OR
                // 2. A new file was selected by the user.
                goToNextSession('session1', 'session2');
        });

        $('#nextToSession3').click(function() {
            goToNextSession('session2', 'session3');
        });

        $('#nextToSession4').click(function() {
            goToNextSession('session3', 'session4');
        });

        $('#nextToSession5').click(function() {
            goToNextSession('session4', 'session5');
        });

        $('#nextToSession6').click(function() {
            goToNextSession('session5', 'session6');
        });

        $('#session_end').click(function() {
            // เช็คการแนบไฟล์หลักฐานต่างๆ
            var idcard = document.getElementById("idcard_upload").value;
            var address = document.getElementById("address_upload").value;
            // เช็คแนบไฟล์สำเนาบัตรประชาชน
            if(idcard == ""){
                Swal.fire({
                    icon: 'warning',
                    title: 'แจ้งเตือน',
                    text: 'กรุณาแนบสำเนาบัตรประชาชนก่อนดำเนินการต่อ',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'ตกลง'
                });            
                return;
            }
            // เช็คแนบไฟล์สำเนาทะเบียนบ้าน
            if(address == ""){
                Swal.fire({
                    icon: 'warning',
                    title: 'แจ้งเตือน',
                    text: 'กรุณาแนบสำเนาทะเบียนบ้านก่อนดำเนินการต่อ',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'ตกลง'
                });            
                return;
            }
            goToNextSession('session6', 'session6');
        });

        $('#prevToSession1').click(function() {
            $('#session2').addClass('d-none');
            $('#session1').removeClass('d-none');
            updateStepIndicator(1);     // เพิ่มฟังก์ชันนี้

        });

        $('#prevToSession2').click(function() {
            $('#session3').addClass('d-none');
            $('#session2').removeClass('d-none');
            updateStepIndicator(2);     // เพิ่มฟังก์ชันนี้

        });

        $('#prevToSession3').click(function() {
            $('#session4').addClass('d-none');
            $('#session3').removeClass('d-none');
            updateStepIndicator(3);     // เพิ่มฟังก์ชันนี้

        });

        $('#prevToSession4').click(function() {
            $('#session5').addClass('d-none');
            $('#session4').removeClass('d-none');
            updateStepIndicator(4);     // เพิ่มฟังก์ชันนี้

        });

        $('#prevToSession5').click(function() {
            $('#session6').addClass('d-none');
            $('#session5').removeClass('d-none');
            updateStepIndicator(5);     // เพิ่มฟังก์ชันนี้

        });
    }


    // ดึงข้อมูลสาขาและตำแหน่งจากชื่อบริษัท
    function initBranchAndjob_name() {
        $('#sub_branch').change(function() {
            var sub_branch = $(this).val();
            $('#branch').html('<option selected disabled value="">--เลือก--</option>');
            $('#job_name').html('<option selected disabled value="">--เลือก--</option>');
            // console.log("Sub_branch changed to (script.js):", sub_branch);

            if (sub_branch != '') {
                $.ajax({
                    url: "fetch_branch.php",
                    method: "POST",
                    data: { action: 'fetch_branch', sub_branch: sub_branch },
                    success: function(data) {
                        $('#branch').html(data);
                        // console.log("#branch options populated by AJAX (script.js).");

                        // **** DEBUGGING RIGHT BEFORE THE IF CONDITION ****
                        // console.log("SCRIPT.JS DEBUG: Inside #sub_branch AJAX success:");
                        // console.log("SCRIPT.JS DEBUG: typeof window.isEditMode:", typeof window.isEditMode, "Value:", window.isEditMode);
                        // console.log("SCRIPT.JS DEBUG: typeof window.prefillBranchJob:", typeof window.prefillBranchJob, "Value:", window.prefillBranchJob);
                        // **** END DEBUGGING ****

                        if (typeof window.isEditMode !== 'undefined' && window.isEditMode &&
                            typeof window.prefillBranchJob !== 'undefined' && window.prefillBranchJob !== '') {
                            
                            // console.log("SCRIPT.JS: Attempting to re-select #branch to:", window.prefillBranchJob);
                            $('#branch').val(window.prefillBranchJob); 
                            
                            if ($('#branch').val() === window.prefillBranchJob) {
                                // console.log("SCRIPT.JS: #branch re-selection SUCCESSFUL. Triggering change.");
                                $('#branch').trigger('change');
                            } else {
                                // console.error("SCRIPT.JS: #branch re-selection FAILED. Current value:", $('#branch').val(), "Expected:", window.prefillBranchJob);
                                var options = [];
                                $('#branch option').each(function() { options.push($(this).val()); });
                                // console.log("SCRIPT.JS: Available #branch options:", options);
                            }
                        } else {
                            // This is the path your log indicated was taken
                            // console.log("SCRIPT.JS: Condition for re-selecting #branch was FALSE. isEditMode:", window.isEditMode, "prefillBranchJob:", window.prefillBranchJob);
                        }
                    }
                });
            }
        });

        $('#branch').change(function() {
            var sub_branch = $('#sub_branch').val();
            var branch = $(this).val();
            $('#job_name').html('<option selected disabled value="">--เลือก--</option>');
            // console.log("Branch changed to (script.js):", branch);

            if (sub_branch != '' && branch != '') {
                $.ajax({
                    url: "fetch_branch.php", 
                    method: "POST",
                    data: { action: 'fetch_job_name', sub_branch: sub_branch, branch: branch },
                    success: function(data) {
                        $('#job_name').html(data); 
                        // console.log("#job_name options populated by AJAX (script.js).");
                        
                        // **** DEBUGGING RIGHT BEFORE THE IF CONDITION ****
                        // console.log("SCRIPT.JS DEBUG: Inside #branch AJAX success (for job_name):");
                        // console.log("SCRIPT.JS DEBUG: typeof window.isEditMode:", typeof window.isEditMode, "Value:", window.isEditMode);
                        // console.log("SCRIPT.JS DEBUG: typeof window.prefillJobNoDb:", typeof window.prefillJobNoDb, "Value:", window.prefillJobNoDb);
                        // **** END DEBUGGING ****

                        if (typeof window.isEditMode !== 'undefined' && window.isEditMode &&
                            typeof window.prefillJobNoDb !== 'undefined' && window.prefillJobNoDb !== '') {
                            
                            // console.log("SCRIPT.JS: Attempting to re-select #job_name to:", window.prefillJobNoDb);
                            $('#job_name').val(window.prefillJobNoDb);

                            if ($('#job_name').val() === window.prefillJobNoDb) {
                                // console.log("SCRIPT.JS: #job_name re-selection SUCCESSFUL.");
                            } else {
                                // console.error("SCRIPT.JS: #job_name re-selection FAILED. Current value:", $('#job_name').val(), "Expected:", window.prefillJobNoDb);
                                var options = [];
                                $('#job_name option').each(function() { options.push($(this).val()); });
                                // console.log("SCRIPT.JS: Available #job_name options:", options);
                            }
                        } else {
                            // console.log("SCRIPT.JS: Condition for re-selecting #job_name was FALSE. isEditMode:", window.isEditMode, "prefillJobNoDb:", window.prefillJobNoDb);
                        }
                    }
                });
            }
        });
    }



    // เรียกใช้งานฟังก์ชันต่างๆ
    function init() {
        initFormValidation();     // Form validation
        handleInputValidation();  // Real-time validation
        initFormNavigation();     // Form section navigation
        initBranchAndjob_name();   // Branch and job_name fetching
    }

    // Call the init function
    init();
});

//เปลี่ยนรูปแบบ บัตรประชาชน และ เบอร์โทร
function autoTab(obj, typeCheck) {
    // Define patterns based on the typeCheck value
    var pattern, pattern_ex;
    if (typeCheck == 1) {
        pattern = "_-____-_____-_-__"; // IDcard 
        pattern_ex = "-";
    } else if (typeCheck == 2) {
        pattern = "__-___-____"; // เบอร์โทรศัพท์
        pattern_ex = "-";
    } else {
        pattern = "___-___-____"; // Default pattern
        pattern_ex = "-";
    }

    var cleanValue = obj.value.replace(/[^0-9]/g, ""); // Remove all non-numeric characters
    var returnText = "";

    // Iterate through the pattern and apply formatting to the cleaned value
    for (var i = 0, j = 0; i < pattern.length && j < cleanValue.length; i++) {
        if (pattern.charAt(i) == pattern_ex) {
            returnText += pattern_ex;
        } else {
            returnText += cleanValue.charAt(j);
            j++;
        }
    }

    obj.value = returnText;
}

//ออร์โต้ เพศ และ เปิด/ปิด สถานภาพทหารถ้าเลือกเพศชาย
function auto_sex() {
    var title = document.getElementById("title").value;
    if (title == "นาย") {
        document.getElementById("sex").value = "ชาย";
        document.getElementById("military").style.display = "block";

        $('#not_military').attr('required', true);
        $('#discharged').attr('required', true);
        $('#studied_military').attr('required', true);
        $('#exempted').attr('required', true);

    } else if (title == "นาง" || title == "นางสาว") {
        document.getElementById("sex").value = "หญิง";
        document.getElementById("military").style.display = "none";

        $('#not_military').removeAttr('required');
        $('#discharged').removeAttr('required');
        $('#studied_military').removeAttr('required');
        $('#exempted').removeAttr('required');
        
    } else {
        document.getElementById("sex").value = "";
        document.getElementById("military").style.display = "block";

        $('#not_military').attr('required', true);
        $('#discharged').attr('required', true);
        $('#studied_military').attr('required', true);
        $('#exempted').attr('required', true);
    }
}
//ออร์โต้ เพศ และ เปิด/ปิด สถานภาพทหาร ถ้าเลือกเพศหญิง
function auto_sexs() {
    var sex = document.getElementById("sex").value;
         if (sex == "หญิง") {
        document.getElementById("military").style.display = "none";

        $('#not_military').removeAttr('required');
        $('#discharged').removeAttr('required');
        $('#studied_military').removeAttr('required');
        $('#exempted').removeAttr('required');
        
    } else {
        document.getElementById("military").style.display = "block";

        $('#not_military').attr('required', true);
        $('#discharged').attr('required', true);
        $('#studied_military').attr('required', true);
        $('#exempted').attr('required', true);
    }
}
//คำนวณอายุ
function calAge(o) {
    var tmp = o.value.split("-");
    var current = new Date();
    var current_year = current.getFullYear();
    if (tmp[0] <= 2500) {
        document.getElementById("age").value = current_year - tmp[0] + " ปี";
    } else {
        document.getElementById("age").value = (current_year + 543) - tmp[0] + " ปี";
    }
}
//เช็คสถานภาพสมรส
function status_div(){
    var status = document.getElementById("status").value;
    if(status == "โสด" ){
     document.getElementById("spouse").style.display = "none";
     document.getElementById("spouse_name").value = "";
     document.getElementById("spouse_age").value = "";
     document.getElementById("spouse_occupation").value = "";
     document.getElementById("spouse_Place_work").value = "";
     document.getElementById("spouse_talephone").value = "";
     document.getElementById("children").value = "";
    }else {
     document.getElementById("spouse").style.display = "block";
    }
}
//เพิ่มคอมมา เงินเดือน
function numberWithCommas() {
    var salary = document.getElementById("salary").value;
    document.getElementById("salary").value = salary.replace(/\B(?=(\d{3})+\b)/g, ",");
}

//เช็คความพิการ
$(function () {
    $("#disabled_input").hide();
    $("input[name='radiodisabled']").click(function () {
        if ($("#disabled").is(":checked")) {
            $("#disabled_input").removeAttr("disabled").show().focus();
            $("#disabled_input").attr('required', true);
        } else {
            $("#disabled_input").attr("disabled", "disabled").hide().val("");
            document.getElementById("disabled_input").value = "";
            $('#disabled_input').removeAttr('required');
        }
    });
});

//เช็คโรคประจำตัว
$(function () {
    $("#disease_input").hide();
    $("input[name='radiocongenital_disease']").click(function () {
        if ($("#yes_disease").is(":checked")) {
            $("#disease_input").removeAttr("disabled").show().focus();
            $("#disease_input").attr('required', true);
        } else {
            $("#disease_input").attr("disabled", "disabled").hide().val("");
            document.getElementById("disease_input").value = "";
            $('#disease_input').removeAttr('required');
        }
    });
});

// สคริปเรียกปุ่มเพิ่มฟอร์มการศึกษาล่าสุด,การทำงาน
    document.addEventListener('DOMContentLoaded', function () {
            // --- Existing functionality for "การศึกษาล่าสุด" ---
            // Assuming you already have some logic here for the "การศึกษาล่าสุด" button
            // Example:
            const prevEducationSection = document.getElementById('prevEducationSection');
            const addPrevEducationBtn = document.getElementById('addPrevEducationBtn');
            
            if (addPrevEducationBtn) {
                addPrevEducationBtn.addEventListener('click', function() {
                    prevEducationSection.classList.remove('hidden');
                    addPrevEducationBtn.classList.add('hidden');
                });
            }
        
            // --- New functionality for adding work experience forms ---
            let currentWorkForm = 1;
            const maxWorkForms = 4;
            const addMoreWorkBtn = document.getElementById('addMoreWorkBtn');
            
            if (addMoreWorkBtn) {
                addMoreWorkBtn.addEventListener('click', function () {
                    currentWorkForm++;
        
                    if (currentWorkForm <= maxWorkForms) {
                        // Show the next work form
                        document.getElementById('work_' + currentWorkForm).classList.remove('hidden');
        
                        // If the current form is the last one, hide the button
                        if (currentWorkForm === maxWorkForms) {
                            addMoreWorkBtn.classList.add('hidden');
                        }
                    }
                });
            }
    });

    //อัพโหลดรูปภาพหน้าตรง
    function previewImage(event) {
        const preview = document.getElementById('previewImg');
        const file = event.target.files[0];
        const imagePreview = document.querySelector('.imagePreview');
        const currentFileNameElement = document.getElementById('currentFileName'); // Get the small tag

        if (currentFileNameElement) {
            currentFileNameElement.style.display = 'none'; // Hide filename text when a new file is chosen
        }

    
        if (file) {
            const img_size = file.size / (1024 * 1024); // ขนาดไฟล์ในหน่วย MB
            const fileType = file.type; // ประเภทของไฟล์
    
            // ตรวจสอบว่าชนิดไฟล์เป็น jpg, jpeg หรือ png
            if (fileType !== 'image/jpeg' && fileType !== 'image/jpg' && fileType !== 'image/png') {
                // ถ้าชนิดไฟล์ไม่ถูกต้อง
                preview.style.display = 'none'; // ซ่อนรูป preview (img)
                imagePreviewDiv.style.backgroundImage = "url('images/avatar.png')"; // เปลี่ยนเป็นรูป default
                imagePreviewDiv.style.backgroundSize = 'cover'; // ให้รูปเต็มขนาด
                imagePreviewDiv.style.backgroundPosition = 'center 0px'; // เลื่อนลง 5px
    
                // แสดงแจ้งเตือน
                Swal.fire({
                    icon: 'error',
                    title: 'ชนิดไฟล์ไม่ถูกต้อง',
                    text: 'กรุณาแนบไฟล์ที่เป็น .jpg, .jpeg หรือ .png เท่านั้น',
                    confirmButtonText: 'ตกลง'
                });
                // รีเซ็ตค่า input file
                document.getElementById("img_upload").value = "";
            } else if (img_size > 10) {
                // ถ้าขนาดไฟล์เกิน 10 MB ให้แสดง avatar.png เป็น background image
                preview.style.display = 'none'; // ซ่อนรูป preview (img)
                imagePreviewDiv.style.backgroundImage = "url('images/avatar.png')"; // เปลี่ยนเป็นรูป default
                imagePreviewDiv.style.backgroundSize = 'cover'; // ให้รูปเต็มขนาด
                imagePreviewDiv.style.backgroundPosition = 'center 0px'; // เลื่อนลง 5px
    
                // แสดงแจ้งเตือน
                Swal.fire({
                    icon: 'error',
                    title: 'ขนาดไฟล์ใหญ่เกินไป',
                    text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 10MB',
                    confirmButtonText: 'ตกลง'
                });
                
    
                // รีเซ็ตค่า input file
                document.getElementById("img_upload").value = "";
            } else {
                // ถ้าขนาดไฟล์และชนิดไฟล์ถูกต้อง ให้แสดงภาพที่อัปโหลด
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // แสดงรูป preview (img)
                    
                    // ลบ background image ของ .imagePreview เพื่อไม่ให้มีสองรูปซ้อนกัน
                    imagePreviewDiv.style.backgroundImage = 'none';
                }
                reader.readAsDataURL(file);
            }
        }else { 
            preview.style.display = 'none';
            imagePreviewDiv.style.backgroundImage = "url('images/avatar.png')";
            preview.src = '#'; 
        }
    }
    
    //อัฟโหลดสำเนาบัตรประชาชน
    function idcardsize(idcard) {
        const idcard_size = idcard.files[0].size / 1024 / 1024; // in MiB
        var idcard_name = idcard.files[0].name;
        var idcardType = idcard.files[0].type;
        var validTypes = ["image/jpeg", "image/jpg", "image/png", "application/pdf"];
      
        if (idcard_size > 10) {
          //alert('ไฟล์แนบ!! สำเนาบัตรประจำตัวประชาชน มีขนาดใหญ่กว่า 10 Mb กรุณาแนบไฟล์ใหม่');
          // แสดงแจ้งเตือน
          Swal.fire({
            icon: 'error',
            title: 'ขนาดไฟล์ใหญ่เกินไป',
            text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 10MB',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("idcard_upload").value = "";
          document.getElementById("idcard_name").innerHTML = "";
        } else {
          document.getElementById("idcard_name").innerHTML = "ชื่อไฟล์ : "+idcard_name+" , ขนาดไฟล์ : "+idcard_size.toFixed(2)+" Mb";
        }
      
        if ($.inArray(idcardType, validTypes) < 0) {
          //alert("สำเนาบัตรประจำตัวประชาชน ต้องมีนามสกุล .jpg, .jpeg, .png, .pdf");
          Swal.fire({
            icon: 'error',
            title: 'ชนิดไฟล์ไม่ถูกต้อง',
            text: 'กรุณาแนบไฟล์ที่เป็น .jpg, .jpeg, .png, .pdf เท่านั้น',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("idcard_upload").value = "";
          document.getElementById("idcard_name").innerHTML = "";
        } else {}
    }
    
    //อัฟโหลดสำเนาทะเบียนบ้าน
    function addresssize(address) {
        const address_size = address.files[0].size / 1024 / 1024; // in MiB
        var address_name = address.files[0].name;
        var addressType = address.files[0].type;
        var validTypes = ["image/jpeg", "image/jpg", "image/png", "application/pdf"];
      
        if (address_size > 10) {
          //alert('ไฟล์แนบ!! สำเนาทะเบียนบ้าน มีขนาดใหญ่กว่า 2 Mb กรุณาแนบไฟล์ใหม่');
          Swal.fire({
            icon: 'error',
            title: 'ขนาดไฟล์ใหญ่เกินไป',
            text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 10MB',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("address_upload").value = "";
          document.getElementById("address_name").innerHTML = "";
        } else {
          document.getElementById("address_name").innerHTML = "ชื่อไฟล์ : "+address_name+" , ขนาดไฟล์ : "+address_size.toFixed(2)+" Mb";
        }
      
        if ($.inArray(addressType, validTypes) < 0) {
          //alert("สำเนาทะเบียนบ้าน ต้องมีนามสกุล .jpg, .jpeg, .png, .pdf");
          Swal.fire({
            icon: 'error',
            title: 'ชนิดไฟล์ไม่ถูกต้อง',
            text: 'กรุณาแนบไฟล์ที่เป็น .jpg, .jpeg, .png, .pdf เท่านั้น',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("address_upload").value = "";
          document.getElementById("address_name").innerHTML = "";
        } else {}
    }

    //อัฟโหลดสำเนาผลการเรียน
    function transcriptsize(transcript) {
        const transcript_size = transcript.files[0].size / 1024 / 1024; // in MiB
        var transcript_name = transcript.files[0].name;
        var transcriptType = transcript.files[0].type;
        var validTypes = ["image/jpeg", "image/jpg", "image/png", "application/pdf"];
      
        if (transcript_size > 10) {
          //alert('ไฟล์แนบ!! สำเนาหลักฐานการศึกษา มีขนาดใหญ่กว่า 2 Mb กรุณาแนบไฟล์ใหม่');
          Swal.fire({
            icon: 'error',
            title: 'ขนาดไฟล์ใหญ่เกินไป',
            text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 10MB',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("transcript_upload").value = "";
          document.getElementById("transcript_name").innerHTML = "";
        } else {
          document.getElementById("transcript_name").innerHTML = "ชื่อไฟล์ : "+transcript_name+" , ขนาดไฟล์ : "+transcript_size.toFixed(2)+" Mb";
        }
      
        if ($.inArray(transcriptType, validTypes) < 0) {
          //alert("สำเนาหลักฐานการศึกษา .jpg, .jpeg, .png, .pdf");
          Swal.fire({
            icon: 'error',
            title: 'ชนิดไฟล์ไม่ถูกต้อง',
            text: 'กรุณาแนบไฟล์ที่เป็น .jpg, .jpeg, .png, .pdf เท่านั้น',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("transcript_upload").value = "";
          document.getElementById("transcript_name").innerHTML = "";
        } else {}
    }
    
    //อัฟโหลดสำเนาเกณฑ์ทหาร
    function malitarysize(malitary) {
        const malitary_size = malitary.files[0].size / 1024 / 1024; // in MiB
        var malitary_name = malitary.files[0].name;
        var malitaryType = malitary.files[0].type;
        var validTypes = ["image/jpeg", "image/jpg", "image/png", "application/pdf"];
      
        if (malitary_size > 10) {
          //alert('ไฟล์แนบ!! สำเนาหลักฐานทางทหาร มีขนาดใหญ่กว่า 2 Mb กรุณาแนบไฟล์ใหม่');
          Swal.fire({
            icon: 'error',
            title: 'ขนาดไฟล์ใหญ่เกินไป',
            text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 10MB',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("malitary_upload").value = "";
          document.getElementById("malitary_name").innerHTML = "";
        } else {
          document.getElementById("malitary_name").innerHTML = "ชื่อไฟล์ : "+malitary_name+" , ขนาดไฟล์ : "+malitary_size.toFixed(2)+" Mb";
        }
      
        if ($.inArray(malitaryType, validTypes) < 0) {
          //alert("สำเนาหลักฐานทางทหาร .jpg, .jpeg, .png, .pdf");
          Swal.fire({
            icon: 'error',
            title: 'ชนิดไฟล์ไม่ถูกต้อง',
            text: 'กรุณาแนบไฟล์ที่เป็น .jpg, .jpeg, .png, .pdf เท่านั้น',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("malitary_upload").value = "";
          document.getElementById("malitary_name").innerHTML = "";
        } else {}
    }
    
    //อัฟโหลดสำเนาใบขับขี่
    function driversize(driver) {
        const driver_size = driver.files[0].size / 1024 / 1024; // in MiB
        var driver_name = driver.files[0].name;
        var driverType = driver.files[0].type;
        var validTypes = ["image/jpeg", "image/jpg", "image/png", "application/pdf"];
      
        if (driver_size > 10) {
          //alert('ไฟล์แนบ!! สำเนาใบอนุญาตขับขี่ มีขนาดใหญ่กว่า 2 Mb กรุณาแนบไฟล์ใหม่');
          Swal.fire({
            icon: 'error',
            title: 'ขนาดไฟล์ใหญ่เกินไป',
            text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 10MB',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("driver_upload").value = "";
          document.getElementById("driver_name").innerHTML = "";
        } else {
          document.getElementById("driver_name").innerHTML = "ชื่อไฟล์ : "+driver_name+" , ขนาดไฟล์ : "+driver_size.toFixed(2)+" Mb";
        }
      
        if ($.inArray(driverType, validTypes) < 0) {
          //alert("สำเนาใบอนุญาตขับขี่ .jpg, .jpeg, .png, .pdf");
          Swal.fire({
            icon: 'error',
            title: 'ชนิดไฟล์ไม่ถูกต้อง',
            text: 'กรุณาแนบไฟล์ที่เป็น .jpg, .jpeg, .png, .pdf เท่านั้น',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("driver_upload").value = "";
          document.getElementById("driver_name").innerHTML = "";
        } else {}
    }
    
    //อัฟโหลดใบรับรองการผ่านงาน
    function certifysize(certify) {
        const certify_size = certify.files[0].size / 1024 / 1024; // in MiB
        var certify_name = certify.files[0].name;
        var certifyType = certify.files[0].type;
        var validTypes = ["image/jpeg", "image/jpg", "image/png", "application/pdf"];
      
        if (certify_size > 10) {
          //alert('ไฟล์แนบ!! เอกสารรับรองการผ่านงาน มีขนาดใหญ่กว่า 2 Mb กรุณาแนบไฟล์ใหม่');
          Swal.fire({
            icon: 'error',
            title: 'ขนาดไฟล์ใหญ่เกินไป',
            text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 10MB',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("certify_upload").value = "";
          document.getElementById("certify_name").innerHTML = "";
        } else {
          document.getElementById("certify_name").innerHTML = "ชื่อไฟล์ : "+certify_name+" , ขนาดไฟล์ : "+certify_size.toFixed(2)+" Mb";
        }
      
        if ($.inArray(certifyType, validTypes) < 0) {
          //alert("เอกสารรับรองการผ่านงาน .jpg, .jpeg, .png, .pdf");
          Swal.fire({
            icon: 'error',
            title: 'ชนิดไฟล์ไม่ถูกต้อง',
            text: 'กรุณาแนบไฟล์ที่เป็น .jpg, .jpeg, .png, .pdf เท่านั้น',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("certify_upload").value = "";
          document.getElementById("certify_name").innerHTML = "";
        } else {}
    }
    

    //อัฟโหลด portfolio หรือ resume
    function portfoliosize(portfolio) {
        const portfolio_size = portfolio.files[0].size / 1024 / 1024; // in MiB
        var portfolio_name = portfolio.files[0].name;
        var portfolioType = portfolio.files[0].type;
        var validTypes = ["application/pdf"];
      
        if (portfolio_size > 10) {
          //alert('ไฟล์แนบ!! portfolio หรือ resume มีขนาดใหญ่กว่า 10 Mb กรุณาแนบไฟล์ใหม่');
          Swal.fire({
            icon: 'error',
            title: 'ขนาดไฟล์ใหญ่เกินไป',
            text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 10MB',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("portfolio_upload").value = "";
          document.getElementById("portfolio_name").innerHTML = "";
        } else {
          document.getElementById("portfolio_name").innerHTML = "ชื่อไฟล์ : "+portfolio_name+" , ขนาดไฟล์ : "+portfolio_size.toFixed(2)+" Mb";
        }
      
        if ($.inArray(portfolioType, validTypes) < 0) {
          //alert("portfolio หรือ resume .pdf");
          Swal.fire({
            icon: 'error',
            title: 'ชนิดไฟล์ไม่ถูกต้อง',
            text: 'กรุณาแนบไฟล์ที่เป็น .pdf เท่านั้น',
            confirmButtonText: 'ตกลง'
            });
          document.getElementById("portfolio_upload").value = "";
          document.getElementById("portfolio_name").innerHTML = "";
        } else {}
    }

/// เช็คช่องทางสมัครงาน
$(document).ready(function() {
    // Select all checkboxes within the class '.form-check-input' (your current checkboxes)
    var requiredCheckboxes = $('input[name="checkboxnews[]"]');

    // Attach the change event listener to each checkbox in the group
    requiredCheckboxes.change(function() {
        // If any of the checkboxes is checked, remove the 'required' attribute from all checkboxes
        if (requiredCheckboxes.is(':checked')) {
            requiredCheckboxes.removeAttr('required');
        } else {
            // If none are checked, add the 'required' attribute back
            requiredCheckboxes.attr('required', 'required');
        }
    });

    // Ensure the required attribute is updated just before form submission to handle all edge cases
    $('form').on('submit', function(e) {
        if (!requiredCheckboxes.is(':checked')) {
            // If no checkbox is selected, add 'required' to one of the checkboxes
            requiredCheckboxes.first().attr('required', 'required');
        }
    });
});

//เช็คเมื่อกด ไม่ยินยอม
$(document).ready(function() {
    // ตรวจสอบการคลิกที่ radio button
    $('input[name="radioagree"]').click(function() {
        if ($("#not_agree")[0].checked) {  // ตรวจสอบว่า radio ที่ไม่ยินยอมถูกคลิกหรือไม่
            Swal.fire({
                icon: 'warning',
                title: 'แจ้งเตือน',
                text: 'คุณต้องยินยอมในการใช้ข้อมูลก่อนดำเนินการสมัครงาน',
                confirmButtonColor: '#d33',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                $("#not_agree").prop('checked', false); // ยกเลิกเลือก "ไม่ยินยอม"
            });
        }
    });
});





// popup แสดงสถานะการอัปโหลดหลักฐานต่างๆแบบ SweetAlert
$(document).ready(function() {
    $('#session_end').on('click', function() {
        var branchValue = $('#branch').val(); // Get value of #branch
        var idcardchValue = $('#idcard_upload').val();
        var addressValue = $('#address_upload').val();
        var agreeValue = $('input[name="radioagree"]:checked').val();
        let xhr; // Declare xhr variable outside the function
        
        // ตรวจสอบว่า branch มีค่าเป็นค่าว่างหรือไม่
        if (!branchValue || branchValue.trim() === "") {
            // alert('กรุณากรอก branch ก่อนทำการอัปโหลด');
            return; // หยุดการทำงานถ้า branch ว่าง
        }
        if (!agreeValue || agreeValue.trim() === "" || !idcardchValue || idcardchValue.trim() === "" || !addressValue || addressValue.trim() === "") {
            // alert('กรุณากรอก radioagree ก่อนทำการอัปโหลด');
            return; // หยุดการทำงานถ้า radioagree ไม่ยินยอม
        }
    
        var formData = new FormData();
        var imgFile = $('#img_upload')[0].files[0]; // Get file from input
        var idCardFile  = $('#idcard_upload')[0].files[0]; // Get file from input
        var addressFile  = $('#address_upload')[0].files[0]; // Get file from input
        var transcriptFile  = $('#transcript_upload')[0].files[0]; // Get file from input
        var malitaryFile  = $('#malitary_upload')[0].files[0]; // Get file from input
        var driverFile  = $('#driver_upload')[0].files[0]; // Get file from input
        var certifyFile  = $('#certify_upload')[0].files[0]; // Get file from input
        //var vaccinerecordFile  = $('#vaccinerecord_upload')[0].files[0]; // Get file from input
        var portfolioFile  = $('#portfolio_upload')[0].files[0]; // Get file from input
        
        formData.append('img_upload', imgFile); // Append file to FormData
        formData.append('idcard_upload', idCardFile ); // Append file to FormData
        formData.append('address_upload', addressFile ); // Append file to FormData
        formData.append('transcript_upload', transcriptFile ); // Append file to FormData
        formData.append('malitary_upload', malitaryFile ); // Append file to FormData
        formData.append('driver_upload', driverFile ); // Append file to FormData
        formData.append('certify_upload', certifyFile ); // Append file to FormData
        formData.append('portfolio_upload', portfolioFile ); // Append file to FormData
        formData.append('branch', branchValue); // Append branch to FormData

        // แสดง Modal รอ
        Swal.fire({
            title: 'กำลังอัปโหลดไฟล์ของคุณ...',
            html: `
              <div class="progress mt-2" style="height: 20px;">
                <div id="swalUploadBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                  role="progressbar" style="width: 0%">0%</div>
              </div>
              <div class="mt-1">
                <span id="swalUploadText">0 MB / 0 MB</span>
              </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCancelButton: true,
            cancelButtonText: 'ยกเลิกการอัปโหลด',
            showConfirmButton: false,
            didOpen: () => {
              Swal.showLoading();
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {
              xhr.abort(); // ✅ Now this works
              Swal.fire({
                icon: 'info',
                title: 'การอัปโหลดถูกยกเลิก',
                text: 'คุณยกเลิกการอัปโหลดไฟล์แล้ว',
                confirmButtonText: 'ตกลง'
              });
            }
          });
          
        setTimeout(function () {
            let responseHandled = false;
            xhr = new XMLHttpRequest(); // Initialize xhr here

            // Show progress bar and text in SweetAlert
            xhr.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    const mbLoaded = (e.loaded / (1024 * 1024)).toFixed(2);
                    const mbTotal = (e.total / (1024 * 1024)).toFixed(2);
        
                    $('#swalUploadBar').css('width', percent + '%').text(`${percent}%`);
                    $('#swalUploadText').text(`${mbLoaded} MB / ${mbTotal} MB`);
                }
            });
        
            xhr.onload = function () {
                const response = xhr.responseText;
                console.log("Response from server:", response); // ✅ OK ตรงนี้

        
                if (xhr.status === 200 && response.includes("อัปโหลดเรียบร้อย")) {
                    Swal.fire({
                        icon: 'success',
                        title: 'อัปโหลดสำเร็จ',
                        text: 'ระบบได้รับข้อมูลของคุณแล้ว',
                        showConfirmButton: false,
                        timer: 2500
                      }).then(() => {
                        window.location.href = 'home_job.php';
                      });
                      
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'การอัปโหลดล้มเหลว',
                        text: 'เซิร์ฟเวอร์ตอบกลับไม่สำเร็จ: ' + response,
                        confirmButtonText: 'ตกลง'
                      });
                }
            };

            // เช็คเน็ตหลุดระหว่างกดส่ง
            xhr.onerror = function () {
                if (!responseHandled) {
                    responseHandled = true;
            
                    Swal.fire({
                        icon: 'error',
                        title: 'การเชื่อมต่อล้มเหลว',
                        text: 'กรุณาตรวจสอบอินเทอร์เน็ตของคุณ',
                        confirmButtonText: 'ลองอีกครั้ง',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Retry the upload
                            setTimeout(() => {
                                $('#session_end').trigger('click');
                            }, 300);
                        }
                    });
                }
            };
            
        
            xhr.open("POST", "upload.php");
            xhr.send(formData);
        }, 500); // delay 500ms before starting the upload
        
    });
});



//ลบบังคับตอบคำถามเพิ่มเติมถ้าตำแหน่งที่เลือกตั้งค่าให้ไม่ต้องตอบคำถาม
$(document).ready(function() {
    // ฟัง event การเปลี่ยนแปลงหรือการใส่ข้อมูล
    $('#job_name').on('change input', function() {
        var job_no = $(this).val();

        if (job_no !== '') {
            $.ajax({
                url: "fetch_question.php", // PHP file เพื่อดึง job_question
                method: "POST",
                data: { job_no: job_no },
                success: function(data) {
                    //$('#job_question').val(data); // ใส่ค่า job_question ที่ได้ลงใน input
                    
                    if (data === "N") {
                        $('#more_infor1, #more_infor2, #more_infor3, #more_infor4, #more_infor5, #more_infor6')
                            .removeAttr('required'); // ลบ attribute required ถ้า job_question เป็น "N"
                        $('#span1, #span2, #span3, #span4, #span5, #span6').hide(); // ซ่อน * ทั้งหมด
                    } else {
                        $('#more_infor1, #more_infor2, #more_infor3, #more_infor4, #more_infor5, #more_infor6')
                            .attr('required', 'required'); // เพิ่ม attribute required ถ้า job_question ไม่เป็น "N"
                        $('#span1, #span2, #span3, #span4, #span5, #span6').show(); // โชว์ * ทั้งหมด
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", error); // แจ้ง error ถ้ามีปัญหา
                }
            });
        } else {
            //$('#job_question').val('');
            $('#more_infor1, #more_infor2, #more_infor3, #more_infor4, #more_infor5, #more_infor6')
            .attr('required', 'required'); // เพิ่ม attribute required ถ้า job_question ไม่เป็น "N"
            $('#span1, #span2, #span3, #span4, #span5, #span6').show(); // โชว์ * ทั้งหมด
        }
    });

    // กระตุ้น event change ทันทีหลังจากโหลดหน้า
    $('#job_name').trigger('change');
});









// ย่อขนาดไฟล์ภาพที่อัปโหลดใน input[type='file'] ที่เป็นรูปภาพ
// ตรวจสอบว่าเป็นไฟล์ภาพและย่อขนาดให้ไม่เกิน 1024px ในด้านใดด้านหนึ่ง

document.querySelectorAll("input[type='file']").forEach(input => {
    input.addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (!file || !file.type.startsWith("image/")) return;  // ตรวจสอบว่าเป็นไฟล์ภาพ

        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(e) {
            const img = new Image();
            img.src = e.target.result;
            img.onload = function() {
                const canvas = document.createElement("canvas");
                const ctx = canvas.getContext("2d");

                const maxSize = 1024;  // กำหนดขนาดสูงสุด
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    if (width > maxSize) {
                        height *= maxSize / width;
                        width = maxSize;
                    }
                } else {
                    if (height > maxSize) {
                        width *= maxSize / height;
                        height = maxSize;
                    }
                }

                canvas.width = width;
                canvas.height = height;
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(blob => {
                    const newFile = new File([blob], file.name, { type: "image/jpeg" });
                    const dt = new DataTransfer();
                    dt.items.add(newFile);
                    event.target.files = dt.files;
                }, "image/jpeg", 0.8);
            };
        };
    });
});


// Update Step Indicator ตัวจัดการเลข step และสีของ step
// Update Step Indicator เมื่อเปลี่ยน session
function updateStepIndicator(currentStep) {
    $('.step-item').each(function(index) {
        const stepIndex = index + 1;
        if(stepIndex < currentStep){
            $(this).addClass('completed').removeClass('active');
        } else if(stepIndex === currentStep){
            $(this).addClass('active').removeClass('completed');
        } else {
            $(this).removeClass('active completed');
        }
    });
}

// เพิ่มการ scroll ไปยัง Step ปัจจุบันโดยอัตโนมัติ (Mobile)
function scrollToCurrentStep(currentStep) {
    const stepElement = document.getElementById('step' + currentStep);
    if (stepElement) {
        stepElement.scrollIntoView({ behavior: 'smooth', inline: 'center' });
    }
}





// admin

    // ปุ่มกดดูต่างๆ ในหน้า admin
    //ดูข้อมูลตำแหน่งงาน
    function popview(no){	
        var job_no = no;
        mypopup = window.open('ornjob_details.php?job_no='+job_no , 'mypopup' , 'menuber=no,toorlbar=no,location=no,scrollbars=no, status=no,resizable=no,width=1024,height=750,top=30,left=270 ' );
        mypopup.focus();
    }
    //แก้ไขตำแหน่งงาน
    function popedit(no){	
        var job_no = no;
        mypopup = window.open('edit_job.php?job_no='+job_no , 'mypopup' , 'menuber=no,toorlbar=no,location=no,scrollbars=no, status=no,resizable=no,width=1024,height=750,top=30,left=270 ' );
        mypopup.focus();
    }
    //เพิ่มตำแหน่งงาน
    function popadd(){	
        mypopup = window.open('add_new_job.php' , 'mypopup' , 'menuber=no,toorlbar=no,location=no,scrollbars=no, status=no,resizable=no,width=1024,height=750,top=30,left=270 ' );
        mypopup.focus();
    }

    //บันทึกการสัมภาษณ์
    function popsave(no){
        var guest_no = no;
        mypopup = window.open('add_interviews.php?guest_no='+guest_no , 'mypopup' , 'menuber=no,toorlbar=no,location=no,scrollbars=no, status=no,resizable=no,width=1024,height=620,top=30,left=270 ' );
        mypopup.focus();
    }

    //ดูข้อมูลใบสมัครงาน
    function popWin(no){	
        var guest_no = no;
        mypopup = window.open('guest_details.php?guest_no='+guest_no , 'mypopup' , 'menuber=no,toorlbar=no,location=no,scrollbars=no, status=no,resizable=no,width=1024,height=750,top=30,left=270 ' );
        mypopup.focus();
    }

    //แก้ไขใบสมัครงาน
    function popWinEdit(no){	
        var guest_no = no;
        mypopup = window.open('admin_edit_guest.php?guest_no='+guest_no , 'mypopup' , 'menuber=no,toorlbar=no,location=no,scrollbars=no, status=no,resizable=no,width=1024,height=750,top=30,left=270 ' );
        mypopup.focus();
    }

    //ดูข้อมูลการสัมภาษณ์
    function close_fill() {
        var check = document.getElementById("selected").value;
        if(check == "ไม่ผ่าน"){
          $('#accept_job,#selected_date').attr("disabled", true); 
          document.getElementById("accept_job").value ="";
          document.getElementById("selected_date").value ="";
        }else{
          $('#accept_job,#selected_date').attr("disabled", false);
        }
    }
    


    ///ปุ่มกดดูFile ในหน้า Guest_view
    function fileUpload(type) {
        const uploadField = document.getElementById(`${type}_upload`);
        const path = document.getElementById("path").value;
        
        if (uploadField && uploadField.value) {
            const popupUrl = `${path}${uploadField.value}`;
            const popupOptions = 'menubar=no,toolbar=no,location=no,scrollbars=no,status=no,resizable=no,width=480,height=680,top=30,left=540';
            const popup = window.open(popupUrl, 'mypopup_att', popupOptions);
            if (popup) {
                popup.focus();
            }
        }
    }

    // แสดงข้อมูลผลการค้นหาในหน้า table HR
    $(document).ready(function() {
        $('#table,#table1').DataTable({
            "language": {
                "lengthMenu": "แสดงข้อมูล _MENU_ รายการ",
                "search": "ค้นหา : ",
                "searchPlaceholder": "กรอกคำที่ต้องการค้นหา",
                "zeroRecords": "ไม่พบรายการที่คุณต้องการค้นหา",
                "info": "แสดงข้อมูล _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                "infoEmpty": "แสดงข้อมูล 0 ถึง _END_ จาก _TOTAL_ รายการ",
                "infoFiltered": "(จากทั้งหมด _MAX_ รายการ)",
                "paginate": {
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                }
            }
        });
    } );




    //Dropdown สวัสดิการ ในหน้าสร้างตำแหน่งงาน
    document.addEventListener('DOMContentLoaded', () => {
        const selects = document.querySelectorAll('.benefits-select');

        const updateOptions = () => {
            // รวบรวมค่าที่ถูกเลือก
            const selectedValues = Array.from(selects)
                .map(select => select.value)
                .filter(value => value !== '');

            // อัปเดตตัวเลือกใน dropdown ทั้งหมด
            selects.forEach(select => {
                const currentValue = select.value;
                Array.from(select.options).forEach(option => {
                    if (option.value === "" || option.value === currentValue) {
                        option.hidden = false; // แสดงตัวเลือกปัจจุบัน
                    } else {
                        option.hidden = selectedValues.includes(option.value); // ซ่อนค่าที่ถูกเลือกไปแล้ว
                    }
                });
            });
        };

        // ผูก event listener กับ dropdowns
        selects.forEach(select => {
            select.addEventListener('change', updateOptions);
        });
    });

// end HR