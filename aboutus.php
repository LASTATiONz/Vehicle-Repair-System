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
?>

</div>
<div class="container mt-5 mb-4"> 
<!--Div Content-->
    <div class="section-header"><i class="fa-regular fa-handshake fa-lg"></i> เกี่ยวกับเรา</div>
    <div class="section-body">
        <div align="center">
          <img src="images/pg.jpg" alt="product_pg" style="max-width: 30%; max-height: 40%; display: block;"><br>
        </div>
        <span><p class="data">บริษัท ออร์เนท เอสดีโอ จำกัด เป็นตัวแทนกระจายสินค้าของบริษัท พรอคเตอร์ แอนด์ แกมเบิล แมนูแฟคเจอริ่ง (ประเทศไทย) จำกัด หรือที่รู้จักกันในนาม P&G 
          ซึ่งเป็นบริษัทที่ผลิตสินค้าคอนซูมเมอร์สัญชาติอเมริกันมานานกว่า 165 ปี ที่มีขนาดใหญ่ที่สุดรายหนึ่งของโลก โดยบริษัทออร์เนท เอสดีโอ จำกัด  </p></span>
        <hr class="style_hr1">
        
        <h1 class="centered-text">การกระจายสินค้า</h1> <br>
        <div align="center">
            <img src="images/site.jpg" alt="site" style="max-width: 30%; max-height: 40%; display: block;"><br>
        </div>
        <span><p class="data"> ครอบคลุมพื้นที่การกระจายสินค้าทั้งภาคใต้ 14 จังหวัด  (กระบี่, ชุมพร, นครศรีธรรมราช, พังงา, ภูเก็ต, ระนอง, สุราษฎร์ธานี,ตรัง, นราธิวาส, ปัตตานี, พัทลุง, ยะลา, สงขลา, สตูล) 
        <br>ภาคเหนือ 8 จังหวัด (เชียงราย, เชียงใหม่, น่าน, พะเยา, แพร่, แม่ฮ่องสอน, ลำปาง, ลำพูน)  </p></span>
        <hr class="style_hr1">
        
        <h1 class="centered-text">สินค้า</h1> 
        <br>
        <div align="center">
          <img src="images/product_pg.png" alt="product_pg" style="max-width: 100%; max-height: 100%; display: block;"><br>
        </div>
        <span><p class="data"> ผลิตภัณฑ์น้ำยาปรับผ้านุ่มและซักผ้าดาวน์นี่,ผลิตภัณฑ์ดูแลเส้นผม แพนทีน รีจอยส์ เฮดแอนด์โชว์เดอร์ และ เฮอร์เบิลเอสเซนส์, ผลิตภัณฑ์บำรุงผิวหน้าโอเลย์, ผลิตภัณฑ์ดูแลช่องปากออรัลบี, 
        ผลิตภัณฑ์น้ำหอมปรับอากาศแอมบิเพอร์, ผลิตภัณฑ์โกนหนวดยิลเลตต์, สบู่ก้อนเซฟการ์ด, ผ้าอนามัยวิสเปอร์ และ ผลิตภัณฑ์แก้หวัดคัดจมูกวิควาเปอร์รับ  เป็นต้น</p></span>

		</div>
<!--------------------------->
</div>  
</body>
</html>