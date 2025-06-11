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

$strSQL="select *,format(annoudate,'dd/MM/')+convert(varchar,year(annoudate)+543) as post_date from jobs_require where job_no = ? ";

    $params = [$_GET['job_no']];
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $Query = sqlsrv_query($conn,$strSQL, $params, $options);
    $numRows = sqlsrv_num_rows( $Query );
    $row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC);
?>
</div>
<div class="container mt-1 mb-2">
<!--Div รายละเอียดตำแหน่งงานว่าง-->
    <div class="section-header"><i class="fa-regular fa-folder-open fa-lg txt_icon"></i><span class="txt_toppic"> <?php echo "ตำแหน่ง : ".$row['position'];?></span></div>
    <div class="section-body">
        <div class="row  mb-1">
            <div class="col-md-3 style_h"><i class="fa-solid fa-qrcode"></i> QR Code :</div>
            <div id="qrcode-<?php echo $row['job_no']; ?>" class="qrcode-container col-md-9 style_d"></div>
        </div>
        <script>
        // สร้าง QR Code สำหรับแต่ละ job_no
        new QRCode(document.getElementById("qrcode-<?php echo $row['job_no']; ?>"), {
            text: "<?php echo 'https://ornwebapp.com/ornjobs/job_detail.php?job_no=' . $row['job_no']; ?>",
            width: 100,  // ขนาดความกว้างของ QR Code
            height: 100  // ขนาดความสูงของ QR Code
        });
		</script>
        <div class="row  mb-1">
            <div class="col-md-3 style_h"><i class="fa-solid fa-user"></i> จำนวนที่รับ :</div>
            <div class="col-md-9 style_d"><?php echo $row["require_no"]." ตำแหน่ง";?></div>
        </div>
        <hr class="style-one mt-2"> 
        <div class="row mb-1 style_row">
            <?php 
                if($row["salary"] != ""){
                    $salary = $row["salary"];
                }else{
                    $salary = "ไม่ระบุ";
                } 
            ?>
            <div class="col-md-3 style_h"><i class="fa-solid fa-baht-sign"></i> เงินเดือน :</div>
            <div class="col-md-9 style_d"><?php echo $salary;?></div>
        </div>
        <hr class="style-one mt-2">
        <div class="row mb-1 style_row">
            <?php 
                if($row["job_location"] != ""){
                    $job_location = $row["job_location"];
                }else{
                    $job_location = "ไม่ระบุ";
                } 
            ?>
            <div class="col-md-3 style_h"><i class="fa-solid fa-house"></i> สถานที่ปฎิบัติงาน :</div>
            <div class="col-md-9 style_d"><?php echo $job_location;?></div>
        </div>
        <hr class="style-one mt-2">
        <div class="row mb-1 style_row">
            <div class="col-md-3 style_h"><i class="fa-solid fa-user-tag"></i> รายละเอียดงาน :</div>
            <div class="col-md-9 style_d">
                <ul>
                <?php 
                    if($row['job_attribute1'] != ""){ ?>
                        <li><?php echo $row['job_attribute1'];?></li>
                    <?php }else{} 
                    if($row['job_attribute2'] != ""){ ?>
                        <li><?php echo $row['job_attribute2'];?></li>
                    <?php }else{}
                    if($row['job_attribute3'] != ""){ ?>
                        <li><?php echo $row['job_attribute3'];?></li>
                    <?php }else{}
                    if($row['job_attribute4'] != ""){ ?>
                        <li><?php echo $row['job_attribute4'];?></li>
                    <?php }else{}
                    if($row['job_attribute5'] != ""){ ?>
                        <li><?php echo $row['job_attribute5'];?></li>
                    <?php }else{} ?>
                </ul>
            </div>
        </div>
        <hr class="style-one style_mt">
        <div class="row mb-1 style_row">
            <div class="col-md-3 style_h"><i class="fa-solid fa-user-graduate"></i> คุณสมบัติ :</div>
            <div class="col-md-9 style_d">
                <ul>
                <?php 
                    if(isset($row['gender']) && $row['gender'] != "NULL"){ ?>
                        <li><?php echo "เพศ ".$row['gender'];?></li>
                    <?php }else{} 
                    if(isset($row['age']) && $row['age'] != "NULL"){ ?>
                        <li>
                        <?php 
                            if (strpos($row['age'], 'อายุ') !== false) { 
                                if(strpos($row['age'], 'ปี') !== false){
                                    echo $row['age'];
                                }else{
                                echo $row['age']." ปี";
                                }
                            }else{ 
                                if(strpos($row['age'], 'ปี') !== false){
                                    echo "อายุ ".$row['age'];
                                }else{
                                echo "อายุ ".$row['age']." ปี";
                            } 
                        }?></li>
                    <?php }else{}
                    if(isset($row['job_certificate1']) && $row['job_certificate1'] != "NULL" && $row['job_certificate1'] != ""){ ?>
                        <li>
                        <?php 
                            if($row['job_certificate1'] == "ไม่จำกัดวุฒิฯ"){
                                if($row['major'] == ""){
                                    echo $row['job_certificate1'];
                                }else{
                                    echo $row['job_certificate1']." ".$row['major'];
                                }
                            }else{
                                if($row['major'] == ""){
                                    echo "วุฒิการศึกษา ".$row['job_certificate1'];
                                }else{
                                    echo "วุฒิการศึกษา ".$row['job_certificate1']." สาขา".$row['major'];
                                }
                            }
                        ?></li>
                    <?php }else{}
                    if(isset($row['job_certificate2']) && $row['job_certificate2'] != "NULL" && $row['job_certificate2'] != ""){ ?>
                        <li><?php echo $row['job_certificate2'];?></li>
                            <?php }else{}
                            if(isset($row['job_certificate3']) && $row['job_certificate3'] != "NULL" && $row['job_certificate3'] != ""){ ?>
                        <li><?php echo $row['job_certificate3'];?></li>
                            <?php }else{}
                            if(isset($row['job_certificate4']) && $row['job_certificate4'] != "NULL" && $row['job_certificate4'] != ""){ ?>
                        <li><?php echo $row['job_certificate4'];?></li>
                            <?php }else{}
                            if(isset($row['job_certificate5']) && $row['job_certificate5'] != "NULL" && $row['job_certificate5'] != ""){ ?>
                        <li><?php echo $row['job_certificate5'];?></li>
                            <?php }else{}
                            if(isset($row['job_certificate6']) && $row['job_certificate6'] != "NULL" && $row['job_certificate6'] != ""){ ?>
                        <li><?php echo $row['job_certificate6'];?></li>
                            <?php }else{} 
                    ?>
                </ul>
            </div>
        </div>
        <hr class="style-one style_mt">
        <div class="row mb-1 style_row">
            <div class="col-md-3 style_h"><i class="fa-solid fa-user-check"></i> สวัสดิการพนักงาน :</div>
            <div class="col-md-9 style_d">
                <ul>
                    <?php 
                    if(isset($row['benefits1']) && $row['benefits1'] != "NULL" && $row['benefits1'] != ""){ ?>
                        <li><?php echo $row['benefits1'];?></li>
                    <?php }else{} 
                    if(isset($row['benefits2']) && $row['benefits2'] != "NULL" && $row['benefits2'] != ""){ ?>
                        <li><?php echo $row['benefits2'];?></li>
                    <?php }else{}
                    if(isset($row['benefits3']) && $row['benefits3'] != "NULL" && $row['benefits3'] != ""){ ?>
                        <li><?php echo $row['benefits3'];?></li>
                    <?php }else{}
                    if(isset($row['benefits4']) && $row['benefits4'] != "NULL" && $row['benefits4'] != ""){ ?>
                        <li><?php echo $row['benefits4'];?></li>
                    <?php }else{}
                    if(isset($row['benefits5']) && $row['benefits5'] != "NULL" && $row['benefits5'] != ""){ ?>
                        <li><?php echo $row['benefits5'];?></li>
                    <?php }else{} 
                    if(isset($row['benefits6']) && $row['benefits6'] != "NULL" && $row['benefits6'] != ""){ ?>
                        <li><?php echo $row['benefits6'];?></li>
                    <?php }else{} 
                    if(isset($row['benefits7']) && $row['benefits7'] != "NULL" && $row['benefits7'] != ""){ ?>
                        <li><?php echo $row['benefits7'];?></li>
                    <?php }else{} 
                    if(isset($row['benefits8']) && $row['benefits8'] != "NULL" && $row['benefits8'] != ""){ ?>
                        <li><?php echo $row['benefits8'];?></li>
                    <?php }else{}
                    if(isset($row['benefits9']) && $row['benefits9'] != "NULL" && $row['benefits9'] != ""){ ?>
                        <li><?php echo $row['benefits9'];?></li>
                    <?php }else{}
                    ?>
                </ul>
            </div>
        </div>
        <hr class="style-one style_mt">
        <div class="row mb-1 style_row">
            <div class="col-md-3 style_h"><i class="fa-brands fa-square-whatsapp"></i> ติดต่อสอบถาม :</div>
            <div class="col-md-9 style_d">
                <ul>
                    <?php
                    if(trim($row['branch']) == "SRT"){
                        $address = "เลขที่ 31/237 กานจนวิธี 16 ถนนสุราษฎร์-นครศรีธรรมราช ตำบลบางกุ้ง อำเภอเมืองสุราษฎร์ธานี จังหวัดสุราษฎร์ธานี 84000";
                        $sub_b = "(สำนักงานใหญ่)";
                        $line_id ="<a href=\"https://line.me/ti/p/NKzv6seCSM"."\" target=\"_blank"."\">เพิ่มเพื่อน</a>";
                    }else if (trim($row['branch']) == "HDY"){
                        $address = "เลขที่ 671 หมู่ที่ 3 ถนนเพชรเกษม ตำบลควนลัง อำเภอหาดใหญ่ จังหวัดสงขลา 90110";
                        $sub_b = "(สาขาหาดใหญ่)";
                        $line_id ="<a href=\"https://line.me/ti/p/~hrornhdy"."\" target=\"_blank"."\">เพิ่มเพื่อน</a>";
                    }else if (trim($row['branch']) == "CHM"){
                        $address = "เลขที่ 323 หมู่ที่ 1 ตำบลหนองหอย อำเภอเมืองเชียงใหม่ จังหวัดเชียงใหม่ 50000";
                        $sub_b = "(สาขาเชียงใหม่)";
                        $line_id ="<a href=\"https://line.me/ti/p/~0813702662"."\" target=\"_blank"."\">เพิ่มเพื่อน</a>";
                    }else{}
                    ?>
                        <li><?php echo $row['branch_name']." ".$sub_b."<br>".$address;?></li>
                    <?php
                    if(isset($row['hrcontact']) && $row['hrcontact'] != "NULL"){?>
                        <li><?php echo "ฝ่ายบุคคล ".$row['hrcontact'];?></li>
                    <?php }else{}
                    if(isset($row['hrcontact2']) && $row['hrcontact2'] != "NULL"){ ?>
                        <li><?php echo "อีเมล ".$row['hrcontact2'];?></li>
                    <?php }else{}
                    ?>
                        <li><?php echo "Line ID : ".$line_id;?></li>
                </ul>
            </div>
        </div>
        <hr class="style-one style_mt">
        <div class="row mb-1 style_row">
            <div class="col-md-3 style_h"><i class="fa-solid fa-calendar-days"></i> วันที่ประกาศ :</div>
            <div class="col-md-9 style_d"><?php echo $row["post_date"];?></div>
        </div>
        <hr class="style-one mt-2">
        <div class="row mt-1 style_row">
            <div class="col-md-12 style_h" align="center">
                <button type="button" class="btn btn-danger btn-sm mt-3" onclick="location.href='ornjob_details.php?status=close&job_no=<?php echo $row['job_no'];?>';">
                <i class="fa-regular fa-circle-xmark fa-lg"></i> ปิดรับสมัคร</button>
            </div>
        </div>	
    </div>   
<!--------------------------->
</div> 

<!--------แจ้งเตือนปิดตำแหน่งงาน----------->
<?php
if (!isset($_GET['status']) || !isset($_GET['job_no'])) {
}else{    
   $job_no = $_GET['job_no'];
   $status = isset($_GET['status']) ? $_GET['status'] : null;

   $strSQL2 = "SELECT * FROM jobs_require WHERE job_no = ?";
   $params2 = array($job_no);
   $options2 = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
   $Query2 = sqlsrv_query($conn, $strSQL2, $params2, $options2);
   $numRows2 = sqlsrv_num_rows($Query2);
   $row2 = sqlsrv_fetch_array($Query2, SQLSRV_FETCH_ASSOC);

   $modalMessage = '';
   if ($numRows2 <= 0) {
       $modalMessage = "ไม่พบข้อมูลตำแหน่งงาน $job_no ในระบบ";
       $msg="<span><i class='fa-solid fa-circle-xmark fa-lg text-danger'></i> ".$modalMessage."</span>"; 
   } else {
       if ($status == "close") {
            $sql = "UPDATE jobs_require Set job_status='N' WHERE job_no = ?";
            $updateParams = array($job_no);
            $query = sqlsrv_query($conn, $sql, $updateParams);

            $modalMessage = "ปิดรับสมัครตำแหน่งงาน $job_no เรียบร้อยแล้ว !!";
            $msg="<span><i class='fa-solid fa-circle-check fa-lg text-success'></i> ".$modalMessage."</span>";      
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
                <button type="button" class="btn-close text-danger" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body font_bt"><?php echo $msg; ?></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" onclick="redirectToDashboard()">ตกลง</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
    <script>
        function redirectToDashboard() {
            window.opener.location.href = 'post_ornjobs.php'; // กลับไปหน้า dashboard
            window.close(); // ปิดหน้าต่างป๊อปอัป
        }

        // เปิด Modal โดยอัตโนมัติ
        document.addEventListener('DOMContentLoaded', () => {
            const modal = new bootstrap.Modal(document.getElementById('resultModal'));
            modal.show();
        });
    </script>

</body>
</html>