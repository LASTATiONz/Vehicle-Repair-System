<?php
// Connection To SQL Server
include 'db_connect.php';
   
   session_start();
   ob_start();

   if(isset($_GET['guest_no'])){
    $guest_no = $_GET['guest_no'];
   }else{
    $guest_no = "";
   }
  
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
		from resume_job where guest_no ='".$guest_no."'";
			
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$Query = sqlsrv_query($conn,$strSQL, $params, $options);
	$numRows = sqlsrv_num_rows( $Query );
    $row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC);
    
    $img_file = "File_Upload_" . rtrim($row['branch_job']) . "/" . rtrim($row['guest_no']) . "/" . rtrim($row['picture_upload']);
    $dummy_image = "images/dummy.png";

    if (!empty($row['picture_upload']) && file_exists($img_file)) {
        $img_guest = "<img src=\"$img_file"."\" width=\"70"."\" height=\"80"."\" style=\"border: 1px solid #000033; border-radius: 5px;"."\">";
    } else {
        $img_guest = "<img src=\"$dummy_image"."\" width=\"70"."\" height=\"80"."\" style=\"border: 1px solid #000033; border-radius: 5px;"."\">";
    }


	if($row['sub_branch']=="ORNATE SDO"){
		  $logo = "<img src=\"images/LOGO_ORNATE1.png"."\"  width=\"60"."\" height=\"60"."\" align=\"absmiddle"."\" valign=\"top"."\">";
		  $company = "ORNATE SDO CO.,LTD.";
	}else if($row['sub_branch']=="SHIPSHAPE"){
		  $logo = "<img src=\"images/shipshape_logos.png"."\"  width=\"60"."\" height=\"60"."\" align=\"absmiddle"."\" valign=\"top"."\">";
		  $company = "SHIPSHAPE (THAILAND) CO.,LTD.";
	}else if($row['sub_branch']=="TIPTOP"){
		  $logo = "<img src=\"images/tiptop_logos.png"."\"  width=\"60"."\" height=\"60"."\" align=\"absmiddle"."\" valign=\"top"."\">";
		  $company = "TIPTOP (THAILAND) CO.,LTD.";
	}else if($row['sub_branch']=="THINKER"){
		  $logo = "<img src=\"images/thinker_logos.png"."\"  width=\"60"."\" height=\"60"."\" align=\"absmiddle"."\" valign=\"top"."\">";
		  $company = "THINKER (THAILAND) CO.,LTD.";
	}else if($row['sub_branch']=="ORNATE DRINK"){
		  $logo = "<img src=\"images/ornatedrink_logo.png"."\"  width=\"60"."\" height=\"60"."\" align=\"absmiddle"."\" valign=\"top"."\">";
		  $company = "ORNATE DRINK CO.,LTD.";
	}else if($row['sub_branch']=="ORNATE TRANDING"){
		  $logo = "<img src=\"images/ornatetranding_logo.png"."\"  width=\"60"."\" height=\"60"."\" align=\"absmiddle"."\" valign=\"top"."\">";
		  $company = "ORNATE TRANDING LIMITED PARTNERSHIP";
	}else if($row['sub_branch']=="KUBKEEPHODPHAI"){
		  $logo = "<img src=\"images/kubkeplodpai_logos.png"."\"  width=\"60"."\" height=\"60"."\" align=\"absmiddle"."\" valign=\"top"."\">";
		  $company = "บริษัท ขับขี่ปลอดภัย จำกัด";
	}else if($row['sub_branch']=="TRONGPAI TRONGMA"){
		  $logo = "<img src=\"images/trongpai_trongma_logos.png"."\"  width=\"60"."\" height=\"60"."\" align=\"absmiddle"."\" valign=\"top"."\">";
		  $company = "บริษัท ตรงไปตรงมา จำกัด";
	}else if($row['sub_branch']=="ORNATE LOGISTICS"){
		  $logo = "<img src=\"images/ornatelogistics_logo1.png"."\"  width=\"60"."\" height=\"60"."\" align=\"absmiddle"."\" valign=\"top"."\">";
		  $company = "ORNATE LOGISTICS CO.,LTD.";
	}else{
		  $logo = "<img src=\"images/no_logo.png"."\"  width=\"60"."\" height=\"60"."\" align=\"absmiddle"."\" valign=\"top"."\">"; 
		   $company = "";
	}


  if($row['sub_branch']=="ORNATE SDO"){
	if($row['branch_job'] =="SRT"){
	$img_srt = "<img src=\"images/b_select.png"."\"  width=\"9.2px"."\" height=\"8.2px"."\" />";
	$branch_srt = "สำนักงานใหญ่";
	$address_srt = "31/237 หมู่ที่ 2 ถ.สุราษฎร์-นครศรีฯ ต.บางกุ้ง อ.เมือง จ.สุราษฎร์ธานี 84000";
	}else{
	$img_srt = "<img src=\"images/b_noselect.png"."\"  width=\"9px"."\" height=\"8px"."\" />";
	$branch_srt = "สำนักงานใหญ่";
	$address_srt = "31/237 หมู่ที่ 2 ถ.สุราษฎร์-นครศรีฯ ต.บางกุ้ง อ.เมือง จ.สุราษฎร์ธานี 84000";
	}

	if($row['branch_job'] =="HDY"){
	$img_hdy ="<img src=\"images/b_select.png"."\"  width=\"9.2px"."\" height=\"8.2px"."\" />";
	$branch_hdy = "สาขาหาดใหญ่";
	$address_hdy = "671 หมู่ที่ 3 ถ.เพชรเกษม ต.ควนลัง อ.หาดใหญ่ จ.สงขลา 90110";
	}else{
	$img_hdy = "<img src=\"images/b_noselect.png"."\"  width=\"9px"."\" height=\"8px"."\" />";
	$branch_hdy = "สาขาหาดใหญ่";
	$address_hdy = "671 หมู่ที่ 3 ถ.เพชรเกษม ต.ควนลัง อ.หาดใหญ่ จ.สงขลา 90110";
	}
		
	if($row['branch_job'] =="CHM"){
	$img_chm ="<img src=\"images/b_select.png"."\"  width=\"9.2px"."\" height=\"8.2px"."\" />";
	$branch_chm = "สาขาเชียงใหม่";
	$address_chm = "323 หมู่ที่ 1 ต.หนองหอย อ.เมือง จ.เชียงใหม่ 50000";
	}else{
	$img_chm = "<img src=\"images/b_noselect.png"."\"  width=\"9px"."\" height=\"8px"."\" />";
	$branch_chm = "สาขาเชียงใหม่";
	$address_chm = "323 หมู่ที่ 1 ต.หนองหอย อ.เมือง จ.เชียงใหม่ 50000";
	}
  }elseif($row['sub_branch']=="SHIPSHAPE" || $row['sub_branch']=="ORNATE DRINK" || $row['sub_branch']=="ORNATE TRANDING" || $row['sub_branch']=="KUBKEEPHODPHAI"){
	$img_srt = "<img src=\"images/b_select.png"."\"  width=\"9.2px"."\" height=\"8.2px"."\" />";
	$branch_srt = "สำนักงานใหญ่";
	$address_srt = "31/237 หมู่ที่ 2 ถ.สุราษฎร์-นครศรีฯ ต.บางกุ้ง อ.เมือง จ.สุราษฎร์ธานี 84000";	
  }elseif($row['sub_branch']=="TIPTOP"){
	$img_srt = "<img src=\"images/b_noselect.png"."\"  width=\"9px"."\" height=\"8px"."\" />";
	$branch_srt = "สำนักงานใหญ่";
	$address_srt = "31/237 หมู่ที่ 2 ถ.สุราษฎร์-นครศรีฯ ต.บางกุ้ง อ.เมือง จ.สุราษฎร์ธานี 84000";
	$img_hdy ="<img src=\"images/b_select.png"."\"  width=\"9.2px"."\" height=\"8.2px"."\" />";
	$branch_hdy = "สาขาหาดใหญ่";
	$address_hdy = "671 หมู่ที่ 3 ถ.เพชรเกษม ต.ควนลัง อ.หาดใหญ่ จ.สงขลา 90110";
  }elseif($row['sub_branch']=="THINKER" || $row['sub_branch']=="TRONGPAI TRONGMA" || $row['sub_branch']=="ORNATE LOGISTICS"){
	$img_srt = "<img src=\"images/b_noselect.png"."\"  width=\"9px"."\" height=\"8px"."\" />";
	$branch_srt = "สำนักงานใหญ่";
	$address_srt = "31/237 หมู่ที่ 2 ถ.สุราษฎร์-นครศรีฯ ต.บางกุ้ง อ.เมือง จ.สุราษฎร์ธานี 84000";
	$img_chm ="<img src=\"images/b_select.png"."\"  width=\"9.2px"."\" height=\"8.2px"."\" />";
	$branch_chm = "สาขาเชียงใหม่";
	$address_chm = "323 หมู่ที่ 1 ต.หนองหอย อ.เมือง จ.เชียงใหม่ 50000";
  }

require_once('tcpdf/tcpdf.php');
header("Link: <images/LOGO_ORNATE1.png>; rel=icon; type=image/png");

// สร้าง Class ใหม่ขึ้นมา กำหนดให้สืบทอดจาก Class ของ TCPDF
class ornjob extends TCPDF
{
    // สร้าง function ชื่อ Footer สำหรับปรับแต่งการแสดงผลในส่วนท้ายของเอกสาร
    public function Footer()
    {
        // สำหรับตัวอักษรที่จะใช้คือ helvetica เป็นตัวเอียง และขนาดอักษรคือ 8
        $this->SetFont('thsarabunnew', '', 9);
        // คำสั่งสำหรับแสดงข้อความ โดยกำหนด ความกว้าง และ ความสูงของข้อความได้ใน 2 ช่องแรก
        // ช่องที่ 3 คือข้อความที่แสดง $this->getAliasNumPage() คือ หมายเลขหน้าปัจจุบัน และ $this->getAliasNbPages() จำนวนหน้าทั้งหมด
        // ส่วนค่า R คือ กำหนดให้แสดงข้อความชิดขวา
        $this->Cell('0', '', 'หน้า ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C');
    }
}


$pdf = new ornjob(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8',false);

//$pdf->setHeaderFont(array('freeserif', '', 14));

$pdf->SetTitle('ORNJOB_'.$guest_no);
$pdf->setPrintHeader(false);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

if($row['sub_branch']=="ORNATE SDO"){
$html = '<table border="0" style="border-bottom: 0.01rem solid #000033;" color="#000033" cellpadding="0" cellspacing="0">'
		    .'<tr>'
		    .'<th align="center" width = "87%" style="font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ใบสมัครงาน</b></th>'
            . '<td rowspan="5" width="13%" style="text-align: center;">'.$img_guest.'</td>'
			.'</tr>'
			.'<tr>'
            . '<td rowspan="4" width="12%" align="center">'.$logo.'</td>'
            . '<td colspan="2" width="75%"><span style="font-size: 13px;" ><b>'.$company.'</b></span></td>'
			.'</tr>'
			.'<tr>'
			. '<td width="15%">&nbsp;&nbsp;<span>'.$img_srt.'</span>&nbsp;&nbsp;<span style="font-size: 12px;"><b>'.$branch_srt.'</b></span></td>'
			. '<td width="60%"><span><b>'.$address_srt.'</b></span></td>'
			.'</tr>'
			.'<tr>'
			. '<td>&nbsp;&nbsp;<span>'.$img_hdy.'</span>&nbsp;&nbsp;<span style="font-size: 12px;"><b>'.$branch_hdy.'</b></span></td>'
			. '<td><span><b>'.$address_hdy.'</b></span></td>'
			.'</tr>'
			.'<tr>'
			. '<td>&nbsp;&nbsp;'.$img_chm.'&nbsp;&nbsp;<span style="font-size: 12px;"><b>'.$branch_chm.'</b></span></td>'
			. '<td><span><b>'.$address_chm.'</b></span></td>'
			.'</tr>'
            .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );
}else if($row['sub_branch']=="SHIPSHAPE" || $row['sub_branch']=="ORNATE DRINK" || $row['sub_branch']=="ORNATE TRANDING" || $row['sub_branch']=="KUBKEEPHODPHAI"){
$html = '<table border="0" color="#000033" cellpadding="1" cellspacing="0">'
		    .'<tr>'
		    .'<th align="center" width = "87%" style="font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ใบสมัครงาน</b></th>'
            . '<td rowspan="5" width="13%" align="center">'.$img_guest.'</td>'
			.'</tr>'
			.'<tr>'
            . '<td rowspan="4" width="12%" align="center">'.$logo.'</td>'
            . '<td colspan="2" width="75%"><span style="font-size: 13px;" ><b>'.$company.'</b></span></td>'
			.'</tr>'
			.'<tr>'
			. '<td width="15%">&nbsp;&nbsp;<span>'.$img_srt.'</span>&nbsp;&nbsp;<span style="font-size: 12px;"><b>'.$branch_srt.'</b></span></td>'
			. '<td width="60%"><span><b>'.$address_srt.'</b></span></td>'
			.'</tr>'
			.'<tr>'
			. '<td></td>'
			. '<td></td>'
			.'</tr>'
			.'<tr>'
			. '<td></td>'
			. '<td></td>'
			.'</tr>'
            .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );
}else if($row['sub_branch']=="TIPTOP"){
$html = '<table border="0" color="#000033" cellpadding="1" cellspacing="0">'
		    .'<tr>'
		    .'<th align="center" width = "87%" style="font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ใบสมัครงาน</b></th>'
            . '<td rowspan="5" width="13%" align="center">'.$img_guest.'</td>'
			.'</tr>'
			.'<tr>'
            . '<td rowspan="4" width="12%" align="center">'.$logo.'</td>'
            . '<td colspan="2" width="75%"><span style="font-size: 13px;" ><b>'.$company.'</b></span></td>'
			.'</tr>'
			.'<tr>'
			. '<td width="15%">&nbsp;&nbsp;<span>'.$img_srt.'</span>&nbsp;&nbsp;<span style="font-size: 12px;"><b>'.$branch_srt.'</b></span></td>'
			. '<td width="60%"><span>'.$address_srt.'</span></td>'
			.'</tr>'
			.'<tr>'
			. '<td width="15%">&nbsp;&nbsp;<span>'.$img_hdy.'</span>&nbsp;&nbsp;<span style="font-size: 12px;"><b>'.$branch_hdy.'</b></span></td>'
			. '<td width="60%"><span>'.$address_hdy.'</span></td>'
			.'</tr>'
			.'<tr>'
			. '<td></td>'
			. '<td></td>'
			.'</tr>'
            .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );
}else if($row['sub_branch']=="THINKER" || $row['sub_branch']=="TRONGPAI TRONGMA" || $row['sub_branch']=="ORNATE LOGISTICS"){
$html = '<table border="0" color="#000033" cellpadding="1" cellspacing="0">'
		    .'<tr>'
		    .'<th align="center" width = "87%" style="font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ใบสมัครงาน</b></th>'
            . '<td rowspan="5" width="13%" align="center">'.$img_guest.'</td>'
			.'</tr>'
			.'<tr>'
            . '<td rowspan="4" width="12%" align="center">'.$logo.'</td>'
            . '<td colspan="2" width="75%"><span style="font-size: 13px;" ><b>'.$company.'</b></span></td>'
			.'</tr>'
			.'<tr>'
			. '<td width="15%">&nbsp;&nbsp;<span>'.$img_srt.'</span>&nbsp;&nbsp;<span style="font-size: 12px;"><b>'.$branch_srt.'</b></span></td>'
			. '<td width="60%"><span>'.$address_srt.'</span></td>'
			.'</tr>'
			.'<tr>'
			. '<td width="15%">&nbsp;&nbsp;<span>'.$img_chm.'</span>&nbsp;&nbsp;<span style="font-size: 12px;"><b>'.$branch_chm.'</b></span></td>'
			. '<td width="60%"><span>'.$address_chm.'</span></td>'
			.'</tr>'
			.'<tr>'
			. '<td></td>'
			. '<td></td>'
			.'</tr>'
            .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );
}

$pdf->Ln(1);

if (stripos($row['salary'], ',')) {$salary = $row['salary']." บาท";}else{ $salary = number_format($row['salary'])." บาท";}

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.8" style="font-size: 11.5px; color:#000033; width:100%;">'
    .'<tr >'
    .'<td style="width:15%; font-weight: bold; vertical-align: middle;">ตำแหน่งที่ต้องการสมัคร :</td>'
    .'<td style="width:27%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['job_name'].'</span></td>'
    .'<td style="width:12%; font-weight: bold;">เงินเดือนที่ต้องการ :</td>'
    .'<td style="width:9%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$salary.'</span></td>'
	.'<td style="width:7.5%; font-weight: bold;">วันที่สมัคร :</td>'
    .'<td style="width:9%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['date_create'].'</span></td>'
	.'<td style="width:11.5%; font-weight: bold;">วันที่พร้อมเริ่มงาน :</td>'
    .'<td style="width:8.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['work_date'].'</span></td>'
    .'</tr>'
    .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(2);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.8" style="font-size: 11.5px; color:#000033; width:100%;">'
    .'<tr>'
    .'<th bgcolor="#DCDCDC" style="font-size: 11.5px; text-align: center;"><b>ประวัติส่วนตัว</b></th>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:8.5%; font-weight: bold; vertical-align: middle;">ชื่อ-นามสกุล :</td>'
    .'<td style="width:26%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['title'].$row['name'].'</span></td>'
    .'<td style="width:5%; font-weight: bold;">ชื่อเล่น :</td>'
    .'<td style="width:11%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['nickname'].'</span></td>'
	.'<td style="width:3.5%; font-weight: bold;">เพศ :</td>'
    .'<td style="width:11%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['sex'].'</span></td>'
	.'<td style="width:3.5%; font-weight: bold;">อายุ :</td>'
    .'<td style="width:10%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['age'].'</span></td>'
    .'<td style="width:9.5%; font-weight: bold;">วันเดือนปี เกิด :</td>'
    .'<td style="width:10.5%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['birthday'].'</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:13%; font-weight: bold; vertical-align: middle;">เลขบัตรตัวประชาชน :</td>'
    .'<td style="width:19%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['idcard'].'</span></td>'
    .'<td style="width:8%; font-weight: bold;">วันออกบัตร :</td>'
    .'<td style="width:10%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['create_date'].'</span></td>'
	.'<td style="width:8%; font-weight: bold;">วันหมดอายุ :</td>'
    .'<td style="width:10%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['expiry_date'].'</span></td>'
	.'<td style="width:5.5%; font-weight: bold;">ส่วนสูง :</td>'
    .'<td style="width:10%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['height'].' ซม.</span></td>'
    .'<td style="width:5.5%; font-weight: bold;">น้ำหนัก :</td>'
    .'<td style="width:9.5%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['weight'].' กก.</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:6%; font-weight: bold; vertical-align: middle;">สัญชาติ :</td>'
    .'<td style="width:11%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['nationality'].'</span></td>'
    .'<td style="width:5.5%; font-weight: bold;">ศาสนา :</td>'
    .'<td style="width:11%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['religion'].'</span></td>'
	.'<td style="width:9.5%; font-weight: bold;">โทรศัพท์มือถือ :</td>'
    .'<td style="width:11%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['phone'].'</span></td>'
	.'<td style="width:6%; font-weight: bold;">Line ID :</td>'
    .'<td style="width:11%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['line_id'].'</span></td>'
    .'<td style="width:5.5%; font-weight: bold;">E-Mail :</td>'
    .'<td style="width:22%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['email'].'</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:8%; font-weight: bold; vertical-align: middle;">ที่อยู่ปัจจุบัน :</td>'
    .'<td style="width:91.5%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['address'].'</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:7%; font-weight: bold; vertical-align: middle;">สถานภาพ :</td>'
    .'<td style="width:13%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['status'].'</span></td>'
    .'<td style="width:12.5%; font-weight: bold;">สถานภาพทางทหาร :</td>'
    .'<td style="width:16%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['military'].'</span></td>'
	.'<td style="width:9%; font-weight: bold;">โรคประจำตัว :</td>'
    .'<td style="width:16%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['congenital_disease'].'</span></td>'
	.'<td style="width:9.5%; font-weight: bold;">อวัยวะที่พิการ :</td>'
    .'<td style="width:15.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['disabled'].'</span></td>'
	.'</tr>'
    .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(2);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.7" style="font-size: 11.5px; color:#000033; width:100%;">'
    .'<tr>'
    .'<th bgcolor="#DCDCDC" style="font-size: 11.5px; text-align: center;"><b>ประวัติการศึกษา</b></th>'
    .'</tr>'
	.'<tr>'
    .'<td style="font-size: 11px; text-align: left;"><b><u>การศึกษาสูงสุด</b></b></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:9.5%; font-weight: bold; vertical-align: middle;">ชื่อสถานศึกษา :</td>'
    .'<td style="width:42.5%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['u_school'].'</span></td>'
    .'<td style="width:10.5%; font-weight: bold;">ปีการศึกษาที่จบ :</td>'
    .'<td style="width:15%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['u_year'].'</span></td>'
    .'<td style="width:7%; font-weight: bold;">เกรดเฉลี่ย :</td>'
    .'<td style="width:14.5%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['u_gpa'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:8.75%; font-weight: bold;">วุฒิการศึกษา :</td>'
    .'<td style="width:40.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['u_educational'].'</span></td>'
	.'<td style="width:9.5%; font-weight: bold;">สาขา/วิชาเอก :</td>'
    .'<td style="width:40.25%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['u_major'].'</span></td>'
	.'</tr>'
	.'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(0.5);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.7" style="font-size: 11.5px; color:#000033; width:100%;">'
	.'<tr>'
    .'<td style="font-size: 11px; text-align: left;"><b><u>การศึกษาก่อนหน้า</b></b></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:9.5%; font-weight: bold; vertical-align: middle;">ชื่อสถานศึกษา :</td>'
    .'<td style="width:42.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['v_school'].'</span></td>'
    .'<td style="width:10.5%; font-weight: bold;">ปีการศึกษาที่จบ :</td>'
    .'<td style="width:15%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['v_year'].'</span></td>'
    .'<td style="width:7%; font-weight: bold;">เกรดเฉลี่ย :</td>'
    .'<td style="width:14.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['v_gpa'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:8.75%; font-weight: bold;">วุฒิการศึกษา :</td>'
    .'<td style="width:40.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['v_educational'].'</span></td>'
	.'<td style="width:9.5%; font-weight: bold;">สาขา/วิชาเอก :</td>'
    .'<td style="width:40.25%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['v_major'].'</span></td>'
	.'</tr>'
    .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(2);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.7" style="font-size: 11.5px; color:#000033; width:100%;">'
    .'<tr>'
    .'<th bgcolor="#DCDCDC" style="font-size: 11.5px; text-align: center;"><b>ประวัติการทำงาน</b></th>'
    .'</tr>'
	.'<tr>'
    .'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>การทำงานที่ 1</b></b></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:11%; font-weight: bold; vertical-align: middle;">ชื่อสถานที่ทำงาน :</td>'
    .'<td style="width:24.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['company_1'].'</span></td>'
    .'<td style="width:6%; font-weight: bold;">ตำแหน่ง :</td>'
    .'<td style="width:16%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['position_1'].'</span></td>'
    .'<td style="width:6.25%; font-weight: bold;">จากวันที่ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['datestart_1'].'</span></td>'
	.'<td style="width:5.25%; font-weight: bold;">ถึงวันที่ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['dateend_1'].'</span></td>'
	.'<td style="width:6.25%; font-weight: bold;">เงินเดือน :</td>'
    .'<td style="width:7.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['salary_1'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:11.75%; font-weight: bold;">ลักษณะงานโดยย่อ :</td>'
    .'<td style="width:38.25%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['detail_work_1'].'</span></td>'
	.'<td style="width:9.75%; font-weight: bold;">สาเหตุที่ลาออก :</td>'
    .'<td style="width:39.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['remark_leave_1'].'</span></td>'
	.'</tr>'
    .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

//$pdf->Ln(0.5);

$html = '<table border ="0" valign="middle" cellpadding="0.9" cellspacing="0.7" style="font-size: 11.5px; color:#000033; width:100%;">'
	.'<tr>'
	.'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>การทำงานที่ 2</b></b></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:11%; font-weight: bold; vertical-align: middle;">ชื่อสถานที่ทำงาน :</td>'
    .'<td style="width:24.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['company_2'].'</span></td>'
    .'<td style="width:6%; font-weight: bold;">ตำแหน่ง :</td>'
    .'<td style="width:16%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['position_2'].'</span></td>'
    .'<td style="width:6.25%; font-weight: bold;">จากวันที่ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['datestart_2'].'</span></td>'
	.'<td style="width:5.25%; font-weight: bold;">ถึงวันที่ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['dateend_2'].'</span></td>'
	.'<td style="width:6.25%; font-weight: bold;">เงินเดือน :</td>'
    .'<td style="width:7.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['salary_2'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:11.75%; font-weight: bold;">ลักษณะงานโดยย่อ :</td>'
    .'<td style="width:38.25%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['detail_work_2'].'</span></td>'
	.'<td style="width:9.75%; font-weight: bold;">สาเหตุที่ลาออก :</td>'
    .'<td style="width:39.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['remark_leave_2'].'</span></td>'
	.'</tr>'
    .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

//$pdf->Ln(0.5);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.7" style="font-size: 11.5px; color:#000033; width:100%;">'
	.'<tr>'
	.'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>การทำงานที่ 3</b></b></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:11%; font-weight: bold; vertical-align: middle;">ชื่อสถานที่ทำงาน :</td>'
    .'<td style="width:24.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['company_3'].'</span></td>'
    .'<td style="width:6%; font-weight: bold;">ตำแหน่ง :</td>'
    .'<td style="width:16%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['position_3'].'</span></td>'
    .'<td style="width:6.25%; font-weight: bold;">จากวันที่ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['datestart_3'].'</span></td>'
	.'<td style="width:5.25%; font-weight: bold;">ถึงวันที่ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['dateend_3'].'</span></td>'
	.'<td style="width:6.25%; font-weight: bold;">เงินเดือน :</td>'
    .'<td style="width:7.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['salary_3'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:11.75%; font-weight: bold;">ลักษณะงานโดยย่อ :</td>'
    .'<td style="width:38.25%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['detail_work_3'].'</span></td>'
	.'<td style="width:9.75%; font-weight: bold;">สาเหตุที่ลาออก :</td>'
    .'<td style="width:39.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['remark_leave_3'].'</span></td>'
	.'</tr>'
    .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

//$pdf->Ln(0.5);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.7" style="font-size: 11.5px; color:#000033; width:100%;">'
	.'<tr>'
	.'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>การทำงานที่ 4</b></b></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:11%; font-weight: bold; vertical-align: middle;">ชื่อสถานที่ทำงาน :</td>'
    .'<td style="width:24.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['company_4'].'</span></td>'
    .'<td style="width:6%; font-weight: bold;">ตำแหน่ง :</td>'
    .'<td style="width:16%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['position_4'].'</span></td>'
    .'<td style="width:6.25%; font-weight: bold;">จากวันที่ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['datestart_4'].'</span></td>'
	.'<td style="width:5.25%; font-weight: bold;">ถึงวันที่ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['dateend_4'].'</span></td>'
	.'<td style="width:6.25%; font-weight: bold;">เงินเดือน :</td>'
    .'<td style="width:7.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['salary_4'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:11.75%; font-weight: bold;">ลักษณะงานโดยย่อ :</td>'
    .'<td style="width:38.25%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['detail_work_4'].'</span></td>'
	.'<td style="width:9.75%; font-weight: bold;">สาเหตุที่ลาออก :</td>'
    .'<td style="width:39.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['remark_leave_4'].'</span></td>'
	.'</tr>'
    .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(2);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.8" style="font-size: 11.5px; color:#000033; width:100%;">'
    .'<tr>'
    .'<th bgcolor="#DCDCDC" style="font-size: 11.5px; text-align: center;"><b>ประวัติครอบครัว</b></th>'
    .'</tr>'
	.'<tr>'
	.'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>ข้อมูลบิดา</b></b></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:8.5%; font-weight: bold; vertical-align: middle;">ชื่อ-นามสกุล :</td>'
    .'<td style="width:30.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['father_name'].'</span></td>'
    .'<td style="width:4%; font-weight: bold;">อายุ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['father_age'].'</span></td>'
	.'<td style="width:5%; font-weight: bold;">อาชีพ :</td>'
    .'<td style="width:18%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['father_occupation'].'</span></td>'
    .'<td style="width:10%; font-weight: bold;">เบอร์โทรติดต่อ :</td>'
    .'<td style="width:15%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['father_talephone'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:9.5%; font-weight: bold;">สถานที่ทำงาน :</td>'
    .'<td style="width:64.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['father_Place_work'].'</span></td>'
	.'<td style="width:5.5%; font-weight: bold;">สถานะ :</td>'
    .'<td style="width:19.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['father_status'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>ข้อมูลมารดา</b></b></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:8.5%; font-weight: bold; vertical-align: middle;">ชื่อ-นามสกุล :</td>'
    .'<td style="width:30.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['mother_name'].'</span></td>'
    .'<td style="width:4%; font-weight: bold;">อายุ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['mother_age'].'</span></td>'
	.'<td style="width:5%; font-weight: bold;">อาชีพ :</td>'
    .'<td style="width:18%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['mother_occupation'].'</span></td>'
    .'<td style="width:10%; font-weight: bold;">เบอร์โทรติดต่อ :</td>'
    .'<td style="width:15%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['mother_talephone'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:9.5%; font-weight: bold;">สถานที่ทำงาน :</td>'
    .'<td style="width:64.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['mother_Place_work'].'</span></td>'
	.'<td style="width:5.5%; font-weight: bold;">สถานะ :</td>'
    .'<td style="width:19.5%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['mother_status'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>ข้อมูลคู่สมรส</b></b></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:8.5%; font-weight: bold; vertical-align: middle;">ชื่อ-นามสกุล :</td>'
    .'<td style="width:30.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['spouse_name'].'</span></td>'
    .'<td style="width:4%; font-weight: bold;">อายุ :</td>'
    .'<td style="width:8%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['spouse_age'].'</span></td>'
	.'<td style="width:5%; font-weight: bold;">อาชีพ :</td>'
    .'<td style="width:18%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['spouse_occupation'].'</span></td>'
    .'<td style="width:10%; font-weight: bold;">เบอร์โทรติดต่อ :</td>'
    .'<td style="width:15%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['spouse_talephone'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:9.5%; font-weight: bold;">สถานที่ทำงาน :</td>'
    .'<td style="width:64.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['spouse_Place_work'].'</span></td>'
	.'<td style="width:8.5%; font-weight: bold;">จำนวนบุตร :</td>'
    .'<td style="width:16.5%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['children'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:19.25%; font-weight: bold;">จำนวนพี่น้องทั้งหมดรวมตัวท่าน :</td>'
    .'<td style="width:31.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['num_bro_sis'].' คน</span></td>'
	.'<td style="width:11.2%; font-weight: bold;">ท่านเป็นบุตรคนที่ :</td>'
    .'<td style="width:37%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['num_sir'].'</span></td>'
	.'</tr>'
    .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

//ขึ้นหน้าใหม่
$pdf->Addpage ();

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.7" style="font-size: 11.5px; color:#000033; width:100%;">'
    .'<tr>'
    .'<th bgcolor="#DCDCDC" style="font-size: 11.5px; text-align: center;"><b>ทักษะ และ ความสามารถ</b></th>'
    .'</tr>'
	.'<tr>'
    .'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>ทักษะด้านภาษาไทย</b></b></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:5%; font-weight: bold; vertical-align: middle;">การฟัง :</td>'
    .'<td style="width:19.1%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['thai_listen'].'</span></td>'
    .'<td style="width:5.25%; font-weight: bold;">การพูด :</td>'
    .'<td style="width:19.1%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['thai_speak'].'</span></td>'
	.'<td style="width:5.75%; font-weight: bold;">การอ่าน :</td>'
    .'<td style="width:19.1%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['thai_read'].'</span></td>'
	.'<td style="width:6.5%; font-weight: bold;">การเขียน :</td>'
    .'<td style="width:19%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['thai_write'].'</span></td>'
	.'</tr>'
	.'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(0.5);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.9" style="font-size: 11.5px; color:#000033; width:100%;">'
	.'<tr>'
    .'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>ทักษะด้านภาษาอังกฤษ</b></b></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:5%; font-weight: bold; vertical-align: middle;">การฟัง :</td>'
    .'<td style="width:19.1%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['eng_listen'].'</span></td>'
    .'<td style="width:5.25%; font-weight: bold;">การพูด :</td>'
    .'<td style="width:19.1%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['eng_speak'].'</span></td>'
	.'<td style="width:5.75%; font-weight: bold;">การอ่าน :</td>'
    .'<td style="width:19.1%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['eng_read'].'</span></td>'
	.'<td style="width:6.5%; font-weight: bold;">การเขียน :</td>'
    .'<td style="width:19%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['eng_write'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:7%; font-weight: bold;">ภาษาอื่นๆ :</td>'
    .'<td style="width:33%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['other_languages'].'</span></td>'
	.'<td style="width:18.5%; font-weight: bold;">ความสามารถพิเศษ/งานอดิเรก :</td>'
    .'<td style="width:40.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['talent_skill'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:17.25%; font-weight: bold;">การใช้โปรแกรมคอมพิวเตอร์ :</td>'
    .'<td style="width:82.25%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['computer_skill'].'</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:25%; font-weight: bold; vertical-align: middle;">ท่านทราบข้อมูลสมัครงานทางช่องทางไหน :</td>'
    .'<td style="width:74.5%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['news'].'</span></td>'
    .'</tr>'
	.'<tr>'
	.'<td style="width:12.5%; font-weight: bold;">ท่านสามารถขับขี่รถ :</td>'
    .'<td style="width:36.5%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['driver'].'</span></td>'
	.'<td style="width:13.25%; font-weight: bold;">ท่านมีใบอนุญาตขับขี่ :</td>'
    .'<td style="width:37%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['drive_license'].'</span></td>'
	.'</tr>'
    .'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(2);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.7" style="font-size: 11.5px; color:#000033; width:100%;">'
	.'<tr>'
	.'<th bgcolor="#DCDCDC" style="font-size: 11.5px; text-align: center;"><b>บุคคลอ้างอิง</b></th>'
	.'</tr>'
	.'<tr>'
    .'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>บุคคลที่ท่านรู้จักที่ทำงานในบริษัทฯนี้</b></b></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:8.25%; font-weight: bold; vertical-align: middle;">ชื่อ-นามสกุล :</td>'
    .'<td style="width:30.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['person_name'].'</span></td>'
    .'<td style="width:8.25%; font-weight: bold;">ตำแหน่งงาน :</td>'
    .'<td style="width:22.5%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['person_position'].'</span></td>'
	.'<td style="width:8.75%; font-weight: bold;">ความสัมพันธ์ :</td>'
    .'<td style="width:20.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['person_relations'].'</span></td>'
	.'</tr>'
	.'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(0.5);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.8" style="font-size: 11.5px; color:#000033; width:100%;">'
	.'<tr>'
    .'<td style="font-size: 11px; text-align: left; width:100%;"><b><u>บุคคลอ้างอิงรับรองการทำงาน หรือ ความสามารถ</b></b></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:8.25%; font-weight: bold; vertical-align: middle;">ชื่อ-นามสกุล :</td>'
    .'<td style="width:30.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['person_referen_name'].'</span></td>'
    .'<td style="width:8.25%; font-weight: bold;">ตำแหน่งงาน :</td>'
    .'<td style="width:22.5%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['person_referen_position'].'</span></td>'
	.'<td style="width:9%; font-weight: bold;">เบอร์โทรศัพท์ :</td>'
    .'<td style="width:20.75%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['person_referen_phone'].'</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:15%; font-weight: bold; vertical-align: middle;">ที่อยู่ หรือ สถานที่ทำงาน :</td>'
    .'<td style="width:54.5%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['person_referen_address'].'</span></td>'
	.'<td style="width:8.75%; font-weight: bold;">ความสัมพันธ์ :</td>'
    .'<td style="width:21%; color: MediumBlue; font-size: 8px; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['person_referen_relations'].'</span></td>'
	.'</tr>'
	.'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(2);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.78" style="font-size: 11.5px; color:#000033; width:100%;">'
	.'<tr>'
	.'<th bgcolor="#DCDCDC" style="font-size: 11.5px; text-align: center;"><b>ข้อมูลอื่นๆ</b></th>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:73.25%; font-weight: bold; vertical-align: middle;">&#8226; ท่านจะยินดีให้เราสอบถามไปยังบริษัทที่ท่านทำงานอยู่ในขณะนี้หรือเคยทำงาน ตรวจสอบคุณวุฒิและคุณสมบัติของท่านได้หรือไม่ :</td>'
    .'<td style="width:26.5%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['imformation'].'</span></td>'
    .'</tr>'
	.'<tr>'
	.'<td style="width:48.75%; font-weight: bold; vertical-align: middle;">&#8226; ท่านเคยถูกจับ หรือต้องโทษทางคดีอาญาและได้คำพิพากษาให้ได้รับโทษจำคุกหรือไม่ :</td>'
    .'<td style="width:51%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['penalize'].'</span></td>'
	.'</tr>'
	.'<tr>'
	.'<td style="width:42.75%; font-weight: bold; vertical-align: middle;">&#8226; ท่านเคยถูกไล่ออกจากงาน เนื่องจากความประพฤติและงานไม่ดีพอหรือไม่ :</td>'
    .'<td style="width:57%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['dismiss'].'</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:60.75%; font-weight: bold; vertical-align: middle;">&#8226; ท่านได้รับเงินรายได้จากทางอื่น เช่น เบี้ยหวัด เงินบำเหน็จ หรือเงินค่าตอบแทนจากการเจ็บป่วยบ้างหรือไม่ :</td>'
    .'<td style="width:39%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['income_other'].'</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:66.5%; font-weight: bold; vertical-align: middle;">&#8226; ในขณะนี้ท่านได้เจ็บป่วย เป็นโรคเรื้อรัง หรือหย่อนสมรรถภาพอื่นๆ ทางร่างกายและอยู่ในความดูแลของแพทย์หรือไม่ :</td>'
    .'<td style="width:33.25%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['health'].'</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:94.20%; font-weight: bold; vertical-align: middle;">&#8226; ถ้าท่านได้รับพิจารณาให้บรรจุเป็นพนักงานของบริษัทฯ ท่านจะยินยอมให้บริษัทฯ ย้ายตำแหน่งหน้าที่ตามความเหมาะสมโดยไม่ลดเงินค้าจ้าง และผลประโยชน์ได้หรือไม่ :</td>'
    .'<td style="width:5.5%; color: MediumBlue; font-size: 8px; vertical-align: middle; border-bottom: 0.1em dotted gray; text-align: left;"><span style="font-size: 11px;">&nbsp;'.$row['move_job'].'</span></td>'
	.'</tr>'
	.'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(5);

$html = '<table border ="0" valign="middle" cellpadding="1" cellspacing="0.8" style="font-size: 11.5px; color:#000033; width:100%;">'
	.'<tr>'
    .'<td style="width:100%; font-weight: none; vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้าพเจ้าขอให้คำรับรองว่า ข้อความที่ข้าพเจ้าได้กรอกไว้ในใบสมัครงานนี้ ถูกต้องและตรงต่อความเป็นจริงทุกประการ ถ้าเมื่อใดหรือเวลาใดๆ ก็ตาม ความปรากฎว่าไม่ถูกต้องหรือไม่ตรง<br>ต่อความเป็นจริงตามที่ข้าพเจ้าได้ให้ไว้ในใบสมัครนี้ ข้าพเจ้ายินดีให้ทางบริษัทฯ เลิกจ้างได้ทันที หรือจัดการได้ตามแต่จะเห็นสมควร โดยข้าพเจ้าจะไม่เรียกร้องสิทธิใดๆ จากบริษัทฯ</td>'
    .'</tr>'
	.'<tr>'
    .'<td></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:68%;"></td>'
	.'<td style="width:32%; text-align: center;"><b>ลงชื่อ</b><span style="color:gray;">...........................................................</span><b>(ผู้สมัครงาน)</b></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:68%;"></td>'
	.'<td style="width:32%; text-align: center;">( '.$row['title'].$row['name'].' )&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>'
    .'</tr>'
	.'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->Ln(25);

$html = '
<table border ="0" valign="middle" cellpadding="0.9" cellspacing="0.9" style="font-size: 11.5px; color:#000033; width:100%;">'
	.'<tr>'
    .'<td style="width:50%; font-weight: bold; vertical-align: middle; border-top: 0.1em solid #000033; border-left: 0.1em solid #000033;">&nbsp;&nbsp;ใบสมัครผ่านการตรวจโดย</td>'
	.'<td style="width:50%; font-weight: bold; vertical-align: middle; border-top: 0.1em solid #000033; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033;">&nbsp;&nbsp;สำหรับเจ้าหน้าที่</td>'
    .'</tr>'
	.'<tr>'
	.'<td style="width:50%; border-left: 0.1em solid #000033;"></td>'
	.'<td style="width:50%; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033;"></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033; border-bottom: 0.1em solid #000033;">&nbsp;&nbsp;<b>ลงชื่อ</b> <span style="color:gray;">....................................................................</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>วันที่</b> <span style="color:gray;">.............</span>/<span style="color:gray;">.............</span>/<span style="color:gray;">.............</span></td>'
	.'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033;"></td>'
    .'</tr>'
	.'<tr>'
	.'<td style="width:50%; height: 5px; border-left: 0.1em solid #000033;"></td>'
	.'<td style="width:50%; height: 5px; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033;"></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033; border-bottom: 0.1em solid #000033;">&nbsp;&nbsp;<b>ความคิดเห็น</b> <span style="color:gray;">........................................................................................................................</span></td>'
	.'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033; border-bottom: 0.1em solid #000033;"></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ฝ่ายบุคคล</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="hrcheck" value="1" readonly="readonly">ให้สัมภาษณ์&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="hrcheck" value="2" readonly="readonly">เก็บเข้าแฟ้ม&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="hrcheck" value="3" readonly="readonly">ไม่ผ่าน</td>'
	.'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033;"></td>'
    .'</tr>'
	.'<tr>'
	.'<td style="width:50%; border-left: 0.1em solid #000033;"></td>'
	.'<td style="width:50%; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033;">&nbsp;&nbsp;บรรจุในตำแหน่ง <span style="color:gray;">.................................................................................................................</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033;">&nbsp;&nbsp;<b>ลงชื่อ</b> <span style="color:gray;">....................................................................</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>วันที่</b> <span style="color:gray;">.............</span>/<span style="color:gray;">.............</span>/<span style="color:gray;">.............</span></td>'
	.'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033;">&nbsp;&nbsp;เงินเดือน หรือ จ่ายรายวัน <span style="color:gray;">..................................................................................................</span></td>'
    .'</tr>'
	.'<tr>'
    .'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033; border-top: 0.1em solid #000033;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ผู้จัดการแผนก</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="mgcheck" value="1" readonly="readonly">สมควรจ้าง&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="mgcheck" value="2" readonly="readonly">ไม่เห็นสมควรจ้าง</td>'
	.'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033;">&nbsp;&nbsp;วันที่เริ่มงาน <span style="color:gray;">.........................................................................................................................</span></td>'
    .'</tr>'
	.'<tr>'
	.'<td style="width:50%; border-left: 0.1em solid #000033;"></td>'
	.'<td style="width:50%; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033;">&nbsp;&nbsp;หมายเหต <span style="color:gray;">.............................................................................................................................</span></td>'
	.'</tr>'
	.'<tr>'
    .'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033; border-bottom: 0.1em solid #000033;">&nbsp;&nbsp;<b>ลงชื่อ</b> <span style="color:gray;">....................................................................</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>วันที่</b> <span style="color:gray;">.............</span>/<span style="color:gray;">.............</span>/<span style="color:gray;">.............</span></td>'
	.'<td style="width:50%; vertical-align: middle; border-left: 0.1em solid #000033; border-right: 0.1em solid #000033; border-bottom: 0.1em solid #000033;">&nbsp;&nbsp;<b>ลงชื่อ</b> <span style="color:gray;">....................................................................</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>วันที่</b> <span style="color:gray;">.............</span>/<span style="color:gray;">.............</span>/<span style="color:gray;">.............</span></td>'
    .'</tr>'
	.'</table>';
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->writeHTML($html, '' );

$pdf->SetMargins(15, 10, 15, true);
$pdf->AddPage();
$html = '<table border="0"  cellpadding="1" cellspacing="0.9" style="color:#000033; width:100%;">'
		    .'<tr>'   
            . '<th align="center" style="font-size: 15px;"><b>ข้อมูลเพิ่มเติม</b></th>'
			.'</tr>'
			.'<tr>'   
            . '<td align="center">ชื่อ-นามสกุล : <font color="MediumBlue">'.$row['title'].$row['name'].'</font></td>'
			.'</tr>'
			.'<tr><td><hr></td></tr>'
			.'<tr>'   
            . '<td><b>ให้ยกตัวอย่างสั้นๆ จากประสบการณ์ของคุณ ซึ่งจะช่วยให้เราเข้าใจความสามารถของคุณดีขึ้น</b></td>'
			.'</tr>'
			.'<tr>'   
            . '<td><b>1.อธิบายตัวอย่างที่ท่านตั้งเป้าหมายไว้สูง และฝ่าฟันอุปสรรคต่างๆ จนประสบความสำเร็จ</b></td>'
			.'</tr>'
			.'<tr>'   
            . '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="MediumBlue">'.$row['more_infor1'].'</font></td>'
			.'</tr>'
			.'<tr>'   
            . '<td></td>'
			.'</tr>'
			.'<tr>'   
            . '<td><b>2.สรุปสถานการณ์ที่ท่านเสนอความคิดริเริ่มให้คนอื่นทำงานที่สำคัญ และสามารถเป็นผู้นำให้ทำงานนั้นออกมาประสบความสำเร็จ ตามที่ต้องการ</b></td>'
			.'</tr>'
			.'<tr>'   
            . '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="MediumBlue">'.$row['more_infor2'].'</font></td>'
			.'</tr>'
			.'<tr>'   
            . '<td></td>'
			.'</tr>'
			.'<tr>'   
            . '<td><b>3.อธิบายสถานการณ์ของการแก้ปัญหา โดยที่ท่านต้องค้นหาข้อมูลที่เกี่ยวข้อง แยกแยะประเด็นสำคัญและตัดสินใจว่าจะทำอย่างไร เพื่อให้ได้ผลที่ต้องการ</b></td>'
			.'</tr>'
			.'<tr>'   
            . '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="MediumBlue">'.$row['more_infor3'].'</font></td>'
			.'</tr>'
			.'<tr>'   
            . '<td></td>'
			.'</tr>'
			.'<tr>'   
            . '<td><b>4.อธิบายถึงสถานการณ์ที่ท่านนำมาเสนอ/ชี้แจง โดยใช้ข้อมูลที่เป็นจริงอย่างมีประสิทธิภาพในการทำงานให้คนอื่นเห็นด้วย</b></td>'
			.'</tr>'
			.'<tr>'   
            . '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="MediumBlue">'.$row['more_infor4'].'</font></td>'
			.'</tr>'
			.'<tr>'   
            . '<td></td>'
			.'</tr>'
			.'<tr>'   
            . '<td><b>5.ยกตัวอย่างสถานการณ์ที่ท่านได้ทำงานร่วมกับคนอื่นอย่างมีประสิทธิภาพ เพื่อให้ประสบความสำเร็จในงานสำคัญ</b></td>'
			.'</tr>'
			.'<tr>'   
            . '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="MediumBlue">'.$row['more_infor5'].'</font></td>'
			.'</tr>'
			.'<tr>'   
            . '<td></td>'
			.'</tr>'
			.'<tr>'   
            . '<td><b>6.อธิบายถึงความคิดสร้างสรรค์ที่ท่านได้คิดขึ้นมา และมีส่วนสำคัญในการสนับสนุนให้กิจกรรมหรือโครงการนั้นประสบความสำเร็จ</b></td>'
			.'</tr>'
			.'<tr>'   
            . '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="MediumBlue">'.$row['more_infor6'].'</font></td>'
			.'</tr>'
			.'<tr>'   
            . '<td></td>'
			.'</tr>'
            .'</table>';
$pdf->SetFont('thsarabunnew', '', 13.5);
$pdf->writeHTML($html, '' );

$pdf->Output('ORNJOB_'.$guest_no.'.pdf', 'I');