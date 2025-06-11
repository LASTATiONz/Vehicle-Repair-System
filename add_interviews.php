<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<!-- <head>
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

	<link rel="icon" href="images/LOGO_ORNATE1.png" type="image/icon type">

</head> -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB ORNATE SDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="images/LOGO_ORNATE1.png" type="image/icon type">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</head>

<body style="font-size: 13px;">
<div class="m-0">
<?php
//include connect database
include 'db_connect.php';
//include 'header.php';
//include 'modle_notify.php';

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

            
//	Query ข้อมูลจากเลขที่ผู้สมัคร 
$strSQL="select picture_upload,branch_job,guest_no,title,name,job_name,salary,age,phone,interviews,selected,format(interviews_date,'yyyy-MM-dd') as interviews_date,format(selected_date,'yyyy-MM-dd') as selected_date,note 
		from pe..resume_job  where guest_no = ? ";

$params = array($_GET['guest_no']);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$Query = sqlsrv_query($conn,$strSQL, $params, $options);
$numRows = sqlsrv_num_rows( $Query );
$row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC);
        
?>

</div>
<div class="container mt-1 mb-2"> 
<!--Div รายละเอียดตำแหน่งงานว่าง-->
<div class="section-header"><i class="fa-solid fa-floppy-disk fa-lg txt_icon"></i><span class="txt_toppic"> บันทึกผลการสัมภาษณ์</span></div>
    <div class="section-body">
        <form  id="from_save" action="add_interviews.php" method="post" class="needs-validation" novalidate>
            <div class="row mb-1">
                <div class="col-md-6 margin_bt">
                    <label for="guest_no" class="form-label"><b>เลขที่ผู้สมัคร</b></label>
                    <input type="text" class="form-control" id="guest_no" name="guest_no" value="<?php echo $row['guest_no'];?>" placeholder="กรอก เลขที่ผู้สมัคร" readonly>  
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6 margin_bt">
                    <label for="job_name" class="form-label"><b>ตำแหน่งที่สมัคร</b></label>
                    <input type="text" class="form-control" id="job_name" name="job_name" value="<?php echo $row['job_name'];?>" placeholder="กรอก ตำแหน่งที่สมัคร" readonly>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-6 margin_bt">
                    <label for="name" class="form-label"><b>ชื่อ-นามสกุล</b></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $row["title"].$row['name'];?>" placeholder="กรอก ชื่อ-นามสกุล" readonly>  
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6 margin_bt">
                    <label for="age" class="form-label"><b>อายุ</b></label>
                    <input type="text" class="form-control" id="age" name="age" value="<?php if (stripos($row['age'], 'ปี')) {echo $row['age'];}else{echo $row['age']." ปี";}?>" placeholder="กรอก อายุ" readonly>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-6 margin_bt">
                    <label for="salary" class="form-label"><b>เงินเดือนที่ต้องการ</b></label>
                    <input type="text" class="form-control" id="salary" name="salary" value="<?php if (stripos($row['salary'], ',')) {echo $row['salary'];}else{echo number_format($row['salary']);}?>" placeholder="กรอก เงินเดือนที่ต้องการ" readonly>  
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6 margin_bt">
                    <label for="phone" class="form-label"><b>เบอร์โทรศัพท์</b></label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['phone'];?>" placeholder="กรอก เบอร์โทรศัพท์" readonly>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-6 margin_bt">
                    <label for="interviews_date" class="form-label"><b>วันที่สัมภาษณ์</b> <span style="color: red;">*</span></label>
                    <input type="date" class="form-control" id="interviews_date" name="interviews_date"  placeholder="กรอก วันที่สัมภาษณ์" required>  
                    <div class="invalid-feedback">กรุณากรอก วันที่สัมภาษณ</div>
                </div>
                <div class="col-md-6 margin_bt">
                    <label for="selected" class="form-label"><b>ผลการสัมภาษณ์</b> <span style="color: red;">*</span></label>
                    <select class="form-select font_option" id="selected" name="selected" required onChange="close_fill()">
				        <option selected disabled value="">--เลือก--</option>
				        <option value="ผ่าน">ผ่าน</option>
				        <option value="ไม่ผ่าน">ไม่ผ่าน</option>
				    </select>
				    <div class="invalid-feedback">กรุณาเลือก ผลการสัมภาษณ์</div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-6 margin_bt">
                    <label for="accept_job" class="form-label"><b>การรับเข้าทำงาน</b></label>
                    <select class="form-select font_option" id="accept_job" name="accept_job">
				        <option selected disabled value="">--เลือก--</option>
				        <option value="รับเข้าทำงาน">รับเข้าทำงาน</option>
				        <option value="ไม่รับเข้าทำงาน">ไม่รับเข้าทำงาน</option>
				    </select>
				    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6 margin_bt">
                    <label for="selected_date" class="form-label"><b>วันที่พร้อมเริ่มงาน</b> </label>
                    <input type="date" class="form-control" id="selected_date" name="selected_date"  placeholder="กรอก วันที่พร้อมเริ่มงาน">  
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-12 margin_bt">
                    <label for="note" class="form-label"><b>หมายเหตุ</b> </label>
                    <textarea class="form-control font_option" id="note" name="note" rows="3" placeholder="กรอก หมายเหตุ (ถ้ามี)"></textarea>
				<div class="invalid-feedback"></div>
                </div>
            </div>
            <hr class="style-one mt-2">
            <div class="row mt-1 style_row">
                <div class="col-md-12 style_h" align="center">
                    <button class="btn btn-primary btn-sm" type="submit" name="button" id="button"><i class="fa-solid fa-floppy-disk fa-lg"></i> บันทึกผลการสัมภาษณ์</button>
                </div>
            </div>


        </form>
        
    </div>   
<!--------------------------->
</div> 
<!--------แจ้งเตือนเมื่อกด เรียกสัมภาษณ์ หรือ ไม่เรียกสัมภาษณ์----------->
<?php
if (!isset($_POST['guest_no'])) {  
}else{
$guest_no = $_POST['guest_no'];
$strSQL = "SELECT * FROM resume_job WHERE guest_no = ?";
$params = array($guest_no);
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
$Query = sqlsrv_query($conn, $strSQL, $params, $options);
$numRows = sqlsrv_num_rows($Query);
$row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC);

$modalMessage = '';
if ($numRows <= 0) {
    $modalMessage = "ไม่พบตำแหน่งงาน $job_no ในระบบ !!";
    $msg = "<span><i class='fa-solid fa-circle-xmark fa-lg text-danger'></i> $modalMessage</span>";
} else {
    if(isset($_POST['accept_job'])) {
        $accept_job = $_POST['accept_job'];
    }else{
        $accept_job = "";
    }

    if(isset($_POST['selected_date'])) {
        $selected_date = $_POST['selected_date'];
    }else{
        $selected_date = "";
    }

    date_default_timezone_set('Asia/Bangkok');
    $date_update = date('Y-m-d H:i:s');

    // Update query
    $sql = "
        UPDATE resume_job SET 
            selected = ?, interviews_date = ?, selected_date = ?, date_update = ?, note = ?, accept_job = ?  
        WHERE guest_no = ? ";
    
    $paramsUpdate = [
        $_POST['selected'], $_POST['interviews_date'], $selected_date, $date_update, $_POST['note'], $accept_job, $guest_no];

    $query = sqlsrv_query($conn, $sql, $paramsUpdate);

    if ($query) {
        $modalMessage = "บันทึกข้อมูลการสัมภาษณ์ของ $guest_no เรียบร้อยแล้ว !!";
        $msg = "<span><i class='fa-solid fa-circle-check fa-lg text-success'></i> $modalMessage</span>";
    } else {
        $modalMessage = "เกิดข้อผิดพลาดในการบันทึกข้อมูล !!";
        $msg = "<span><i class='fa-solid fa-circle-xmark fa-lg text-danger'></i> $modalMessage</span>";
    }
}
sqlsrv_close($conn);
?>
<!-- Modal -->
<div class="modal show" tabindex="-1" id="resultModal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> แจ้งเตือน !!</h5>
                <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" onclick="window.close();"></button>
            </div>
            <div class="modal-body font_bt"><?php echo $msg; ?></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" onclick="redirectToPostJobs()">ตกลง</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script>
    function redirectToPostJobs() {
        window.opener.location.href = 'interview_receives.php'; // กลับไปหน้า interview_receives.php
        window.close(); // ปิดหน้าต่างป๊อปอัป
    }

        // Old modal 
    // เปิด Modal โดยอัตโนมัติ
    // document.addEventListener('DOMContentLoaded', () => {
    //     const modal = new bootstrap.Modal(document.getElementById('resultModal'));
    //     modal.show();
    // });

    // New modal 
    document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('resultModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: 'static', // Prevents closing when clicking outside
            keyboard: false     // Prevents closing with the Escape key
        });
        modal.show();
    }
});

</script>

</body>
</html>