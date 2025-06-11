<?php
// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// Query ข้อมูลจากฐานข้อมูล (ตัวอย่างการดึง job_no และ link)
$sql = "select *,format(annoudate,'dd/MM/')+convert(varchar,year(annoudate)+543) as post_date,convert(varchar,annoudate+30,101) as date_over,
		  convert(varchar,getdate(),101) as date_today,branch from jobs_require where job_status='Y' order by annoudate desc";
          $params = array();
          $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
          $Query = sqlsrv_query($conn,$sql, $params, $options);
          $numRows = sqlsrv_num_rows( $Query );

$links = array();

if($numRows > 0){
    while($row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC)){
        $links[] = "https://ornwebapp.com/ornjob/job_detail.php?job_no=" . $row['job_no'];
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Multiple QR Codes from SQL</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .qrcode-container {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .qrcode-item {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>QR Codes for Job Details</h1>
        <div id="qrcode-container" class="qrcode-container"></div>
    </div>

    <script>
        window.onload = function() {
            // ดึงข้อมูลลิงก์จาก PHP ที่ส่งออกมาเป็น JSON
            var links = <?php echo json_encode($links); ?>;
            
            var container = document.getElementById('qrcode-container');

            // วนลูปเพื่อสร้าง QR Code สำหรับแต่ละลิงก์
            links.forEach(function(link, index) {
                // สร้าง div สำหรับแต่ละ QR Code
                var qrcodeDiv = document.createElement('div');
                qrcodeDiv.classList.add('qrcode-item');
                
                // เพิ่มข้อความใต้ QR Code
                var linkLabel = document.createElement('p');
                linkLabel.textContent = "QR Code for Job No: " + link.split('job_no=')[1];

                // สร้าง div สำหรับ QR Code
                var qrcode = document.createElement('div');
                
                // ใส่ div ทั้งหมดเข้ากับ container
                qrcodeDiv.appendChild(linkLabel);
                qrcodeDiv.appendChild(qrcode);
                container.appendChild(qrcodeDiv);
                
                // สร้าง QR Code
                new QRCode(qrcode, {
                    text: link,
                    width: 200,
                    height: 200
                });
            });
        }
    </script>
</body>
</html>
