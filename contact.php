<?php
session_start();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดต่อเรา - ORNATE SDO</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- jQuery (เลือกใช้แค่ตัวเดียว: เวอร์ชันล่าสุด) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Script -->
    <!-- <script src="js/script.js"></script> -->

    <!-- Favicon -->
    <link rel="icon" href="images/LOGO_ORNATE1.png" type="image/png">


    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, #0056b3 100%);
            padding: 80px 0 40px;
            color: white;
            text-align: center;
            margin-top: 0;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 300;
            opacity: 0.9;
        }

        .contact-card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
        }

        .contact-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .contact-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .contact-info {
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .contact-info i {
            width: 20px;
            color: var(--primary);
            margin-right: 8px;
        }

        .contact-info a {
            color: var(--primary);
            text-decoration: none;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        @media (max-width: 991px) {
            .hero-section {
                padding: 60px 0 30px;
                margin-top: 56px;
            }
            .hero-title {
                font-size: 2.2rem;
            }
            .hero-subtitle {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 40px 0 20px;
            }
            .hero-title {
                font-size: 1.8rem;
            }
            .hero-subtitle {
                font-size: 1rem;
            }
            .contact-card {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                padding: 30px 0 20px;
            }
            .hero-title {
                font-size: 1.6rem;
            }
            .hero-subtitle {
                font-size: 0.9rem;
            }
        }
    </style>

</head>
<body>
    <?php include 'header.php'; ?>
    <!-- <br><br><br> -->

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">ติดต่อเรา</h1>
            <p class="hero-subtitle">ยินดีให้บริการทุกช่องทางการติดต่อ</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <!-- สำนักงานใหญ่ สุราษฎร์ธานี -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <img src="images/srt.jpg" alt="สำนักงานใหญ่ สุราษฎร์ธานี" class="contact-image">
                    <h3 class="contact-title">บริษัท ออร์เนท เอสดีโอ จำกัด (สำนักงานใหญ่)</h3>
                    <div class="contact-info">
                        <i class="fa-solid fa-house"></i>
                        31/237 ม.2 ถ.สุราษฎร์-นครศรีฯ ต.บางกุ้ง อ.เมือง จ.สุราษฎร์ธานี 84000
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-square-phone"></i>
                        077-281-972 หรือ 081-2712662 (HR)
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-envelope"></i>
                        <a href="mailto:hrsrt@ornategroup.com">hrsrt@ornategroup.com</a>
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-earth-americas"></i>
                        <a href="https://www.facebook.com/ornate.sdosrt" target="_blank">Facebook</a>
                    </div>
                    <div class="contact-info">
                        <i class="fa-brands fa-line"></i>
                        <a href="https://line.me/ti/p/NKzv6seCSM" target="_blank">Line ID</a>
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <a href="https://www.google.co.th/maps/place/%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%A9%E0%B8%B1%E0%B8%97+%E0%B8%AD%E0%B8%AD%E0%B8%A3%E0%B9%8C%E0%B9%80%E0%B8%99%E0%B8%97+%E0%B9%80%E0%B8%AD%E0%B8%AA%E0%B8%94%E0%B8%B5%E0%B9%82%E0%B8%AD+%E0%B8%88%E0%B8%B3%E0%B8%81%E0%B8%B1%E0%B8%94+%E0%B8%A8%E0%B8%B9%E0%B8%99%E0%B8%A2%E0%B9%8C%E0%B8%AA%E0%B8%B8%E0%B8%A3%E0%B8%B2%E0%B8%A9%E0%B8%8F%E0%B8%A3%E0%B9%8C%E0%B8%98%E0%B8%B2%E0%B8%99%E0%B8%B5/@9.1595136,99.360563,17z/data=!4m6!3m5!1s0x305405fe63c3492f:0x34e21dcc5898dede!8m2!3d9.1595136!4d99.3627517!16s%2Fg%2F11ddx243w4?entry=ttu&g_ep=EgoyMDI1MDMxMS4wIKXMDSoASAFQAw%3D%3D" target="_blank">ดูแผนที่</a>
                    </div>
                </div>
            </div>

            <!-- สาขาหาดใหญ่ -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <img src="images/hdy.jpg" alt="สาขาหาดใหญ่" class="contact-image">
                    <h3 class="contact-title">บริษัท ออร์เนท เอสดีโอ จำกัด (สาขาหาดใหญ่)</h3>
                    <div class="contact-info">
                        <i class="fa-solid fa-house"></i>
                        671 ม.3 ถ.เพชรเกษม ต.ควนลัง อ.หาดใหญ่ จ.สงขลา 90110
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-square-phone"></i>
                        074-552347 หรือ 081-3702882 (HR)
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-envelope"></i>
                        <a href="mailto:hr_hdy@ornategroup.com">hr_hdy@ornategroup.com</a>
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-earth-americas"></i>
                        <a href="https://www.facebook.com/ornate.sdohdy" target="_blank">Facebook</a>
                    </div>
                    <div class="contact-info">
                        <i class="fa-brands fa-line"></i>
                        <a href="https://line.me/ti/p/~hrornhdy" target="_blank">Line ID</a>
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <a href="https://www.google.co.th/maps/place/%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%A9%E0%B8%B1%E0%B8%97+%E0%B8%AD%E0%B8%AD%E0%B8%A3%E0%B9%8C%E0%B9%80%E0%B8%99%E0%B8%97+%E0%B9%80%E0%B8%AD%E0%B8%AA%E0%B8%94%E0%B8%B5%E0%B9%82%E0%B8%AD+%E0%B8%88%E0%B8%B3%E0%B8%81%E0%B8%B1%E0%B8%94+%E0%B8%AA%E0%B8%B2%E0%B8%82%E0%B8%B2%E0%B8%AB%E0%B8%B2%E0%B8%94%E0%B9%83%E0%B8%AB%E0%B8%8D%E0%B9%88/@6.9903796,100.4081579,17z/data=!3m1!4b1!4m6!3m5!1s0x304d27919d1b2453:0x44937d43bff635c0!8m2!3d6.9903743!4d100.4107328!16s%2Fg%2F11bxg5j1z1?entry=ttu&g_ep=EgoyMDI1MDMxMS4wIKXMDSoASAFQAw%3D%3D" target="_blank">ดูแผนที่</a>
                    </div>
                </div>
            </div>

            <!-- สาขาเชียงใหม่ -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <img src="images/CHM.jpg" alt="สาขาเชียงใหม่" class="contact-image">
                    <h3 class="contact-title">บริษัท ออร์เนท เอสดีโอ จำกัด (สาขาเชียงใหม่)</h3>
                    <div class="contact-info">
                        <i class="fa-solid fa-house"></i>
                        323 ม.1 ต.หนองหอย อ.เมือง จ.เชียงใหม่ 50000
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-square-phone"></i>
                        054-140-553 หรือ 081-3702662 (HR)
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-envelope"></i>
                        <a href="mailto:hrchm1@ornategroup.com">hrchm1@ornategroup.com</a>
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-earth-americas"></i>
                        <a href="https://www.facebook.com/ornate.sdo.7" target="_blank">Facebook</a>
                    </div>
                    <div class="contact-info">
                        <i class="fa-brands fa-line"></i>
                        <a href="https://line.me/ti/p/~0813702662" target="_blank">Line ID</a>
                    </div>
                    <div class="contact-info">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <a href="https://www.google.co.th/maps/place/%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%A9%E0%B8%B1%E0%B8%97+%E0%B8%AD%E0%B8%AD%E0%B8%A3%E0%B9%8C%E0%B9%80%E0%B8%99%E0%B8%97+%E0%B9%80%E0%B8%AD%E0%B8%AA%E0%B8%94%E0%B8%B5%E0%B9%82%E0%B8%AD+%E0%B8%88%E0%B8%B3%E0%B8%81%E0%B8%B1%E0%B8%94+%E0%B8%AA%E0%B8%B2%E0%B8%82%E0%B8%B2%E0%B9%80%E0%B8%8A%E0%B8%B5%E0%B8%A2%E0%B8%87%E0%B9%83%E0%B8%AB%E0%B8%A1%E0%B9%88/@18.7544024,99.0076211,19z/data=!3m1!4b1!4m6!3m5!1s0x30da31a42bd67cb5:0xa4a0a3a3e559623e!8m2!3d18.7544011!4d99.0082648!16s%2Fg%2F11n3462wg4?entry=ttu&g_ep=EgoyMDI1MDMxMS4wIKXMDSoASAFQAw%3D%3D" target="_blank">ดูแผนที่</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
define('BASE_PATH', dirname(__FILE__));
include 'footer.php';
?>

</body>
</html>