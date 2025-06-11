<?php
    session_start();
    include 'db_connect.php';
    require_once 'include/write_log.php'; // Include the write_log.php';



    $branch = isset($_POST['branch']) ? trim($_POST['branch']) : "";
    $guest_no = $_SESSION['guest_no'] ?? "";
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';


    write_log("🔹 เริ่มการอัปโหลด - Branch: $branch | Guest No: $guest_no");

    // ✅ Check if upload was canceled or connection dropped
    // 🔍 ตรวจสอบว่าไฟล์สำคัญทั้งหมดถูกอัปโหลดครบหรือไม่
    function areAllRequiredFilesUploaded(): bool {
        $requiredFields = ['img_upload', 'idcard_upload', 'address_upload'];

        foreach ($requiredFields as $field) {
            if (
                !isset($_FILES[$field]) ||
                $_FILES[$field]['error'] !== UPLOAD_ERR_OK
            ) {
                write_log("❌ ไฟล์สำคัญไม่สมบูรณ์: $field | error: " . ($_FILES[$field]['error'] ?? 'ไม่ทราบ'));
                return false; // ❌ อย่างน้อยหนึ่งไฟล์ไม่มีหรือมีปัญหา
            }
        }
        return true; // ✅ ทุกไฟล์อัปโหลดสำเร็จ
    }
    

    if (empty($branch)) {
        write_log("⚠️ ค่าของ Branch ว่างเปล่า (อาจเกิดจากการยกเลิกกลางทาง)");
    }

    // ✅ ตรวจสอบสถานะการอัปโหลด
    if (!areAllRequiredFilesUploaded()) {
        write_log("⛔ การอัปโหลดถูกยกเลิก หรือไม่มีไฟล์ถูกส่ง (Guest No: $guest_no | Branch: $branch | IP: $ip)");
        exit(); // 🛑 หยุดการทำงาน
    }



    // ตรวจสอบ guest_no
    // ตรวจสอบว่ามี guest_no หรือไม่
    if (!isset($_SESSION['guest_no']) || empty($_SESSION['guest_no'])) {
        echo json_encode([
            "status" => "error",
            "message" => "ไม่สามารถส่งข้อมูลได้ กรุณาบันทึกข้อมูลในหน้าแรกอีกครั้ง    "
        ]);
        exit();
    }


    // ตั้งค่าโฟลเดอร์อัปโหลด
    $upload_directory = "File_Upload_{$branch}/{$guest_no}";

    // ตรวจสอบว่ามีโฟลเดอร์หรือไม่ หากไม่มีให้สร้าง
    if (!is_dir($upload_directory)) {
        mkdir($upload_directory, 0777, true);
        write_log("📂 สร้างโฟลเดอร์: $upload_directory");

    }

    // เช็คว่าได้มีการสร้างหรือไม่ ถ้าไม่มีให้เขียนลง log
    if (!is_dir($upload_directory) && !mkdir($upload_directory, 0777, true)) {
        write_log("❌ ไม่สามารถสร้างโฟลเดอร์ได้: $upload_directory");
        die("Error: ไม่สามารถสร้างโฟลเดอร์สำหรับอัปโหลดไฟล์");
    }
    

    /** 
     * ฟังก์ชันสำหรับอัปโหลดรูปภาพ (JPG, JPEG, PNG)
     * ✅ หมุนภาพอัตโนมัติ (EXIF)
     * ✅ ลดขนาดภาพให้โหลดเร็วขึ้น
     */
    function uploadImage($file, $guest_no, $upload_directory, $db_field, $conn) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            write_log("❌ ข้อผิดพลาดในการอัปโหลดไฟล์ ($db_field): Code " . $file['error']);
            return false;
        
        }
    
        $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
        $fileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    
        if (!in_array($fileType, $allowed_types)) {
            write_log("❌ ประเภทไฟล์ไม่ได้รับอนุญาต: $fileType");
            return false;
        }
    
        // จำกัดขนาดไฟล์ไม่เกิน 20MB
        if ($file['size'] > 20 * 1024 * 1024) {
            write_log("❌ ไฟล์ใหญ่เกินไป: " . round($file['size'] / (1024 * 1024), 2) . "MB");
            return false;
        }
    
        // ตั้งชื่อไฟล์เป็น .jpg เสมอ เพื่อลดขนาดไฟล์
        $file_name = "{$db_field}_{$guest_no}.jpg";
        $target_file = "{$upload_directory}/{$file_name}";
    
        // โหลดรูปภาพจากไฟล์ต้นฉบับ
        switch ($fileType) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'png':
                $image = imagecreatefrompng($file['tmp_name']);
                break;
            case 'webp':
                $image = imagecreatefromwebp($file['tmp_name']);
                break;
            default:
                write_log("❌ ประเภทไฟล์รูปภาพไม่รองรับ: $fileType");
                return false;
        }
    
        if (!$image) {
            write_log("❌ ไม่สามารถโหลดไฟล์รูปภาพได้: " . $file['name']);
            return false;
        }
    
        list($width, $height) = getimagesize($file['tmp_name']);
    
        // ตรวจสอบการหมุนของภาพ (EXIF)
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($file['tmp_name']);
            if ($exif && isset($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3: $image = imagerotate($image, 180, 0); break;
                    case 6: $image = imagerotate($image, -90, 0); break;
                    case 8: $image = imagerotate($image, 90, 0); break;
                }
            }
        }
    
        // ตรวจสอบว่าต้องย่อขนาดหรือไม่ (เฉพาะภาพที่ใหญ่กว่า 1024px)
        $max_size = 1024;
        if ($width > $max_size || $height > $max_size) {
            $new_width = ($width > $height) ? $max_size : round(($width / $height) * $max_size);
            $new_height = ($height > $width) ? $max_size : round(($height / $width) * $max_size);
    
            $new_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
            // บันทึกเป็น JPG (ลดขนาดไฟล์)
            if (imagejpeg($new_image, $target_file, 80)) {
                write_log("✅ อัปโหลดไฟล์และลดขนาดสำเร็จ: $target_file");
            } else {
                write_log("❌ การบันทึกไฟล์ล้มเหลว: $target_file");
            }
            imagedestroy($new_image);
        } else {
            // ถ้าขนาดพอดี ให้เซฟไฟล์ต้นฉบับเป็น JPG ทันที
            if (imagejpeg($image, $target_file, 80)) {
                write_log("✅ อัปโหลดไฟล์โดยไม่ต้องย่อขนาด: $target_file");
            } else {
                write_log("❌ การบันทึกไฟล์ล้มเหลว: $target_file");
            }
        }
    
        imagedestroy($image);
    
        // อัปเดตฐานข้อมูล
        $sql = "UPDATE resume_job SET $db_field = ? WHERE guest_no = ?";
        $params = array($file_name, $guest_no);
        $stmt = sqlsrv_query($conn, $sql, $params);
    
        if ($stmt === false) {
            $errors = sqlsrv_errors();
            write_log("❌ อัปเดตฐานข้อมูลล้มเหลว: guest_no = $guest_no, field = $db_field, file = $file_name");
            
            if ($errors) {
                foreach ($errors as $error) {
                    write_log("  - SQLSTATE: " . $error['SQLSTATE']);
                    write_log("  - Code: " . $error['code']);
                    write_log("  - Message: " . $error['message']);
                }
            }
        } else {
            write_log("✅ อัปเดตฐานข้อมูลสำเร็จ: guest_no = $guest_no, field = $db_field, file = $file_name");
        }
    
        return true;
    }
    

    /** 
     * ฟังก์ชันสำหรับอัปโหลดเอกสาร (PDF, JPG, PNG)
     */
    function uploadDocument($file, $guest_no, $upload_directory, $db_field, $conn) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            write_log("❌ ข้อผิดพลาดในการอัปโหลดไฟล์ ($db_field): Code " . $file['error']);
            return false;
        }

        $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        if (!in_array($fileType, $allowed_types)) {
            write_log("❌ ประเภทไฟล์ไม่ได้รับอนุญาต: $fileType");
            return false;
        }

        // ✅ แยกเงื่อนไข PDF และ รูปภาพ
        if ($fileType === "pdf") {
            // 📂 ตั้งชื่อไฟล์เป็น PDF ตามปกติ
            $file_name = "{$db_field}_{$guest_no}.pdf";
            $target_file = "{$upload_directory}/{$file_name}";

            if (!move_uploaded_file($file['tmp_name'], $target_file)) {
                write_log("❌ การอัปโหลดไฟล์ PDF ล้มเหลว: $target_file");
                return false;
            }
            write_log("✅ อัปโหลดไฟล์ PDF สำเร็จ: $target_file");
        } 
        else {
            // 📂 ตั้งชื่อไฟล์เป็น .jpg เสมอ (สำหรับไฟล์รูปภาพ)
            $file_name = "{$db_field}_{$guest_no}.jpg";
            $target_file = "{$upload_directory}/{$file_name}";

            list($width, $height) = getimagesize($file['tmp_name']);

            // โหลดรูปภาพ
            switch ($fileType) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($file['tmp_name']);
                    break;
                case 'png':
                    $image = imagecreatefrompng($file['tmp_name']);
                    break;
                default:
                    write_log("❌ ไม่รองรับประเภทไฟล์รูปภาพ: $fileType");
                    return false;
            }

            if (!$image) {
                write_log("❌ ไม่สามารถโหลดไฟล์รูปภาพได้: " . $file['name']);
                return false;
            }

            // ✅ ตรวจสอบขนาดภาพและย่อขนาดถ้าเกิน 1024px
            $max_size = 1024;
            if ($width > $max_size || $height > $max_size) {
                $new_width = ($width > $height) ? $max_size : round(($width / $height) * $max_size);
                $new_height = ($height > $width) ? $max_size : round(($height / $width) * $max_size);

                // ย่อขนาด
                $new_image = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                // บันทึกเป็น JPG (ลดขนาด)
                imagejpeg($new_image, $target_file, 80);
                imagedestroy($new_image);
                write_log("✅ บีบอัดและลดขนาดรูปภาพสำเร็จ: $target_file");
            } else {
                // ถ้าภาพเล็กอยู่แล้ว → แปลงเป็น JPG โดยไม่ลดขนาด
                imagejpeg($image, $target_file, 80);
                write_log("✅ บันทึกไฟล์รูปภาพโดยไม่ลดขนาด: $target_file");
            }

            imagedestroy($image);
        }

        // ✅ อัปเดตฐานข้อมูล
        $sql = "UPDATE resume_job SET $db_field = ? WHERE guest_no = ?";
        $params = array($file_name, $guest_no);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            $errors = sqlsrv_errors();
            write_log("❌ อัปเดตฐานข้อมูลล้มเหลว: guest_no = $guest_no, field = $db_field, file = $file_name");
            if ($errors) {
                foreach ($errors as $error) {
                    write_log("  - SQLSTATE: " . $error['SQLSTATE']);
                    write_log("  - Code: " . $error['code']);
                    write_log("  - Message: " . $error['message']);
                }
            }
            return false;
        } else {
            write_log("✅ อัปเดตฐานข้อมูลสำเร็จ: guest_no = $guest_no, field = $db_field, file = $file_name");
        }

        return true;
    }

    
    // ตรวจสอบว่ามีไฟล์ที่ต้องการอัปโหลดหรือไม่

    // อัปโหลดไฟล์และบันทึกชื่อไฟล์ที่สำเร็จ
    $upload_fields = [
        'img_upload' => ['function' => 'uploadImage', 'db_field' => 'picture_upload'],
        'idcard_upload' => ['function' => 'uploadDocument', 'db_field' => 'idcard_upload'],
        'address_upload' => ['function' => 'uploadDocument', 'db_field' => 'address_upload'],
        'transcript_upload' => ['function' => 'uploadDocument', 'db_field' => 'transcript_upload'],
        'malitary_upload' => ['function' => 'uploadDocument', 'db_field' => 'malitary_upload'],
        'driver_upload' => ['function' => 'uploadDocument', 'db_field' => 'driver_upload'],
        'certify_upload' => ['function' => 'uploadDocument', 'db_field' => 'certify_upload'],
        'portfolio_upload' => ['function' => 'uploadDocument', 'db_field' => 'portfolio_upload']
    ];
    
    foreach ($upload_fields as $field_name => $options) {
        if (isset($_FILES[$field_name])) {
            $upload_function = $options['function'];
            $db_field = $options['db_field'];
    
            if ($upload_function($_FILES[$field_name], $guest_no, $upload_directory, $db_field, $conn)) {
                $uploaded_files[] = "{$db_field}_{$guest_no}" . ($upload_function == 'uploadImage' ? ".jpg" : "");
            }
        } else {
            write_log("❌ ไม่ได้ทำการแนบไฟล์: $field_name");
        }
    }
    
    

    // ตรวจสอบสถานะการอัปโหลด
    if (!empty($uploaded_files)) {
        echo "อัปโหลดเรียบร้อย";
        
        // ✅ บันทึก Log พร้อมรายชื่อไฟล์ทั้งหมดที่อัปโหลดสำเร็จ
        $uploaded_files_str = implode(", ", $uploaded_files);
        write_log("✅ บันทึกลงฐานข้อมูลสำเร็จ: " . $uploaded_files_str);
    } else {
        echo "เกิดข้อผิดพลาดในการอัปโหลด";
        write_log("❌ ไม่มีไฟล์ถูกอัปโหลด");
    }

    // เช็คเงื่อนไขตอนกดปุ่มยกเลิกการอัพโหลด  
    $must_have = ['picture_upload', 'idcard_upload', 'address_upload'];
    $uploaded_fields_only = array_map(function($f) {
        // แยกชื่อไฟล์เพื่อให้ได้เฉพาะชื่อฟิลด์ที่ต้องการ
        return implode('_', array_slice(explode('_', $f), 0, 2));
    }, $uploaded_files);
    
    $has_all_required = count(array_intersect($uploaded_fields_only, $must_have)) === count($must_have);

    if (
        $guest_no !== "" &&
        $branch !== "" &&
        $has_all_required &&
        connection_status() === CONNECTION_NORMAL
    ) {
        // include __DIR__ . '/include/email_send.php';  //เปิดตอนเทสเสร็จ
        // include __DIR__ . '/include/line_notify.php'; //เปิดตอนเทสเสร็จ
        // require_once '//192.168.15.100/Line_Notify/Ornjob/guest_send.php';
    } else {
        write_log("🚫 ข้ามการส่ง Email และ Line เนื่องจากอัปโหลดไม่ครบหรือถูกยกเลิก");
    }


?>





