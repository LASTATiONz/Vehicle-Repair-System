<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


    // require 'PHPMailer/src/PHPMailer.php';
    // require 'PHPMailer/src/SMTP.php';
    // require 'PHPMailer/src/Exception.php';

    require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
    require_once __DIR__ . '/../PHPMailer/src/Exception.php';

    if($guest_no != ""){

        $strSQL="select guest_no,job_name,salary,work_date,name,nickname,sex,birthday,age,idcard,status,nationality,religion,height,weight,phone,
                line_id,email,address,military,congenital_disease,disabled,branch_job,picture_upload,idcard_upload,address_upload,
                transcript_upload,malitary_upload,driver_upload,certify_upload,portfolio_upload
                from resume_job
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

        if($row['branch_job'] == "SRT1"){
            $mail_to = "hrsrt@ornategroup.com";
        }else if ($row['branch_job'] == "HDY1"){
            $mail_to = "hr_hdy@ornategroup.com";
        }else if($row['branch_job'] == "CHM1"){
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
            $mail->addAddress($mail_to); // ส่งเมลให้ HR ที่เกี่ยวข้อง
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
