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

    <!-- Bootstrap CSS -->
    <!--<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">-->
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

	<link rel="icon" href="images/LOGO_ORNATE1.png" type="image/icon type">

</head>
<body>
<div class="m-0">
<?php
//include connect database
   include 'db_connect.php';
   include 'header.php';
   include 'modle_notify.php';

//	Query ข้อมูลจากเลขที่ผู้สมัคร 
    //$strSQL="select *,format(annoudate,'dd/MM/')+convert(varchar,year(annoudate)+543) as post_date from jobs_require where job_no ='".$_GET['job_no']."'";
    $strSQL="select *,format(create_date,'dd/MM/')+convert(varchar,year(create_date)+543) as create_date,
				format(expiry_date,'dd/MM/')+convert(varchar,year(expiry_date)+543) as expiry_date,
				format(rec_time_stamp,'dd/MM/')+convert(varchar,year(rec_time_stamp)+543) as date_create,
				format(work_date,'dd/MM/')+convert(varchar,year(work_date)+543) as work_date,
				format(birthday,'dd/MM/')+convert(varchar,year(birthday)+543) as birthday,
				case when datestart_1 <>'1900-01-01' then format(datestart_1,'dd/MM/')+convert(varchar,year(datestart_1)+543) else '' end as datestart_1,
				case when dateend_1 <>'1900-01-01' then format(dateend_1,'dd/MM/')+convert(varchar,year(dateend_1)+543) else '' end as dateend_1,
				case when datestart_2 <>'1900-01-01' then format(datestart_2,'dd/MM/')+convert(varchar,year(datestart_2)+543) else '' end as datestart_2,
				case when dateend_2 <>'1900-01-01' then format(dateend_2,'dd/MM/')+convert(varchar,year(dateend_2)+543) else '' end as dateend_2,
				case when  datestart_3 <>'1900-01-01' then format(datestart_3,'dd/MM/')+convert(varchar,year(datestart_3)+543) else '' end as datestart_3,
				case when dateend_3 <>'1900-01-01' then format(dateend_3,'dd/MM/')+convert(varchar,year(dateend_3)+543) else '' end as dateend_3,
				case when datestart_4 <>'1900-01-01' then format(datestart_4,'dd/MM/')+convert(varchar,year(datestart_4)+543) else '' end as datestart_4,
				case when dateend_4 <>'1900-01-01' then format(dateend_4,'dd/MM/')+convert(varchar,year(dateend_4)+543) else '' end as dateend_4
				from resume_job where guest_no = ?";
    $params = array($_GET['guest_no']);
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $Query = sqlsrv_query($conn,$strSQL, $params, $options);
    $numRows = sqlsrv_num_rows( $Query );
    $row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC);

    
	$img_file ="File_Upload_".$row['branch_job']."/".$row['guest_no']."/".$row['picture_upload'];
    if(file_exists($img_file)){
	    $img_guest = "File_Upload_".$row['branch_job']."/".$row['guest_no']."/".$row['picture_upload'];
	}else{
		$img_guest = "images/avatar.png";
	}

    if($row['sub_branch']=="ORNATE SDO" && $row['branch_job'] == "SRT"){
        $company = "บริษัท ออร์เนท เอสดีโอ จำกัด";
        $branch = "สำนักงานใหญ่";
       }else if($row['sub_branch']=="ORNATE SDO" && $row['branch_job'] == "HDY"){
        $company = "บริษัท ออร์เนท เอสดีโอ จำกัด";
        $branch = "หาดใหญ่";
       }else if($row['sub_branch']=="ORNATE SDO" && $row['branch_job'] == "CHM"){
        $company = "บริษัท ออร์เนท เอสดีโอ จำกัด";
        $branch = "เชียงใหม่";
       }else if($row['sub_branch']=="SHIPSHAPE"){
        $company = "บริษัท ชิพเชพ(ไทยแลนด์) จำกัด";
        $branch = "-";
       }else if($row['sub_branch']=="TIPTOP"){
        $company = "บริษัท ทิพทอพ(ไทยแลนด์) จำกัด";
        $branch = "-";
       }else if($row['sub_branch']=="THINKER"){
        $company = "บริษัท ธิงเคอร์(ไทยแลนด์) จำกัด";
        $branch = "-";
       }else if($row['sub_branch']=="ORNATE DRINK"){
        $company = "บริษัท ออร์เนท ดริ๊งค์ จำกัด";
        $branch = "-";
       }else if($row['sub_branch']=="ORNATE TRANDING"){
        $company = "หจก.ออร์เนท เทรดดิ้ง";
        $branch = "-";
       }else if($row['sub_branch']=="KUBKEEPHODPHAI"){
        $company = "บริษัท ขับขี่ปลอดภัย จำกัด";
        $branch = "-";
       }else if($row['sub_branch']=="TRONGPAI TRONGMA"){
        $company = "บริษัท ตรงไป ตรงมา จำกัด";
        $branch = "-";
       }else if($row['sub_branch']=="ORNATE LOGISTICS"){
        $company = "หจก.ออร์เนท โลจิสทิคซ์";
        $branch = "-";
       }else{
        $company = "-";
        $branch = "-";
       }
?>

</div>
<div class="container mt-5 mb-4"> 
<!--Div รายละเอียดตำแหน่งงานว่าง-->
    <div class="job_headers_dt"><span class="span_header font_h"> <?php echo "ข้อมูลสมัครงานเลขที่ : ".$row['guest_no'];?></span></div>
    <div class="section-body">
        <div class="row mb-4">
            <div class="col-md-2" align="center">
            <!--upload รูปถ่าย-->
                <div class="col-md-2 imgUp d-flex flex-column align-items-center">
                    <div class="holder">
                        <img  class="img_preview" id="img_preview" name="img_preview" src=<?php echo $img_guest;?> />
                    </div>                          
                </div>
            </div>
            <div class="col-md-10">
                <div class="row mb-0 mt-3">
                    <div class="col-md-6 mb-2">
                        <label for="" class="form-label pl3 font_h"><b><i class="fa-solid fa-building-user"></i> บริษัท :</b> <?php echo $company;?></label>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="" class="form-label pl3 font_h"><b><i class="fa-solid fa-house-user"></i> สาขา :</b> <?php echo $branch;?></label>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-md-6 mb-2">
                        <label for="" class="form-label pl3 font_h"><b><i class="fa-solid fa-id-badge"></i> ตำแหน่งที่ต้องการสมัคร :</b> <?php echo $row['job_name'];?></label>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="" class="form-label pl3 font_h"><b><i class="fa-solid fa-sack-dollar"></i> เงินเดือนที่ต้องการ :</b> <?php if (stripos($row['salary'], ',')) {echo $row['salary']." บาท";}else{echo number_format($row['salary'])." บาท";};?></label>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-md-6 mb-2">
                        <label for="" class="form-label pl3 font_h"><b><i class="fa-solid fa-calendar-plus"></i> วันที่กรอกใบสมัคร :</b> <?php echo $row['date_create'];?></label>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="" class="form-label pl3 font_h"><b><i class="fa-solid fa-calendar-check"></i> วันที่พร้อมเริ่มงาน :</b> <?php echo $row['work_date'];?></label>
                    </div>
                </div>
            </div>
        </div>   
        <!--<hr class="style-three">-->
        <div class ="style_d1 mb-1">
            <div class ="style_d2">
                <span class ="style_d3"> <i class="fa-solid fa-user-pen"></i> ประวัติส่วนตัว </span>
            </div>
            <div class="row mb-0 mt-4">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อ-นามสกุล :</b> <?php echo $row['title'].$row['name'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อเล่น :</b> <?php echo $row['nickname'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>เพศ :</b> <?php echo $row['sex'];?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>อายุ :</b> <?php echo $row['age'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันเดือนปีเกิด :</b> <?php echo $row['birthday'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>สถานภาพ :</b> <?php echo $row['status'];?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>เลขบัตรประชาชน :</b> <?php echo $row['idcard'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันออกบัตร :</b> <?php echo $row['create_date'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันหมดอายุ :</b> <?php echo $row['expiry_date'];?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>สัญชาติ :</b> <?php echo $row['nationality'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ศาสนา :</b> <?php echo $row['religion'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ส่วนสูง :</b> <?php echo $row['height']." ซม.";?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>น้ำหนัก :</b> <?php echo $row['weight']." กก.";?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>โทรศัพท์มือถือ :</b> <?php echo $row['phone'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>E-Mail :</b> <?php if($row['email'] !=""){echo $row['email'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>Line ID :</b> <?php if($row['line_id'] !=""){echo $row['line_id'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-8 mb-1">
                    <label for="" class="form-label pl3"><b>ที่อยู่ปัจจุบัน :</b> <?php echo $row['address'];?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>สถานภาพทางทหาร :</b> <?php if($row['military'] !=""){echo $row['military'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>โรคประจำตัว :</b> <?php echo $row['congenital_disease'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>อวัยวะที่พิการ :</b> <?php echo $row['disabled'];?></label>
                </div>
            </div> 
        </div>
        <br>
        <div class ="style_d1 mb-1">
            <div class ="style_d2">
                <span class ="style_d3"> <i class="fa-solid fa-user-graduate"></i> ประวัติการศึกษา </span>
            </div>
            <div class="row mb-0 mt-4">
                <div class="col-md-12 mb-0">
                    <label for="" class="form-label pl3"><h6><b><u>การศึกษาสูงสุด</u></b></h6></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อสถาบัน :</b> <?php echo $row['u_school'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ปีการศึกษาที่จบ :</b> <?php echo $row['u_year'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>เกรดเฉลี่ย :</b> <?php echo $row['u_gpa'];?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วุฒิการศึกษา :</b> <?php echo $row['u_educational'];?></label>
                </div>
                <div class="col-md-8 mb-1">
                    <label for="" class="form-label pl3"><b>สาขาวิชาเอก :</b> <?php echo $row['u_major'];?></label>
                </div>
            </div>
            <?php
            if(isset($row['v_school']) && $row['v_school'] != "" ){ ?>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0">
                <div class="col-md-12 bt_attact">
                    <label for="" class="form-label pl3"><h6><b><u>การศึกษาก่อนหน้า</u></b></h6></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อสถาบัน :</b> <?php echo $row['v_school'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ปีการศึกษาที่จบ :</b> <?php echo $row['v_year'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>เกรดเฉลี่ย :</b> <?php echo $row['v_gpa'];?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วุฒิการศึกษา :</b> <?php echo $row['v_educational'];?></label>
                </div>
                <div class="col-md-8 mb-1">
                    <label for="" class="form-label pl3"><b>สาขาวิชาเอก :</b> <?php echo $row['v_major'];?></label>
                </div>
            </div>
            <?php }else{} ?>
        </div>
        <br>
        <div class ="style_d1 mb-1">
            <div class ="style_d2">
                <span class ="style_d3"> <i class="fa-solid fa-user-tie"></i> ประวัติการทำงาน </span>
            </div>
            <div class="row mb-0 mt-4">
                <div class="col-md-12 mb-0">
                    <label for="" class="form-label pl3"><h6><b><u>การทำงานล่าสุด</u></b></h6></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อสถานที่ทำงาน :</b> <?php if($row['company_1'] !=""){echo $row['company_1'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ตำแหน่ง :</b> <?php if($row['position_1'] !=""){echo $row['position_1'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>เงินเดือนที่ได้รับ :</b> <?php if($row['salary_1'] !=""){echo $row['salary_1'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันที่เริ่มงาน :</b> <?php if($row['datestart_1'] !=""){echo $row['datestart_1'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันที่สิ้นสุดงาน :</b> <?php if($row['dateend_1'] !=""){echo $row['dateend_1'];}else{echo "-";}?></label>
                </div>  
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>ลักษณะงานโดยย่อ :</b> <?php if($row['detail_work_1'] !=""){echo $row['detail_work_1'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>สาเหตุที่ลาออก :</b> <?php if($row['remark_leave_1'] !=""){echo $row['remark_leave_1'];}else{echo "-";}?></label>
                </div>
            </div>
            <?php
            if(isset($row['company_2']) && $row['company_2'] != "" ){ ?>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0">
                <div class="col-md-12 bt_attact">
                    <label for="" class="form-label pl3"><h6><b><u>การทำงานที่ 2</u></b></h6></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อสถานที่ทำงาน :</b> <?php if($row['company_2'] !=""){echo $row['company_2'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ตำแหน่ง :</b> <?php if($row['position_2'] !=""){echo $row['position_2'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>เงินเดือนที่ได้รับ :</b> <?php if($row['salary_2'] !=""){echo $row['salary_2'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันที่เริ่มงาน :</b> <?php if($row['datestart_2'] !=""){echo $row['datestart_2'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันที่สิ้นสุดงาน :</b> <?php if($row['dateend_2'] !=""){echo $row['dateend_2'];}else{echo "-";}?></label>
                </div>  
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>ลักษณะงานโดยย่อ :</b> <?php if($row['detail_work_2'] !=""){echo $row['detail_work_2'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>สาเหตุที่ลาออก :</b> <?php if($row['remark_leave_2'] !=""){echo $row['remark_leave_2'];}else{echo "-";}?></label>
                </div>
            </div>
            <?php }else{} 

            if(isset($row['company_3']) && $row['company_3'] != "" ){ ?>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0">
                <div class="col-md-12 bt_attact">
                    <label for="" class="form-label pl3"><h6><b><u>การทำงานที่ 3</u></b></h6></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อสถานที่ทำงาน :</b> <?php if($row['company_3'] !=""){echo $row['company_3'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ตำแหน่ง :</b> <?php if($row['position_3'] !=""){echo $row['position_3'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>เงินเดือนที่ได้รับ :</b> <?php if($row['salary_3'] !=""){echo $row['salary_3'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันที่เริ่มงาน :</b> <?php if($row['datestart_3'] !=""){echo $row['datestart_3'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันที่สิ้นสุดงาน :</b> <?php if($row['dateend_3'] !=""){echo $row['dateend_3'];}else{echo "-";}?></label>
                </div>  
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>ลักษณะงานโดยย่อ :</b> <?php if($row['detail_work_3'] !=""){echo $row['detail_work_3'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>สาเหตุที่ลาออก :</b> <?php if($row['remark_leave_3'] !=""){echo $row['remark_leave_3'];}else{echo "-";}?></label>
                </div>
            </div>
            <?php }else{} 

            if(isset($row['company_4']) && $row['company_4'] != "" ){ ?>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0">
                <div class="col-md-12 bt_attact">
                    <label for="" class="form-label pl3"><h6><b><u>การทำงานที่ 4</u></b></h6></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อสถานที่ทำงาน :</b> <?php if($row['company_4'] !=""){echo $row['company_4'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ตำแหน่ง :</b> <?php if($row['position_4'] !=""){echo $row['position_4'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>เงินเดือนที่ได้รับ :</b> <?php if($row['salary_4'] !=""){echo $row['salary_4'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">  
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันที่เริ่มงาน :</b> <?php if($row['datestart_4'] !=""){echo $row['datestart_4'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>วันที่สิ้นสุดงาน :</b> <?php if($row['dateend_4'] !=""){echo $row['dateend_4'];}else{echo "-";}?></label>
                </div>  
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>ลักษณะงานโดยย่อ :</b> <?php if($row['detail_work_4'] !=""){echo $row['detail_work_4'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>สาเหตุที่ลาออก :</b> <?php if($row['remark_leave_4'] !=""){echo $row['remark_leave_4'];}else{echo "-";}?></label>
                </div>
            </div>
            <?php }else{} ?>
        </div>
        <br>
        <div class ="style_d1 mb-1">
            <div class ="style_d2">
                <span class ="style_d3"> <i class="fa-solid fa-users"></i> ประวัติครอบครัว </span>
            </div>
            <div class="row mb-0 mt-4">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อ-นามสกุล บิดา :</b> <?php echo $row['father_name'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>อายุ :</b> <?php  if($row['father_age'] !="" && $row['father_age'] !="-" ){echo $row['father_age']." ปี";}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>สถานภาพชีวิต :</b> <?php echo $row['father_status'];?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>โทรศัพท์มือถือ :</b> <?php if($row['father_talephone'] !=""){echo $row['father_talephone'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-8 mb-1">
                    <label for="" class="form-label pl3"><b>อาชีพ :</b> <?php if($row['father_occupation'] !=""){echo $row['father_occupation'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>ที่อยู่ หรือ ที่ทำงาน :</b> <?php if($row['father_Place_work'] !=""){echo $row['father_Place_work'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อ-นามสกุล มารดา :</b> <?php echo $row['mother_name'];?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>อายุ :</b> <?php if($row['mother_age'] !="" && $row['mother_age'] !="-"){echo $row['mother_age']." ปี";}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>สถานภาพชีวิต :</b> <?php echo $row['mother_status'];?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>โทรศัพท์มือถือ :</b> <?php if($row['mother_talephone'] !=""){echo $row['mother_talephone'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>อาชีพ :</b> <?php if($row['mother_occupation'] !=""){echo $row['mother_occupation'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>ที่อยู่ หรือ ที่ทำงาน :</b> <?php if($row['mother_Place_work'] !=""){echo $row['mother_Place_work'];}else{echo "-";}?></label>
                </div>
            </div>
            <?php
            if(isset($row['spouse_name']) && $row['spouse_name'] !="") { ?>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อ-นามสกุล คู่สมรส :</b> <?php if($row['spouse_name'] !=""){echo $row['spouse_name'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>อายุ :</b> <?php if($row['spouse_age'] !="" && $row['spouse_age'] !="-"){echo $row['spouse_age']." ปี";}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>จำนวนบุตร :</b> <?php if($row['children'] !="" && $row['children'] !="-"){echo $row['children']." คน";}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>โทรศัพท์มือถือ :</b> <?php if($row['spouse_talephone'] !=""){echo $row['spouse_talephone'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>อาชีพ :</b> <?php if($row['spouse_occupation'] !=""){echo $row['spouse_occupation'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>ที่อยู่ หรือ ที่ทำงาน :</b> <?php  if($row['spouse_Place_work'] !=""){echo $row['spouse_Place_work'];}else{echo "-";}?></label>
                </div>
            </div>
            <?php }else {} ?>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>จำนวนพี่น้องทั้งหมด :</b> <?php if($row['num_bro_sis'] !="" && $row['num_bro_sis'] !="-"){echo $row['num_bro_sis']." คน";}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>เป็นบุตรคนที่ :</b> <?php echo $row['num_sir'];?></label>
                </div>
            </div>
        </div>
        <br>
        <div class ="style_d1 mb-1">
            <div class ="style_d2">
                <span class ="style_d3"> <i class="fa-solid fa-user-check"></i> ความชำนาญ และ ข้อมูลอื่นๆ </span>
            </div>
            <div class="row mb-0 mt-4">
                <div class="col-md-12 mb-0">
                    <label for="" class="form-label pl3"><h6><b><u>ทักษะด้านภาษาไทย</u></b></h6></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-3 mb-1">
                    <label for="" class="form-label pl3"><b>การฟัง :</b> <?php echo $row['thai_listen'];?></label>
                </div>
                <div class="col-md-3 mb-1">
                    <label for="" class="form-label pl3"><b>การพูด :</b> <?php echo $row['thai_speak'];?></label>
                </div>
                <div class="col-md-3 mb-1">
                    <label for="" class="form-label pl3"><b>การอ่าน :</b> <?php echo $row['thai_read'];?></label>
                </div>
                <div class="col-md-3 mb-1">
                    <label for="" class="form-label pl3"><b>การเขียน :</b> <?php echo $row['thai_write'];?></label>
                </div>
            </div>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0 mt-0">
                <div class="col-md-12 mb-0 bt_attact">
                    <label for="" class="form-label pl3"><h6><b><u>ทักษะด้านภาษาอังกฤษ</u></b></h6></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-3 mb-1">
                    <label for="" class="form-label pl3"><b>การฟัง :</b> <?php echo $row['eng_listen'];?></label>
                </div>
                <div class="col-md-3 mb-1">
                    <label for="" class="form-label pl3"><b>การพูด :</b> <?php echo $row['eng_speak'];?></label>
                </div>
                <div class="col-md-3 mb-1">
                    <label for="" class="form-label pl3"><b>การอ่าน :</b> <?php echo $row['eng_read'];?></label>
                </div>
                <div class="col-md-3 mb-1">
                    <label for="" class="form-label pl3"><b>การเขียน :</b> <?php echo $row['eng_write'];?></label>
                </div>
            </div>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ภาษาอื่นๆ :</b> <?php if($row['other_languages'] !=""){echo $row['other_languages'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>สามารถขับขี่รถ :</b> <?php if($row['driver'] !=""){echo $row['driver'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>มีใบอนุญาตขับขี่ :</b> <?php if($row['drive_license'] !=""){echo $row['drive_license'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>ความสามารถพิเศษ/งานอดิเรก :</b> <?php if($row['talent_skill'] !=""){echo $row['talent_skill'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>การใช้โปรแกรมคอมพิวเตอร์ :</b> <?php if($row['computer_skill'] !=""){echo $row['computer_skill'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>ทราบข้อมูลสมัครงานทางช่องทาง :</b> <?php if($row['news'] !=""){echo $row['news'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0 mt-0">
                <div class="col-md-12 mb-0 bt_attact">
                    <label for="" class="form-label pl3"><h6><b><u>บุคคลที่รู้จักที่ทำงานในบริษัทนี้</u></b></h6></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อ-นามสกุล :</b> <?php if($row['person_name'] !=""){echo $row['person_name'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ตำแหน่งงาน :</b> <?php if($row['person_position'] !=""){echo $row['person_position'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ความสัมพันธ์ :</b> <?php if($row['person_relations'] !=""){echo $row['person_relations'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0 mt-0">
                <div class="col-md-12 mb-0 bt_attact">
                    <label for="" class="form-label pl3"><h6><b><u>บุคคลอ้างอิงรับรองการทำงาน หรือ ความสามารถ</u></b></h6></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ชื่อ-นามสกุล :</b> <?php if($row['person_referen_name'] !=""){echo $row['person_referen_name'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>ตำแหน่งงาน :</b> <?php if($row['person_referen_position'] !=""){echo $row['person_referen_position'];}else{echo "-";}?></label>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="" class="form-label pl3"><b>โทรศัพท์มือถือ :</b> <?php if($row['person_referen_phone'] !=""){echo $row['person_referen_phone'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3"><b>ที่อยู่ หรือ ที่ทำงาน :</b> <?php if($row['person_referen_address'] !=""){echo $row['person_referen_address'];}else{echo "-";}?></label>
                </div>
            </div>
            <div class="plr"><hr class="style-one mt-0"></div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3 "><b><li>ท่านจะยินดีให้เราสอบถามไปยังบริษัทที่ท่านทำงานอยู่ในขณะนี้หรือเคยทำงาน ตรวจสอบคุณวุติ และคุณสมบัติของท่านได้หรือไม่ :</b> <?php echo $row['imformation'];?></li></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3 fon_bt"><b><li>ท่านเคยถูกจับ หรือต้องโทษทางคดีอาญาและได้คำพิพากษาให้ได้รับโทษจำคุกหรือไม่ :</b> <?php echo $row['penalize'];?></li></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3 "><b><li>ท่านเคยถูกไล่ออกจากงาน เนื่องจากความประพฤติและงานไม่ดีพอหรือไม่ :</b> <?php echo $row['dismiss'];?></li></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3 "><b><li>ท่านได้รับเงินรายได้จากทางอื่น เช่น เบี้ยหวัด เงินบำเหน็จ หรือเงินค่าตอบแทนจากการเจ็บป่วยบ้างหรือไม่ :</b> <?php echo $row['income_other'];?></li></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3 "><b><li>ในขณะนี้ท่านได้เจ็บป่วย เป็นโรคเรื้อรัง หรือหย่อนสมรรถภาพอื่นๆ ทางร่างกายและอยู่ในความดูแลของแพทย์หรือไม่ :</b> <?php echo $row['health'];?></li></label>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-1">
                    <label for="" class="form-label pl3 "><b><li>ถ้าท่านได้รับพิจารณาให้บรรจุเป็นพนักงานของบริษัทฯ ท่านจะยินยอมให้บริษัทฯ ย้ายตำแหน่งหน้าที่ตามความเหมาะสมโดยไม่ลดเงินค้าจ้าง และผลประโยชน์ได้หรือไม่ :</b> <?php echo $row['move_job'];?></li></label>
                </div>
            </div>     
        </div>
        <br>
        <div class ="style_d1 mb-1">
            <div class ="style_d2">
                <span class ="style_d3"> <i class="fa-solid fa-circle-question"></i> แบบสอบถามเพิ่มเติม </span>
            </div>
            <div class="row mb-0 mt-4">
                <div class="col-md-12 mb-0">
                    <label for="" class="form-label pl3"><b>1.อธิบายตัวอย่างที่ท่านตั้งเป้าหมายไว้สูง และฝ่าฟันอุปสรรคต่างๆ จนประสบความสำเร็จ</b></label>
                    <ul class="bt_attact"><li><?php if($row['more_infor1'] !=""){echo $row['more_infor1'];}else{echo "-";}?></li></ul>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-0">
                    <label for="" class="form-label pl3"><b>2.สรุปสถานการณ์ที่ท่านเสนอความคิดริเริ่มให้คนอื่นทำงานที่สำคัญ และสามารถเป็นผู้นำให้ทำงานนั้นออกมาประสบความสำเร็จตามที่ต้องการ</b></label>
                    <ul class="bt_attact"><li><?php if($row['more_infor2'] !=""){echo $row['more_infor2'];}else{echo "-";}?></li></ul>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-0">
                    <label for="" class="form-label pl3"><b>3.อธิบายสถานการณ์ของการแก้ปัญหา โดยที่ท่านต้องค้นหาข้อมูลที่เกี่ยวข้อง แยกแยะประเด็นสำคัญและตัดสินใจว่าจะทำอย่างไรเพื่อให้ได้ผลที่ต้องการ</b></label>
                    <ul class="bt_attact"><li><?php if($row['more_infor3'] !=""){echo $row['more_infor3'];}else{echo "-";}?></li></ul>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-0">
                    <label for="" class="form-label pl3"><b>4.อธิบายถึงสถานการณ์ที่ท่านนำมาเสนอ/ชี้แจง โดยใช้ข้อมูลที่เป็นจริงอย่างมีประสิทธิภาพในการทำงานให้คนอื่นเห็นด้วย</b></label>
                    <ul class="bt_attact"><li><?php if($row['more_infor4'] !=""){echo $row['more_infor4'];}else{echo "-";}?></li></ul>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-0">
                    <label for="" class="form-label pl3"><b>5.ยกตัวอย่างสถานการณ์ที่ท่านได้ทำงานร่วมกับคนอื่นอย่างมีประสิทธิภาพ เพื่อให้ประสบความสำเร็จในงานสำคัญ</b></label>
                    <ul class="bt_attact"><li><?php if($row['more_infor5'] !=""){echo $row['more_infor5'];}else{echo "-";}?></li></ul>
                </div>
            </div>
            <div class="row mb-0 bt_attact">
                <div class="col-md-12 mb-0">
                    <label for="" class="form-label pl3"><b>6.อธิบายถึงความคิดสร้างสรรค์ที่ท่านได้คิดขึ้นมา และมีส่วนสำคัญในการสนับสนุนให้กิจกรรมหรือโครงการนั้นประสบความสำเร็จ</b></label>
                    <ul class="bt_attact"><li><?php if($row['more_infor6'] !=""){echo $row['more_infor6'];}else{echo "-";}?></li></ul>
                </div>
            </div>
                
            
                
        </div>

        <br>
        <div class="style_d1 mb-1">
                <div class="style_d2">
                    <span class="style_d3"><i class="fa-solid fa-paperclip"></i> ไฟล์แนบ</span>
                </div>
                <br>

                <?php 
                    $path = "file_upload_".$row['branch_job']."/".$row['guest_no']."/";
                    $fileTypes = [
                        "idcard" => "1.สำเนาบัตรประจำตัวประชาชน",
                        "address" => "2.สำเนาทะเบียนบ้าน",
                        "transcript" => "3.สำเนาหลักฐานการศึกษา",
                        "malitary" => "4.สำเนาหลักฐานทางทหาร",
                        "driver" => "5.สำเนาใบอนุญาตขับขี่",
                        "certify" => "6.เอกสารรับรองการผ่านงาน",
                        "portfolio" => "7.Portfolio หรือ Resume"
                    ];
                ?>
                <input type="hidden" id="path" name="path" value="<?php echo $path; ?>">

                <div class="row">
                    <?php 
                        $i = 0;
                        foreach ($fileTypes as $type => $label) {
                            $uploadValue = $row["{$type}_upload"];
                            $buttonClass = $uploadValue ? "btn-primary" : "btn-secondary";
                            $disabled = $uploadValue ? "" : "disabled";
                            $onClick = $uploadValue ? "onclick=\"fileUpload('$type')\"" : "";
                            
                            echo "<div class='col-md-4 mb1' style='margin-bottom: 7px; position: relative;'>
                                    <label for='{$type}_upload' class='form-label pl3' style='font-weight: bold;'>$label : </label>
                                    <button type='button' class='btn $buttonClass btn-sm' $onClick $disabled>
                                        <i class='fa-solid fa-paperclip'></i> ดูไฟล์แนบ
                                    </button>
                                    <input type='hidden' id='{$type}_upload' name='{$type}_upload' value='{$uploadValue}'>
                                </div>";

                            if (++$i % 3 == 0) {
                                echo "</div><div class='row'>";
                            }
                        }
                    ?>
                </div>
            </div> 
            <div style="text-align: center;">
                <button type="button" class="btn btn-primary mt-3" onclick="window.open('exportjob_pdf.php?guest_no=<?php echo $_GET["guest_no"];?>')"/>
                <i class="fa-solid fa-file-pdf"></i> ปริ้นใบสมัคร to PDF</button>
            </div>  
        </div>

    </div>

    
<!--------------------------->
</div>  
</body>
</html>