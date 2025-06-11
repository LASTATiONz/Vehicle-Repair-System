
<?php

    if($guest_no != ""){
        //Line notify
        $strSQL1="select * from resume_job where guest_no = '".$guest_no."'";
                
        $params1 = array();
        $options1 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $Query1 = sqlsrv_query($conn,$strSQL1, $params1, $options1);
        $numRows1 = sqlsrv_num_rows( $Query1 );
        $row1 = sqlsrv_fetch_array($Query1, SQLSRV_FETCH_ASSOC);


        //Line notify
        $sMessage = "\nผู้สมัครเลขที่ :\n".$guest_no."\nชื่อ-นามสกุล :\n".$row1["title"].$row1['name']."\nสมัครงานตำแหน่ง :\n".$row1['job_name']."\n**ได้ส่งใบสมัครงานเข้าระบบแล้ว**";

        if($branch == 'SRT' ){
        $sToken = "rVLNZ1vLb6OdocAiJwnvpWjsi9SyrvHgE1kwnAj6sDC"; //เทส
        //  $sToken = "OxYHURPIyR9okVYiuNbGgPcH1XwEkR7DY5eOZdtMBOb"; //หลัก
        }else if($branch == 'HDY'){
        $sToken = "rVLNZ1vLb6OdocAiJwnvpWjsi9SyrvHgE1kwnAj6sDC"; //เทส
        //  $sToken = "hUebwbBTsUVCP1D3kwnNm8oHZBbFFZ0sK1050pqm4gN"; //หลัก
        }else if($branch == 'CHM'){
        $sToken = "rVLNZ1vLb6OdocAiJwnvpWjsi9SyrvHgE1kwnAj6sDC"; //เทส
        //  $sToken = "D9Fc33WL15Vt8yAgD0YGalagLyXG6IlAm7jFrrXK5M6"; //หลัก
        }else{
        $sToken = "rVLNZ1vLb6OdocAiJwnvpWjsi9SyrvHgE1kwnAj6sDC"; //เทส
        }

        $chOne = curl_init(); 
        curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt( $chOne, CURLOPT_POST, 1); 
        curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
        $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
        curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec( $chOne ); 

        //Result error 
        if(curl_error($chOne)) { 
            echo 'error:' . curl_error($chOne); 
            write_log("❌ Line Notify Error: " . curl_error($ch));

        } 
        else { 
            $result_ = json_decode($result, true); 
            write_log("📱 ส่ง Line Notify สำเร็จสำหรับ Guest No: $guest_no");

        } 
        curl_close( $chOne ); 
    }else{} 
    //End Line notify
?>