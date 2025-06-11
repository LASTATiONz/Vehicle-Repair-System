<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB ORNATE SDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link href="styles.css" rel="stylesheet">
	<script src="js/script.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

	<link rel="icon" href="images/LOGO_ORNATE1.png" type="image/icon type">
 
</head>
<body style="font-size: 13px;">
<div class="m-0">
<?php
//include connect database
    include 'db_connect.php';
    //include 'header_hr.php';

    if (!isset($_SESSION['ses_username']) || empty($_SESSION['ses_username'])) {
        // แสดงข้อความแจ้งเตือน
        echo "
        <!DOCTYPE html>
        <html lang='th'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>กรุณา Login</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css' rel='stylesheet'>
            <link href='styles.css' rel='stylesheet'>
            <script>
                // ฟังก์ชันสำหรับการนับถอยหลัง
                let countdown = 10;
                function startCountdown() {
                    const countdownElement = document.getElementById('countdown');
                    const interval = setInterval(() => {
                        countdown--;
                        countdownElement.textContent = countdown;
                        if (countdown <= 0) {
                            clearInterval(interval);
                            window.location.href = 'index.php';
                        }
                    }, 1000);
                }
            </script>
        </head>
        <body onload='startCountdown()'>
            <div class='container mt-5'>
                <div class='alert alert-warning text-center' role='alert'>
                    <h4 class='alert-heading'>คุณยังไม่ได้ เข้าสู่ระบบ !!</h4>
                    <p>ระบบจะพาคุณกลับไปหน้า เข้าสู่ระบบ ใน <span id='countdown'>10</span> วินาที</p>
                    <hr>
                    <a href='index.php' class='btn btn-primary btn-sm'><i class='fa-solid fa-arrow-rotate-left fa-lg'></i> กลับไปหน้า เข้าสู่ระบบ</a>
                </div>
            </div>
        </body>
        </html>
        ";
        exit();
    }
?>
</div>
<div class="container mt-1 mb-2"> 
<!--Div Content-->
    <div class="section-header"><i class="fa-solid fa-folder-plus fa-lg txt_icon"></i><span class="txt_toppic"> เพิ่มตำแหน่งงานว่าง</span></div>
    <div class="section-body">
		<form  id="from_newjob" name ="from_newjob" action="add_new_job.php" method="post" class="needs-validation" novalidate>
		<div class="row mb-1">
                <div class="col-md-6 margin_bt">
                    <label for="post_jobname" class="form-label"><b>ชื่อตำแหน่งงาน</b> <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="post_jobname" name="post_jobname" placeholder="กรอก ชื่อตำแหน่งงาน" required>   
                    <div class="invalid-feedback">กรุณากรอก ชื่อตำแหน่งงาน</div>
                </div>
                <div class="col-md-2 margin_bt">
                    <label for="post_require" class="form-label"><b>จำนวนที่รับ</b> <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="post_require" name="post_require" placeholder="กรอก จำนวนที่รับ" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                    <div class="invalid-feedback">กรุณากรอก จำนวนที่รับ</div>
                </div>
                <div class="col-md-4 margin_bt">
                    <label for="post_salary" class="form-label"><b>เงินเดือน</b> <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="post_salary" name="post_salary" placeholder="กรอก เงินเดือน" required>
                    <div class="invalid-feedback">กรุณากรอก เงินเดือน</div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-2 margin_bt">
                    <label for="post_gender" class="form-label"><b>เพศ</b> <span style="color: red;">*</span></label>
                    <select class="form-select font_option" id="post_gender" name="post_gender" required>
				    	<option selected disabled value="">--เลือก--</option>
				    	<option value="ชาย">ชาย</option>
				    	<option value="หญิง">หญิง</option>
						<option value="ชาย/หญิง">ชาย/หญิง</option>
				  	</select>
                    <div class="invalid-feedback">กรุณาเลือก เพศ</div>
                </div>
                <div class="col-md-2 margin_bt">
                    <label for="post_age" class="form-label"><b>ช่วงอายุ</b> <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="post_age" name="post_age" placeholder="กรอก อายุ" required>
                    <div class="invalid-feedback">กรุณากรอก ช่วงอายุ</div>
                </div>
                <div class="col-md-3 margin_bt">
                    <label for="post_certificat1" class="form-label"><b>วุฒิการศึกษา</b> <span style="color: red;">*</span></label>
                    <select class="form-select font_option" id="post_certificat1" name="post_certificat1" required>
				    	<option selected disabled value="">--เลือก--</option>
						<option value="ไม่จำกัดวุฒิฯ">ไม่จำกัดวุฒิฯ</option>
						<option value="ม.3 ขึ้นไป">ม.3 ขึ้นไป</option>
						<option value="ม.6/ปวช ขึ้นไป">ม.6/ปวช ขึ้นไป</option>
						<option value="ปวส/อนุปริญญา ขึ้นไป">ปวส/อนุปริญญา ขึ้นไป</option>
						<option value="ปริญญาตรี ขึ้นไป">ปริญญาตรี ขึ้นไป</option>
				  	</select>
                    <div class="invalid-feedback">กรุณาเลือก วุฒิการศึกษา</div>
                </div>
                <div class="col-md-5 margin_bt">
                    <label for="post_age" class="form-label"><b>สาขาวิชา</b></label>
                    <input type="text" class="form-control" id="post_major" name="post_major" placeholder="กรอกสาขาวิชา เช่น สาขาบัญชี">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-4 margin_bt">
                    <label for="post_joblocation" class="form-label"><b>สถานที่ปฎิบัติงาน</b> <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="post_joblocation" name="post_joblocation" placeholder="กรอก สถานที่ปฎิบัติงาน" required>
				    <div class="invalid-feedback">กรุณากรอก สถานที่ปฎิบัติงาน</div>
                </div>
                <div class="col-md-4 margin_bt">
                    <label for="post_sub_branch" class="form-label"><b>บริษัท</b> <span style="color: red;">*</span></label>
                    <select class="form-select font_option" id="post_sub_branch" name="post_sub_branch" required>
						<option selected disabled value="">--เลือก--</option>
						<option value="ORNATE SDO">บริษัท ออร์เนท เอสดีโอ จำกัด</option>
						<?php
						if($_SESSION['ses_branch'] == "SRT"){ ?>
						<option value="SHIPSHAPE">บริษัท ชิพเชพ (ไทยแลนด์) จำกัด</option>
						<option value="ORNATE DRINK">บริษัท ออร์เนทดริ้งค์ จำกัด</option>
						<option value="ORNATE TRANDING">ห้างหุ้นส่วนจำกัด ออร์เนท เทรดดิ้ง</option>
						<?php }elseif ($_SESSION['ses_branch'] == "HDY"){ ?>
						<option value="TIPTOP">บริษัท ทิพทอพ (ไทยแลนด์) จำกัด</option> 
						<option value="KUBKEEPHODPHAI">บริษัท ขับขี่ปลอดภัย จำกัด</option> 
						<?php }elseif ($_SESSION['ses_branch'] == "CHM"){ ?>
						<option value="THINKER">บริษัท ธิงเคอร์ (ไทยแลนด์) จำกัด</option>
						<option value="TRONGPAI TRONGMA">บริษัท ตรงไป ตรงมา จำกัด</option>
						<option value="ORNATE LOGISTICS">ห้างหุ้นส่วนจำกัด ออร์เนท โลจิสทิคซ์</option>
						<?php } ?>
                    </select>
                    <div class="invalid-feedback">กรุณาเลือก บริษัท</div>
                </div>
                <div class="col-md-4 margin_bt">
                    <label for="branch" class="form-label"><b>สาขา</b></label>
                    <?php 
					if($_SESSION['ses_branch'] == "SRT"){
					 $branch = "สาขาสุราษฎร์ธานี (สำนักงานใหญ่)";
					}else if($_SESSION['ses_branch'] == "HDY"){
					 $branch = "สาขาหาดใหญ่ ";
					}else if($_SESSION['ses_branch'] == "CHM"){
					 $branch = "สาขาเชียงใหม่ ";
					}else {}
				    ?>
                    <input type="text" class="form-control" id="branch" name="branch" value="<?php echo $branch;?>" placeholder="กรอกสาขา" readonly>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <hr class="style-one mt-2"> 
            <div class="row mb-1 style_row">
                <div class="col-md-12 margin_bt">
                    <label for="post_certificat2" class="form-label"><b>คุณสมบัติ (1)</b> <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="post_certificat2" name="post_certificat2" placeholder="กรอกคุณสมบัติ (1)" required>
                    <div class="invalid-feedback">กรุณากรอก คุณสมบัติ (1)</div>
                </div>
                <div class="col-md-12 margin_bt">
                    <label for="post_certificat3" class="form-label"><b>คุณสมบัติ (2)</b></label>
                    <input type="text" class="form-control" id="post_certificat3" name="post_certificat3" placeholder="กรอกคุณสมบัติ (2)">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-12 margin_bt">
                    <label for="post_certificat4" class="form-label"><b>คุณสมบัติ (3)</b></label>
                    <input type="text" class="form-control" id="post_certificat4" name="post_certificat4" placeholder="กรอกคุณสมบัติ (3)">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-12 margin_bt">
                    <label for="post_certificat5" class="form-label"><b>คุณสมบัติ (4)</b></label>
                    <input type="text" class="form-control" id="post_certificat5" name="post_certificat5" placeholder="กรอกคุณสมบัติ (4)">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-12 margin_bt">
                    <label for="post_certificat6" class="form-label"><b>คุณสมบัติ (5)</b></label>
                    <input type="text" class="form-control" id="post_certificat6" name="post_certificat6" placeholder="กรอกคุณสมบัติ (5)">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <hr class="style-one mt-2"> 
            <div class="row mb-1 style_row">
                <div class="col-md-12 margin_bt">
                    <label for="post_jobattribute1" class="form-label"><b>ลักษณะงาน (1)</b> <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="post_jobattribute1" name="post_jobattribute1" placeholder="กรอกลักษณะงาน (1)" required>
                    <div class="invalid-feedback">กรุณากรอก ลักษณะงาน (1)</div>
                </div>
                <div class="col-md-12 margin_bt">
                    <label for="post_jobattribute2" class="form-label"><b>ลักษณะงาน (2)</b></label>
                    <input type="text" class="form-control" id="post_jobattribute2" name="post_jobattribute2" placeholder="กรอกลักษณะงาน (2)">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-12 margin_bt">
                    <label for="post_jobattribute3" class="form-label"><b>ลักษณะงาน (3)</b></label>
                    <input type="text" class="form-control" id="post_jobattribute3" name="post_jobattribute3" placeholder="กรอกลักษณะงาน (3)">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-12 margin_bt">
                    <label for="post_jobattribute4" class="form-label"><b>ลักษณะงาน (4)</b></label>
                    <input type="text" class="form-control" id="post_jobattribute4" name="post_jobattribute4" placeholder="กรอกลักษณะงาน (4)">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-12 margin_bt">
                    <label for="post_jobattribute5" class="form-label"><b>ลักษณะงาน (5)</b></label>
                    <input type="text" class="form-control" id="post_jobattribute5" name="post_jobattribute5" placeholder="กรอกลักษณะงาน (5)">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <hr class="style-one mt-2">
			<div class="row mb-1 style_row" id="benefits-container">
			<?php
				$options = [
					"ประกันสังคม",
					"ปรับเงินเดือนประจำปี",
					"เสื้อฟอร์มบริษัทฯ",
					"วันหยุดพักผ่อนประจำปี",
					"วันหยุดตามประเพณี",
					"โบนัสประจำปี",
					"งานเลี้ยงสังสรรค์ประจำปี",
					"สวัสดิการอื่นๆตามที่ทางบริษัทฯ กำหนดไว้"
				];

				for ($i = 1; $i <= 8; $i++) {
					$isRequired = $i === 1 ? 'required' : ''; // กำหนดช่องที่บังคับกรอก
					$label = "<b>สวัสดิการ ({$i})</b>" . ($i === 1 ? ' <span style="color: red;">*</span>' : ''); // เพิ่มดอกจันสีแดงเฉพาะช่องแรก
					$invalidFeedback = $i === 1 ? "<div class='invalid-feedback'>กรุณากรอก สวัสดิการ ({$i})</div>" : ''; // ข้อความแจ้งเตือนสำหรับช่องแรกเท่านั้น

					echo "
					<div class='col-md-6 margin_bt'>
						<label for='post_benefits{$i}' class='form-label'>{$label}</label>
						<select class='form-select font_option benefits-select' id='post_benefits{$i}' name='post_benefits{$i}' {$isRequired}>
							<option selected disabled value=''>- เลือกสวัสดิการ ({$i}) -</option>";

					// เพิ่มตัวเลือกจาก $options
					foreach ($options as $option) {
						echo "<option value='{$option}'>{$option}</option>";
					}

					echo "</select>
						{$invalidFeedback} 
					</div>";
				}
				?>
				<div class="col-md-12 margin_bt">
                    <label for="post_benefits9" class="form-label"><b>สวัสดิการ (9)</b></label>
                    <input type="text" class="form-control" id="post_benefits9" name="post_benefits9" placeholder="กรอกสวัสดิการเพิ่มเติม (ถ้ามี)">
                    <div class="invalid-feedback"></div>
                </div>
			</div>
            <hr class="style-one mt-2">
            <div class="row mb-1 style_row">
                <div class="col-md-4 margin_bt">
                    <label for="post_phone" class="form-label"><b>เบอร์โทรศัพท์ (HR)</b> <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="post_phone" name="post_phone" placeholder="กรอกเบอร์โทรศัพท์ (HR)" required>
                    <div class="invalid-feedback">กรุณากรอก เบอร์โทรศัพท์ (HR)</div>
                </div>
                <div class="col-md-4 margin_bt">
                    <label for="post_email" class="form-label"><b>E-mail (HR)</b> <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="post_email" name="post_email" placeholder="กรอก E-mail (HR)" required>
                    <div class="invalid-feedback">กรุณากรอก E-mail (HR)</div>
                </div>
                <div class="col-md-4 margin_bt">
                    <label for="post_job_question" class="form-label"><b>ตอบคำถามเพิ่มเติม</b> <span style="color: red;">*</span></label>
                    <select class="form-select font_option" id="post_job_question" name="post_job_question" required>
                        <option selected disabled value="">--เลือก--</option>
						<option value="Y">ต้องตอบคำถาม</option>
						<option value="N">ไม่ต้องตอบคำถาม</option>
                    </select>
                    <div class="invalid-feedback">กรุณาเลือก การตอบคำถามเพิ่มเติม</div>
                </div>
            </div>
            <hr class="style-one mt-2">
            <div class="row mt-1 style_row">
                <div class="col-md-12 style_h" align="center">
                    <button class="btn btn-primary btn-sm" type="submit" name="button" id="button"><i class="fa-solid fa-folder-plus fa-lg"></i> เพิ่มตำแหน่งงานว่าง</button>
                </div>
            </div>


		</form>  
    </div>       
<!--------------------------->
</div>
<!--------แจ้งเตือนเมื่อกด เพิ่มข้อมูลตำแหน่งงาน----------->
<?php
if (isset($_POST['button'])) { // ตรวจสอบว่าปุ่ม submit ถูกกด
	
	session_start();
	include("db_connect.php");  // เชื่อมต่อฐานข้อมูล
		
	// Query สำหรับดึงข้อมูล job_next_number
		$strSQL = "SELECT * FROM job_next_number WHERE branch = ?";
		$params = array($_SESSION['ses_branch']);
		$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$Query = sqlsrv_query($conn, $strSQL, $params, $options);
		$row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC);
	
		// คำนวณ job_number
		$max_number = $row['job_next_number'];
		$next_number = $max_number + 1;
		$job_number = substr("0000" . $next_number, -4);
		$job_no = $_SESSION['ses_branch'] . $job_number;
	
		// ตรวจสอบว่ามี job_no ซ้ำหรือไม่
		$strSQL1 = "SELECT * FROM jobs_require WHERE job_no = ?";
		$params1= array($job_no);
		$options1 = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$Query1 = sqlsrv_query($conn, $strSQL1, $params1, $options1);
		$numRows1 = sqlsrv_num_rows($Query1);
	
		$modalMessage = '';
		if ($numRows1 > 0) {
			$modalMessage = "มีตำแหน่งงาน $job_no อยู่ในระบบแล้ว !!";
			$msg = "<span><i class='fa-solid fa-circle-xmark fa-lg text-danger'></i> $modalMessage</span>";
		} else {
			// กำหนด branch_name ตามเงื่อนไข
			$branch_mapping = [
				"SRT" => [
					"ORNATE SDO" => "บริษัท ออร์เนท เอสดีโอ จำกัด",
					"SHIPSHAPE" => "บริษัท ชิพเชพ (ไทยแลนด์) จำกัด",
					"ORNATE DRINK" => "บริษัท ออร์เนทดริ้งค์ จำกัด",
					"ORNATE TRANDING" => "ห้างหุ้นส่วนจำกัด ออร์เนท เทรดดิ้ง",
				],
				"HDY" => [
					"ORNATE SDO" => "บริษัท ออร์เนท เอสดีโอ จำกัด",
					"TIPTOP" => "บริษัท ทิพทอพ (ไทยแลนด์) จำกัด",
					"KUBKEEPHODPHAI" => "บริษัท ขับขี่ปลอดภัย จำกัด",
				],
				"CHM" => [
					"ORNATE SDO" => "บริษัท ออร์เนท เอสดีโอ จำกัด",
					"THINKER" => "บริษัท ธิงเคอร์ (ไทยแลนด์) จำกัด",
					"TRONGPAI TRONGMA" => "บริษัท ตรงไป ตรงมา จำกัด จำกัด",
					"ORNATE LOGISTICS" => "ห้างหุ้นส่วนจำกัด ออร์เนท โลจิสทิคซ์",
				],
			];
			$branch_name = $branch_mapping[$_SESSION['ses_branch']][$_POST['post_sub_branch']] ?? "";
            $job_status	= 'Y';
            $count_views = 0;
            date_default_timezone_set('Asia/Bangkok');
            $annoudate = date('Y-m-d H:i:s');

	
			// Insert ข้อมูล job

			$msg =  $job_no."-".$_SESSION['ses_branch']."-".$branch_name."-".$_POST['post_jobname']."-".$_POST['post_require']."-".
            $_POST['post_jobattribute1']."-".$_POST['post_jobattribute2']."-".$_POST['post_jobattribute3']."-".$_POST['post_jobattribute4']."-".$_POST['post_jobattribute5']."-".
            $_POST['post_certificat1']."-".$_POST['post_certificat2']."-".$_POST['post_certificat3']."-".$_POST['post_certificat4']."-".$_POST['post_certificat5']."-".$_POST['post_certificat6']."-".
            $_POST['post_benefits1']."-".$_POST['post_benefits2']."-".$_POST['post_benefits3']."-".$_POST['post_benefits4']."-".$_POST['post_benefits5']."-".$_POST['post_benefits6']."-".$_POST['post_benefits7']."-".$_POST['post_benefits8']."-".$_POST['post_benefits9']."-".
            $_POST['post_phone']."-". $_POST['post_email']."-". $_POST['post_job_question']."-".$_POST['post_major']."-".$_POST['post_sub_branch']."-".$job_status."-".$count_views."-".$annoudate."-".
            $_POST['post_salary']."-".$_POST['post_joblocation']."-".$_POST['post_gender']."-".$_POST['post_age'];
			

			// Insert ข้อมูล job
			$sql2 = "INSERT INTO jobs_require (job_no,branch,branch_name,position,require_no,salary,job_location,gender,age,job_attribute1,job_attribute2,job_attribute3,job_attribute4,job_attribute5,
                                job_certificate1,job_certificate2,job_certificate3,job_certificate4,job_certificate5,job_certificate6,benefits1,benefits2,benefits3,benefits4,benefits5,benefits6,benefits7,benefits8,benefits9,
                                hrcontact,hrcontact2,job_question,major,sub_branch,job_status,count_views,annoudate) 
                        VALUES (?, ?, ? ,? ,? ,? ,? ,? ,? ,?, ?, ? ,? ,? ,?, ?, ? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)";
			$params2 = [$job_no, $_SESSION['ses_branch'] ,$branch_name ,$_POST['post_jobname'] ,$_POST['post_require'] ,$_POST['post_salary'] ,$_POST['post_joblocation'] ,$_POST['post_gender'] ,$_POST['post_age'] ,
                        $_POST['post_jobattribute1'] ,$_POST['post_jobattribute2'] ,$_POST['post_jobattribute3'] ,$_POST['post_jobattribute4'] ,$_POST['post_jobattribute5'] ,
                        $_POST['post_certificat1'] ,$_POST['post_certificat2'] ,$_POST['post_certificat3'] ,$_POST['post_certificat4'] ,$_POST['post_certificat5'] ,$_POST['post_certificat6'] ,
                        $_POST['post_benefits1'] ,$_POST['post_benefits2'] ,$_POST['post_benefits3'] ,$_POST['post_benefits4'] ,$_POST['post_benefits5'] ,$_POST['post_benefits6'] ,$_POST['post_benefits7'] ,$_POST['post_benefits8'] ,$_POST['post_benefits9'],
                        $_POST['post_phone'] ,$_POST['post_email'] ,$_POST['post_job_question'] ,$_POST['post_major'] ,$_POST['post_sub_branch'] ,$job_status ,$count_views,$annoudate];

			$query2 = sqlsrv_query($conn, $sql2, $params2);

			$sql3 = "UPDATE job_next_number SET job_next_number = ? WHERE branch = ?";
        	$params3 = [$next_number, $_SESSION['ses_branch']];
        	$query3 = sqlsrv_query($conn, $sql3, $params3);

			if ($query2) {
				$modalMessage = "เพิ่มตำแหน่งงาน $job_no เรียบร้อยแล้ว !!";
				$msg = "<span><i class='fa-solid fa-circle-check fa-lg text-success'></i> $modalMessage</span>";
			} else {
				$modalMessage = "เกิดข้อผิดพลาดในการเพิ่มข้อมูลตำแหน่งงานว่าง !!";
				$msg = "<span><i class='fa-solid fa-circle-xmark fa-lg text-danger'></i> $modalMessage</span>";
			}

		}
		sqlsrv_close($conn);
	
	?>
	
	<!-- Modal -->
	<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-warning">
						<i class="fa fa-exclamation-circle" aria-hidden="true"></i> แจ้งเตือน !!
					</h5>
					<button type="button" class="btn-close text-danger" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<?php echo $msg; ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-sm" onclick="redirectToPostJobs()">ตกลง</button>
				</div>
			</div>
		</div>
	</div>
<?php } ?>	


	<script>
	function redirectToPostJobs() {
		window.opener.location.href = 'post_ornjobs.php'; // กลับไปหน้า post_ornjobs.php
		window.close(); // ปิดหน้าต่างป๊อปอัป
	}
	
	// เปิด Modal โดยอัตโนมัติหลังโหลดหน้า
	document.addEventListener('DOMContentLoaded', () => {
		const modal = new bootstrap.Modal(document.getElementById('successModal'));
		modal.show();
	});
	</script>




</body>


</html>