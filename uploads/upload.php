<?php
session_start();
include 'db_connect.php'; // Include your DB connection
    
    // รับค่า branch จากฟอร์ม
    $branch = isset($_POST['branch']) ? trim($_POST['branch']) : "";
    
    // ตรวจสอบค่าของ guest_no ว่ามีอยู่ใน session หรือไม่
    if (isset($_SESSION['guest_no']) && !empty($_SESSION['guest_no'])) {
        $guest_no = $_SESSION['guest_no'];
    } else {
        $guest_no = "";
        //$guest_no = "SRTGUEST00226";
    }
    
    if ($guest_no == "") { // ถ้าไม่มีรหัส guest_no จะไม่ทำการอัปโหลดรูป
        die("ไม่พบ guest_no");
    } else { // ถ้ามีรหัส guest_no ให้ทำการอัปโหลดรูป
        // โฟลเดอร์ที่เก็บรูปภาพ
        $upload_directory = "File_Upload_" . $branch . "/" . $guest_no;
    
        // ตรวจสอบว่ามีโฟลเดอร์หรือไม่ หากไม่มีให้สร้าง
        if (!is_dir($upload_directory)) {
            if (mkdir($upload_directory, 0777, true)) {
                echo "สร้างโฟลเดอร์สำเร็จ<br>";
            } else {
                die("ไม่สามารถสร้างโฟลเดอร์ได้");
            }
        }
    
        $image_uploaded = false;
        $idcard_uploaded = false;
        $address_uploaded = false;
        $transcript_uploaded = false;
        $malitary_uploaded = false;
        $driver_uploaded = false;
        $certify_uploaded = false;
        //$vaccinerecord_uploaded = false;
        $portfolio_uploaded = false;
    
        // อัปโหลดรูปถ่าย img_upload
        if (isset($_FILES['img_upload']) && $_FILES['img_upload']['error'] == 0) {
            $imageFileType = strtolower(pathinfo($_FILES["img_upload"]["name"], PATHINFO_EXTENSION));
            $allowed_image_types = ['jpg', 'jpeg', 'png'];
    
            if (in_array($imageFileType, $allowed_image_types)) {
                $image_name = "img_" . $guest_no . "." . $imageFileType;
                $target_file = $upload_directory . "/" . $image_name;
    
                // โหลดและย่อขนาดรูปภาพ
                $image = imagecreatefromstring(file_get_contents($_FILES['img_upload']['tmp_name']));
                list($width, $height) = getimagesize($_FILES['img_upload']['tmp_name']);
                $max_size = 1024;
    
                $new_width = $width > $height ? $max_size : ($width / $height) * $max_size;
                $new_height = $height > $width ? $max_size : ($height / $width) * $max_size;
    
                $new_image = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
                switch ($imageFileType) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($new_image, $target_file);
                        break;
                    case 'png':
                        imagepng($new_image, $target_file);
                        break;
                }
    
                imagedestroy($image);
                imagedestroy($new_image);
                $image_uploaded = true;
    
                // อัพเดทชื่อไฟล์ในฐานข้อมูล
                $sql = "UPDATE resume_job SET picture_upload = ? WHERE guest_no = ?";
                $params = array($image_name, $guest_no);
                sqlsrv_query($conn, $sql, $params);
            } else {
                //echo "อนุญาตเฉพาะไฟล์รูปแบบ JPG, JPEG, PNG เท่านั้นสำหรับรูปภาพ";
            }
        }
    
        // อัปโหลดสำเนาบัตรประชาชน idcard_upload
        if (isset($_FILES['idcard_upload']) && $_FILES['idcard_upload']['error'] == 0) {
            $idcardFileType = strtolower(pathinfo($_FILES["idcard_upload"]["name"], PATHINFO_EXTENSION));
            $allowed_idcard_types = ['jpg', 'jpeg', 'png', 'pdf'];
    
            if (in_array($idcardFileType, $allowed_idcard_types)) {
                $idcard_name = "idcard_" . $guest_no . "." . $idcardFileType;
                $target_file = $upload_directory . "/" . $idcard_name;
    
                if ($idcardFileType != 'pdf') {
                    // โหลดและย่อขนาดรูปภาพ (เฉพาะ jpg, jpeg, png)
                    $idcard = imagecreatefromstring(file_get_contents($_FILES['idcard_upload']['tmp_name']));
                    list($width, $height) = getimagesize($_FILES['idcard_upload']['tmp_name']);
                    $max_size = 1024;
    
                    $new_width = $width > $height ? $max_size : ($width / $height) * $max_size;
                    $new_height = $height > $width ? $max_size : ($height / $width) * $max_size;
    
                    $new_idcard = imagecreatetruecolor($new_width, $new_height);
                    imagecopyresampled($new_idcard, $idcard, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
                    switch ($idcardFileType) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($new_idcard, $target_file);
                            break;
                        case 'png':
                            imagepng($new_idcard, $target_file);
                            break;
                    }
    
                    imagedestroy($idcard);
                    imagedestroy($new_idcard);
                } else {
                    // สำหรับไฟล์ PDF ให้ย้ายไฟล์โดยตรง
                    move_uploaded_file($_FILES['idcard_upload']['tmp_name'], $target_file);
                }
    
                $idcard_uploaded = true;
    
                // อัพเดทชื่อไฟล์ในฐานข้อมูล
                $sql = "UPDATE resume_job SET idcard_upload = ? WHERE guest_no = ?";
                $params = array($idcard_name, $guest_no);
                sqlsrv_query($conn, $sql, $params);
            } else {
                //echo "อนุญาตเฉพาะไฟล์รูปแบบ JPG, JPEG, PNG, PDF เท่านั้นสำหรับบัตรประชาชน";
            }
        }

        // อัปโหลดสำเนาทะเบียนบ้าน address_upload
        if (isset($_FILES['address_upload']) && $_FILES['address_upload']['error'] == 0) {
            $addressFileType = strtolower(pathinfo($_FILES["address_upload"]["name"], PATHINFO_EXTENSION));
            $allowed_address_types = ['jpg', 'jpeg', 'png', 'pdf'];
    
            if (in_array($addressFileType, $allowed_address_types)) {
                $address_name = "address_" . $guest_no . "." . $addressFileType;
                $target_file = $upload_directory . "/" . $address_name;
    
                if ($addressFileType != 'pdf') {
                    // โหลดและย่อขนาดรูปภาพ (เฉพาะ jpg, jpeg, png)
                    $address = imagecreatefromstring(file_get_contents($_FILES['address_upload']['tmp_name']));
                    list($width, $height) = getimagesize($_FILES['address_upload']['tmp_name']);
                    $max_size = 1024;
    
                    $new_width = $width > $height ? $max_size : ($width / $height) * $max_size;
                    $new_height = $height > $width ? $max_size : ($height / $width) * $max_size;
    
                    $new_address = imagecreatetruecolor($new_width, $new_height);
                    imagecopyresampled($new_address, $address, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
                    switch ($addressFileType) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($new_address, $target_file);
                            break;
                        case 'png':
                            imagepng($new_address, $target_file);
                            break;
                    }
    
                    imagedestroy($address);
                    imagedestroy($new_address);
                } else {
                    // สำหรับไฟล์ PDF ให้ย้ายไฟล์โดยตรง
                    move_uploaded_file($_FILES['address_upload']['tmp_name'], $target_file);
                }
    
                $address_uploaded = true;
    
                // อัพเดทชื่อไฟล์ในฐานข้อมูล
                $sql = "UPDATE resume_job SET address_upload = ? WHERE guest_no = ?";
                $params = array($address_name, $guest_no);
                sqlsrv_query($conn, $sql, $params);
            } else {
                //echo "อนุญาตเฉพาะไฟล์รูปแบบ JPG, JPEG, PNG, PDF เท่านั้นสำหรับบัตรประชาชน";
            }
        }

        // อัปโหลดหลักฐานการศึกษา transcript_upload
        if (isset($_FILES['transcript_upload']) && $_FILES['transcript_upload']['error'] == 0) {
            $transcriptFileType = strtolower(pathinfo($_FILES["transcript_upload"]["name"], PATHINFO_EXTENSION));
            $allowed_transcript_types = ['jpg', 'jpeg', 'png', 'pdf'];
    
            if (in_array($transcriptFileType, $allowed_transcript_types)) {
                $transcript_name = "transcript_" . $guest_no . "." . $transcriptFileType;
                $target_file = $upload_directory . "/" . $transcript_name;
    
                if ($transcriptFileType != 'pdf') {
                    // โหลดและย่อขนาดรูปภาพ (เฉพาะ jpg, jpeg, png)
                    $transcript = imagecreatefromstring(file_get_contents($_FILES['transcript_upload']['tmp_name']));
                    list($width, $height) = getimagesize($_FILES['transcript_upload']['tmp_name']);
                    $max_size = 1024;
    
                    $new_width = $width > $height ? $max_size : ($width / $height) * $max_size;
                    $new_height = $height > $width ? $max_size : ($height / $width) * $max_size;
    
                    $new_transcript = imagecreatetruecolor($new_width, $new_height);
                    imagecopyresampled($new_transcript, $transcript, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
                    switch ($transcriptFileType) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($new_transcript, $target_file);
                            break;
                        case 'png':
                            imagepng($new_transcript, $target_file);
                            break;
                    }
    
                    imagedestroy($transcript);
                    imagedestroy($new_transcript);
                } else {
                    // สำหรับไฟล์ PDF ให้ย้ายไฟล์โดยตรง
                    move_uploaded_file($_FILES['transcript_upload']['tmp_name'], $target_file);
                }
    
                $transcript_uploaded = true;
    
                // อัพเดทชื่อไฟล์ในฐานข้อมูล
                $sql = "UPDATE resume_job SET transcript_upload = ? WHERE guest_no = ?";
                $params = array($transcript_name, $guest_no);
                sqlsrv_query($conn, $sql, $params);
            } else {
                //echo "อนุญาตเฉพาะไฟล์รูปแบบ JPG, JPEG, PNG, PDF เท่านั้นสำหรับบัตรประชาชน";
            }
        }

        // อัปโหลดหลักฐานทางทหาร malitary_upload
        if (isset($_FILES['malitary_upload']) && $_FILES['malitary_upload']['error'] == 0) {
            $malitaryFileType = strtolower(pathinfo($_FILES["malitary_upload"]["name"], PATHINFO_EXTENSION));
            $allowed_malitary_types = ['jpg', 'jpeg', 'png', 'pdf'];
    
            if (in_array($malitaryFileType, $allowed_malitary_types)) {
                $malitary_name = "malitary_" . $guest_no . "." . $malitaryFileType;
                $target_file = $upload_directory . "/" . $malitary_name;
    
                if ($malitaryFileType != 'pdf') {
                    // โหลดและย่อขนาดรูปภาพ (เฉพาะ jpg, jpeg, png)
                    $malitary = imagecreatefromstring(file_get_contents($_FILES['malitary_upload']['tmp_name']));
                    list($width, $height) = getimagesize($_FILES['malitary_upload']['tmp_name']);
                    $max_size = 1024;
    
                    $new_width = $width > $height ? $max_size : ($width / $height) * $max_size;
                    $new_height = $height > $width ? $max_size : ($height / $width) * $max_size;
    
                    $new_malitary = imagecreatetruecolor($new_width, $new_height);
                    imagecopyresampled($new_malitary, $malitary, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
                    switch ($malitaryFileType) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($new_malitary, $target_file);
                            break;
                        case 'png':
                            imagepng($new_malitary, $target_file);
                            break;
                    }
    
                    imagedestroy($malitary);
                    imagedestroy($new_malitary);
                } else {
                    // สำหรับไฟล์ PDF ให้ย้ายไฟล์โดยตรง
                    move_uploaded_file($_FILES['malitary_upload']['tmp_name'], $target_file);
                }
    
                $malitary_uploaded = true;
    
                // อัพเดทชื่อไฟล์ในฐานข้อมูล
                $sql = "UPDATE resume_job SET malitary_upload = ? WHERE guest_no = ?";
                $params = array($malitary_name, $guest_no);
                sqlsrv_query($conn, $sql, $params);
            } else {
                //echo "อนุญาตเฉพาะไฟล์รูปแบบ JPG, JPEG, PNG, PDF เท่านั้นสำหรับบัตรประชาชน";
            }
        }

        // อัปโหลดใบขับขี่ driver_upload
        if (isset($_FILES['driver_upload']) && $_FILES['driver_upload']['error'] == 0) {
            $driverFileType = strtolower(pathinfo($_FILES["driver_upload"]["name"], PATHINFO_EXTENSION));
            $allowed_driver_types = ['jpg', 'jpeg', 'png', 'pdf'];
    
            if (in_array($driverFileType, $allowed_driver_types)) {
                $driver_name = "driver_" . $guest_no . "." . $driverFileType;
                $target_file = $upload_directory . "/" . $driver_name;
    
                if ($driverFileType != 'pdf') {
                    // โหลดและย่อขนาดรูปภาพ (เฉพาะ jpg, jpeg, png)
                    $driver = imagecreatefromstring(file_get_contents($_FILES['driver_upload']['tmp_name']));
                    list($width, $height) = getimagesize($_FILES['driver_upload']['tmp_name']);
                    $max_size = 1024;
    
                    $new_width = $width > $height ? $max_size : ($width / $height) * $max_size;
                    $new_height = $height > $width ? $max_size : ($height / $width) * $max_size;
    
                    $new_driver = imagecreatetruecolor($new_width, $new_height);
                    imagecopyresampled($new_driver, $driver, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
                    switch ($driverFileType) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($new_driver, $target_file);
                            break;
                        case 'png':
                            imagepng($new_driver, $target_file);
                            break;
                    }
    
                    imagedestroy($driver);
                    imagedestroy($new_driver);
                } else {
                    // สำหรับไฟล์ PDF ให้ย้ายไฟล์โดยตรง
                    move_uploaded_file($_FILES['driver_upload']['tmp_name'], $target_file);
                }
    
                $driver_uploaded = true;
    
                // อัพเดทชื่อไฟล์ในฐานข้อมูล
                $sql = "UPDATE resume_job SET driver_upload = ? WHERE guest_no = ?";
                $params = array($driver_name, $guest_no);
                sqlsrv_query($conn, $sql, $params);
            } else {
                //echo "อนุญาตเฉพาะไฟล์รูปแบบ JPG, JPEG, PNG, PDF เท่านั้นสำหรับบัตรประชาชน";
            }
        }

        // อัปโหลดเอกสารการรับรองผ่านงาน certify_upload
        if (isset($_FILES['certify_upload']) && $_FILES['certify_upload']['error'] == 0) {
            $certifyFileType = strtolower(pathinfo($_FILES["certify_upload"]["name"], PATHINFO_EXTENSION));
            $allowed_certify_types = ['jpg', 'jpeg', 'png', 'pdf'];
    
            if (in_array($certifyFileType, $allowed_certify_types)) {
                $certify_name = "certify_" . $guest_no . "." . $certifyFileType;
                $target_file = $upload_directory . "/" . $certify_name;
    
                if ($certifyFileType != 'pdf') {
                    // โหลดและย่อขนาดรูปภาพ (เฉพาะ jpg, jpeg, png)
                    $certify = imagecreatefromstring(file_get_contents($_FILES['certify_upload']['tmp_name']));
                    list($width, $height) = getimagesize($_FILES['certify_upload']['tmp_name']);
                    $max_size = 1024;
    
                    $new_width = $width > $height ? $max_size : ($width / $height) * $max_size;
                    $new_height = $height > $width ? $max_size : ($height / $width) * $max_size;
    
                    $new_certify = imagecreatetruecolor($new_width, $new_height);
                    imagecopyresampled($new_certify, $certify, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
                    switch ($certifyFileType) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($new_certify, $target_file);
                            break;
                        case 'png':
                            imagepng($new_certify, $target_file);
                            break;
                    }
    
                    imagedestroy($certify);
                    imagedestroy($new_certify);
                } else {
                    // สำหรับไฟล์ PDF ให้ย้ายไฟล์โดยตรง
                    move_uploaded_file($_FILES['certify_upload']['tmp_name'], $target_file);
                }
    
                $certify_uploaded = true;
    
                // อัพเดทชื่อไฟล์ในฐานข้อมูล
                $sql = "UPDATE resume_job SET certify_upload = ? WHERE guest_no = ?";
                $params = array($certify_name, $guest_no);
                sqlsrv_query($conn, $sql, $params);
            } else {
                //echo "อนุญาตเฉพาะไฟล์รูปแบบ JPG, JPEG, PNG, PDF เท่านั้นสำหรับบัตรประชาชน";
            }
        }

        // อัปโหลดเอกสารฉีดวัคซีน โควิด 19 vaccinerecord_upload
       /* if (isset($_FILES['vaccinerecord_upload']) && $_FILES['vaccinerecord_upload']['error'] == 0) {
            $vaccinerecordFileType = strtolower(pathinfo($_FILES["vaccinerecord_upload"]["name"], PATHINFO_EXTENSION));
            $allowed_vaccinerecord_types = ['jpg', 'jpeg', 'png', 'pdf'];
    
            if (in_array($vaccinerecordFileType, $allowed_vaccinerecord_types)) {
                $vaccinerecord_name = "vaccinerecord_" . $guest_no . "." . $vaccinerecordFileType;
                $target_file = $upload_directory . "/" . $vaccinerecord_name;
    
                if ($vaccinerecordFileType != 'pdf') {
                    // โหลดและย่อขนาดรูปภาพ (เฉพาะ jpg, jpeg, png)
                    $vaccinerecord = imagecreatefromstring(file_get_contents($_FILES['vaccinerecord_upload']['tmp_name']));
                    list($width, $height) = getimagesize($_FILES['vaccinerecord_upload']['tmp_name']);
                    $max_size = 1024;
    
                    $new_width = $width > $height ? $max_size : ($width / $height) * $max_size;
                    $new_height = $height > $width ? $max_size : ($height / $width) * $max_size;
    
                    $new_vaccinerecord = imagecreatetruecolor($new_width, $new_height);
                    imagecopyresampled($new_vaccinerecord, $vaccinerecord, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
                    switch ($vaccinerecordFileType) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($new_vaccinerecord, $target_file);
                            break;
                        case 'png':
                            imagepng($new_vaccinerecord, $target_file);
                            break;
                    }
    
                    imagedestroy($vaccinerecord);
                    imagedestroy($new_vaccinerecord);
                } else {
                    // สำหรับไฟล์ PDF ให้ย้ายไฟล์โดยตรง
                    move_uploaded_file($_FILES['vaccinerecord_upload']['tmp_name'], $target_file);
                }
    
                $vaccinerecord_uploaded = true;
    
                // อัพเดทชื่อไฟล์ในฐานข้อมูล
                $sql = "UPDATE resume_job SET vaccinerecord_upload = ? WHERE guest_no = ?";
                $params = array($vaccinerecord_name, $guest_no);
                sqlsrv_query($conn, $sql, $params);
            } else {
                //echo "อนุญาตเฉพาะไฟล์รูปแบบ JPG, JPEG, PNG, PDF เท่านั้นสำหรับบัตรประชาชน";
            }
        }*/

        // อัปโหลด Portfolio หรือ Resume portfolio_upload
        if (isset($_FILES['portfolio_upload']) && $_FILES['portfolio_upload']['error'] == 0) {
            $portfolioFileType = strtolower(pathinfo($_FILES["portfolio_upload"]["name"], PATHINFO_EXTENSION));
            $allowed_portfolio_types = ['pdf'];
    
            if (in_array($portfolioFileType, $allowed_portfolio_types)) {
                $portfolio_name = "portfolio_" . $guest_no . "." . $portfolioFileType;
                $target_file = $upload_directory . "/" . $portfolio_name;
    
                if ($portfolioFileType != 'pdf') {
                } else {
                    // สำหรับไฟล์ PDF ให้ย้ายไฟล์โดยตรง
                    move_uploaded_file($_FILES['portfolio_upload']['tmp_name'], $target_file);
                }
    
                $portfolio_uploaded = true;
    
                // อัพเดทชื่อไฟล์ในฐานข้อมูล
                $sql = "UPDATE resume_job SET portfolio_upload = ? WHERE guest_no = ?";
                $params = array($portfolio_name, $guest_no);
                sqlsrv_query($conn, $sql, $params);
            } else {
                //echo "อนุญาตเฉพาะไฟล์รูปแบบ JPG, JPEG, PNG, PDF เท่านั้นสำหรับบัตรประชาชน";
            }
        }
    
        // ตรวจสอบว่าทั้งหมดไฟล์ถูกอัปโหลดสำเร็จหรือไม่
        if ($image_uploaded && $idcard_uploaded && $address_uploaded || $transcript_uploaded || $malitary_uploaded || $driver_uploaded 
            || $certify_uploaded || $vaccinerecord_uploaded || $portfolio_uploaded) {
            echo "อัปโหลดเรียบร้อย";
        } else {
            echo "อัปโหลดเรียบร้อย";
        }

    }   
?>

<!--ส่งเมลให้ HR-->
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if($guest_no != ""){
	$strSQL="select guest_no,job_name,salary,work_date,name,nickname,sex,birthday,age,idcard,status,nationality,religion,height,weight,phone,
            line_id,email,address,military,congenital_disease,disabled,branch_job,picture_upload,idcard_upload,address_upload,
            transcript_upload,malitary_upload,driver_upload,certify_upload,portfolio_upload
            from PE..resume_job
            where guest_no = '".$guest_no."'";
			
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$Query = sqlsrv_query($conn,$strSQL, $params, $options);
	$numRows = sqlsrv_num_rows( $Query );
    $row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC);

    $work_date = DateTime::createFromFormat('Y-m-d', $row['work_date']->format('Y-m-d'));
    $birthday = DateTime::createFromFormat('Y-m-d', $row['birthday']->format('Y-m-d'));
    
    if ($work_date) {
        $year_w = $work_date->format('Y') + 543; // เพิ่ม 543 เพื่อแปลงเป็นปี พ.ศ.
        $month_w = $work_date->format('m');
        $day_w = $work_date->format('d');
        $work_dates = $day_w . '/' . $month_w . '/' . $year_w;
    } else {
        $work_dates = ''; // กรณีที่ `work_date` ไม่มีข้อมูลหรือไม่สามารถแปลงได้
    }

    if ($birthday) {
        $year_b = $birthday->format('Y') + 543; // เพิ่ม 543 เพื่อแปลงเป็นปี พ.ศ.
        $month_b = $birthday->format('m');
        $day_b = $birthday->format('d');
        $birthdays = $day_b . '/' . $month_b . '/' . $year_b;
    } else {
        $birthdays = ''; // กรณีที่ `birthday` ไม่มีข้อมูลหรือไม่สามารถแปลงได้
    }

$mail = new PHPMailer(true);

if($row['branch_job'] == "SRT"){
    $mail_to = "hrsrt@ornategroup.com";
}else if ($row['branch_job'] == "HDY"){
    $mail_to = "hr_hdy@ornategroup.com";
}else if($row['branch_job'] == "CHM"){
    $mail_to = "hrchm1@ornategroup.com";
}else{
    $mail_to = "itsrt02@ornategroup.com";
}

try {
    $mail->isSMTP();
    $mail->Host = 'mail.ornategroup.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ornjob@ornategroup.com';
    $mail->Password = 'ornjob1234';
    //$mail->SMTPSecure = 'tls';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('ornjob@ornategroup.com', 'ฝ่ายสมัครงาน');
    $mail->addAddress($mail_to);
    // $mail->addAddress('itsrt02@ornategroup.com');
    //$mail->addAddress('asst_ithdy@ornategroup.com');

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'ข้อมูลการสมัครงาน '.$row['guest_no'];

    // ใช้สไตล์ Bootstrap อินไลน์
    $mail->Body = '
    <html>
    <head>
       <meta charset="UTF-8">
       <style>
            @import url("https://fonts.googleapis.com/css2?family=Sarabun&display=swap");
            body {
                background-color: rgba(225, 225, 225, 0.518);
	            font-family: "Sarabun", Cordia New, Arial, sans-serif;
                font-size: 20px;
	            color: #000033;
            }
            
            /* เพิ่ม CSS ที่จำเป็นจาก Bootstrap */
            .table {
                width: 100%;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 5px;
            }
            .table th, .table td {
                padding: 1rem;
                border-top: none;
            }
            .table thead th {
                border-bottom: none;
                background-color: #87CEEB;
            }
       </style>
    </head>
    <body>
        <table class="table">
            <thead>
                <th colspan="3"><h2>รายละเอียดการสมัครงาน</h2></th>
            </thead>
                <tbody>
                    <tr>
                        <td><b> ตำแหน่งที่สมัคร :</b> '.$row['job_name'].'</td>
                        <td><b> เงินเดือนที่ต้องการ :</b> '.$row['salary'].'</td>
                        <td><b> วันที่พร้อมเริ่มงาน :</b> '.$work_dates.'</td>
                    </tr>
                    <tr>
                        <td><b>ชื่อ-นามสกุล :</b> '.$row['name'].'</td>
                        <td><b>ชื่อเล่น :</b> '.$row['nickname'].'</td>
                        <td><b>เพศ :</b> '.$row['sex'].'</td>
                    </tr>
                    <tr>
                        <td><b>วันเดือนปีเกิด :</b> '.$birthdays.'</td>
                        <td><b>อายุ :</b> '.$row['age'].'</td>
                        <td><b>เลขบัตรประชาชน :</b> '.$row['idcard'].'</td>
                    </tr>
                    <tr>
                        <td><b>สถานภาพ :</b> '.$row['status'].'</td>
                        <td><b>สัญชาติ :</b> '.$row['nationality'].'</td>
                        <td><b>ศาสนา :</b> '.$row['religion'].'</td>
                    </tr>
                    <tr>
                        <td><b>ส่วนสูง :</b> '.$row['height'].' ซม.</td>
                        <td><b>น้ำหนัก :</b> '.$row['weight'].' กก.</td>
                        <td><b>โทรศัพท์มือถือ :</b> '.$row['phone'].'</td>
                    </tr>
                    <tr>
                        <td><b>Line ID :</b> '.$row['line_id'].'</td>
                        <td><b>E-mail :</b> '.$row['email'].'</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>ที่อยู่ปัจจุบัน :</b> '.$row['address'].'</td>
                    </tr>
                    <tr>
                        <td><b>สถานภาพทางทหาร :</b> '.$row['military'].'</td>
                        <td><b>โรคประจำตัว :</b> '.$row['congenital_disease'].'</td>
                        <td><b>ความพิการ :</b> '.$row['disabled'].'</td>
                    </tr>
                    
                </tbody>
        </table>
        <br>
        <div>
            <span><b><u>สามารถดูใบสมัครงานแบบเต็มได้ที่ </u></b></span>
            <li>Link เน็ตนอก >> https://ornwebapp.com/ORNJOB/guest_view_detail.php?guest_no='.$row['guest_no'].'</li>
            <li>Link เน็ตใน >> http://192.168.15.100:90/ORNJOB/guest_view_detail.php?guest_no='.$row['guest_no'].'</li>
        </div>
        
    </body>
    </html>
';

// การแนบไฟล์รูปหรือไฟล์ PDF
$img = 'File_Upload_' . $row['branch_job'] . '/' . $row['guest_no'] .'/'.$row['picture_upload']; // แนบไฟล์รูปภาพ
$idcard = 'File_Upload_' . $row['branch_job'] . '/' . $row['guest_no'] .'/'.$row['idcard_upload']; // แนบไฟล์รูปภาพ
$address = 'File_Upload_' . $row['branch_job'] . '/' . $row['guest_no'] .'/'.$row['address_upload']; // แนบไฟล์รูปภาพ
$transcript = 'File_Upload_' . $row['branch_job'] . '/' . $row['guest_no'] .'/'.$row['transcript_upload']; // แนบไฟล์รูปภาพ
$malitary = 'File_Upload_' . $row['branch_job'] . '/' . $row['guest_no'] .'/'.$row['malitary_upload']; // แนบไฟล์รูปภาพ
$driver = 'File_Upload_' . $row['branch_job'] . '/' . $row['guest_no'] .'/'.$row['driver_upload']; // แนบไฟล์รูปภาพ
$certify = 'File_Upload_' . $row['branch_job'] . '/' . $row['guest_no'] .'/'.$row['certify_upload']; // แนบไฟล์รูปภาพ
$portfolio = 'File_Upload_' . $row['branch_job'] . '/' . $row['guest_no'] .'/'.$row['portfolio_upload']; // แนบไฟล์รูปภาพ

if (!empty($img) && is_file($img) && file_exists($img)) {
    $mail->addAttachment($img); // แนบไฟล์ img 
}
if (!empty($idcard) && is_file($idcard) && file_exists($idcard)) {
    $mail->addAttachment($idcard); // แนบไฟล์ idcard
}
if (!empty($address) && is_file($address) && file_exists($address)) {
    $mail->addAttachment($address); // แนบไฟล์ address 
}
if (!empty($transcript) && is_file($transcript) && file_exists($transcript)) {
    $mail->addAttachment($transcript); // แนบไฟล์ transcript ถ้ามี
}
if (!empty($malitary) && is_file($malitary) && file_exists($malitary)) {
    $mail->addAttachment($malitary); // แนบไฟล์ malitary ถ้ามี
}
if (!empty($driver) && is_file($driver) && file_exists($driver)) {
    $mail->addAttachment($driver); // แนบไฟล์ driver ถ้ามี
}
if (!empty($certify) && is_file($certify) && file_exists($certify)) {
    $mail->addAttachment($certify); // แนบไฟล์ certify ถ้ามี
}
if (!empty($portfolio) && is_file($portfolio) && file_exists($portfolio)) {
    $mail->addAttachment($portfolio); // แนบไฟล์ portfolio ถ้ามี
}


    $mail->send();
    //echo 'ส่งอีเมลเรียบร้อยแล้ว';
} catch (Exception $e) {
    //echo "ไม่สามารถส่งข้อความได้. ข้อผิดพลาดของ Mailer: {$mail->ErrorInfo}";
}
}else{}

?>

<?php
    require_once '//192.168.15.100/Line_Notify/Ornjob/guest_send.php';
?>