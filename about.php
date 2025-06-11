<?php
session_start();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เกี่ยวกับเรา - ORNATE SDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- <link href="styles.css" rel="stylesheet"> -->
    <link rel="icon" href="images/LOGO_ORNATE1.png" type="image/icon type">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="hero-content">
                    <h1 class="hero-title">ORNATE SDO</h1>
                    <p class="hero-subtitle">ตัวแทนกระจายสินค้าอย่างเป็นทางการของ P&G</p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="hero-image-wrapper">
                    <img src="images/pg.jpg" alt="P&G Products" class="hero-image">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <div class="icon-wrapper">
                <i class="fa-regular fa-handshake"></i>
            </div>
            <h2>เกี่ยวกับเรา</h2>
        </div>
        <div class="about-content">
            <div class="about-text" data-aos="fade-right">
                <p class="lead">บริษัท ออร์เนท เอสดีโอ จำกัด เป็นตัวแทนกระจายสินค้าของบริษัท พรอคเตอร์ แอนด์ แกมเบิล แมนูแฟคเจอริ่ง (ประเทศไทย) จำกัด หรือที่รู้จักกันในนาม P&G ซึ่งเป็นบริษัทที่ผลิตสินค้าคอนซูมเมอร์สัญชาติอเมริกันมานานกว่า 165 ปี ที่มีขนาดใหญ่ที่สุดรายหนึ่งของโลก</p>
            </div>
            <div class="about-image" data-aos="fade-left">
                <img src="images/pandg.jpg" alt="P&G Company" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Distribution Section -->
<section class="distribution-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <div class="icon-wrapper">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h2>การกระจายสินค้า</h2>
        </div>
        <div class="row">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="distribution-image-wrapper">
                    <img src="images/site.jpg" alt="Distribution Sites" class="distribution-image">
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="distribution-cards">
                    <div class="area-card" data-aos="zoom-in">
                        <div class="card-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>ภาคใต้</h3>
                        <p>ครอบคลุม 14 จังหวัด</p>
                        <div class="provinces">
                            <small>กระบี่, ชุมพร, นครศรีธรรมราช, พังงา, ภูเก็ต, ระนอง, สุราษฎร์ธานี, ตรัง, นราธิวาส, ปัตตานี, พัทลุง, ยะลา, สงขลา, สตูล</small>
                        </div>
                    </div>
                    <div class="area-card" data-aos="zoom-in" data-aos-delay="200">
                        <div class="card-icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <h3>ภาคเหนือ</h3>
                        <p>ครอบคลุม 8 จังหวัด</p>
                        <div class="provinces">
                            <small>เชียงราย, เชียงใหม่, น่าน, พะเยา, แพร่, แม่ฮ่องสอน, ลำปาง, ลำพูน</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="products-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <div class="icon-wrapper">
                <i class="fas fa-box-open"></i>
            </div>
            <h2>สินค้าของเรา</h2>
        </div>
        <div class="products-content">
            <div class="products-image-wrapper" data-aos="fade-up">
                <img src="images/product_pg.png" alt="P&G Products" class="products-image">
            </div>
            <div class="products-grid">
                <div class="product-card" data-aos="fade-up">
                    <div class="card-icon">
                        <i class="fas fa-pump-soap"></i>
                    </div>
                    <h4>ผลิตภัณฑ์ดูแลเส้นผม</h4>
                    <div class="brand-item">
                        <div class="brand-name">Rejoice</div>
                        <div class="brand-description">แชมพูและครีมนวดผม</div>
                    </div>
                    <div class="brand-item">
                        <div class="brand-name">Pantene</div>
                        <div class="brand-description">แชมพู ครีมนวด และทรีตเมนต์บำรุงผม</div>
                    </div>
                    <div class="brand-item">
                        <div class="brand-name">Head & Shoulders</div>
                        <div class="brand-description">แชมพูขจัดรังแค</div>
                    </div>
                    <div class="brand-item">
                        <div class="brand-name">Herbal Essences</div>
                        <div class="brand-description">แชมพูและครีมนวดผมจากสารสกัดธรรมชาติ</div>
                    </div>
                </div>
                <div class="product-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-icon">
                        <i class="fas fa-face-smile"></i>
                    </div>
                    <h4>ผลิตภัณฑ์ดูแลผิว</h4>
                    <div class="brand-item">
                        <div class="brand-name">Olay</div>
                        <div class="brand-description">ครีมบำรุงผิวหน้า เซรั่ม และผลิตภัณฑ์ล้างหน้า</div>
                    </div>
                    <div class="brand-item">
                        <div class="brand-name">Safeguard</div>
                        <div class="brand-description">สบู่ก้อนและสบู่เหลวทำความสะอาดผิว</div>
                    </div>
                </div>
                <div class="product-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-icon">
                        <i class="fas fa-tooth"></i>
                    </div>
                    <h4>ผลิตภัณฑ์ดูแลสุขภาพช่องปาก</h4>
                    <div class="brand-item">
                        <div class="brand-name">Oral-B</div>
                        <div class="brand-description">แปรงสีฟัน ยาสีฟัน น้ำยาบ้วนปาก ไหมขัดฟัน</div>
                    </div>
                </div>
                <div class="product-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>ผลิตภัณฑ์เพื่อสุขอนามัยส่วนตัว</h4>
                    <div class="brand-item">
                        <div class="brand-name">Whisper</div>
                        <div class="brand-description">ผ้าอนามัย</div>
                    </div>
                    <div class="brand-item">
                        <div class="brand-name">Pampers</div>
                        <div class="brand-description">ผ้าอ้อมเด็กสำเร็จรูป</div>
                    </div>
                </div>
                <div class="product-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-icon">
                        <i class="fas fa-capsules"></i>
                    </div>
                    <h4>ผลิตภัณฑ์เพื่อสุขภาพ</h4>
                    <div class="brand-item">
                        <div class="brand-name">Vicks</div>
                        <div class="brand-description">ยาดม ยาแก้ไอ ยาทาบรรเทาอาการหวัด</div>
                    </div>
                </div>
                <div class="product-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-icon">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <h4>ผลิตภัณฑ์ซักผ้า</h4>
                    <div class="brand-item">
                        <div class="brand-name">Downy</div>
                        <div class="brand-description">น้ำยาปรับผ้านุ่ม น้ำยาซักผ้า</div>
                    </div>
                </div>
                <div class="product-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-icon">
                        <i class="fas fa-spray-can"></i>
                    </div>
                    <h4>ผลิตภัณฑ์ปรับอากาศ</h4>
                    <div class="brand-item">
                        <div class="brand-name">Ambi Pur</div>
                        <div class="brand-description">สเปรย์และผลิตภัณฑ์ปรับอากาศ</div>
                    </div>
                </div>
                <div class="product-card" data-aos="fade-up" data-aos-delay="700">
                    <div class="card-icon">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <h4>ผลิตภัณฑ์โกนหนวด</h4>
                    <div class="brand-item">
                        <div class="brand-name">Gillette</div>
                        <div class="brand-description">มีดโกนหนวด โฟมโกนหนวด เจลโกนหนวด และผลิตภัณฑ์ดูแลผิวหลังโกนหนวด</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
:root {
    --primary: #1a4b84;
    --primary-light: #2c5c9c;
    --primary-dark: #0d2847;
    --secondary: #3498db;
    --accent: #f1c40f;
    --text-dark: #2c3e50;
    --text-light: #7f8c8d;
    --white: #ffffff;
    --gray-100: #f8f9fa;
    --gray-200: #e9ecef;
    --gray-300: #dee2e6;
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
    --transition: all 0.3s ease;
}

body {
    font-family: 'Sarabun', sans-serif;
    line-height: 1.6;
    color: var(--text-dark);
}

/* Hero Section */
.hero-section {
    position: relative;
    background: linear-gradient(120deg, var(--primary-dark), var(--primary));
    padding: 80px 0 40px;
    margin-top: 0;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, rgba(0,0,0,0.4), rgba(0,0,0,0.1));
}

.hero-content {
    position: relative;
    z-index: 2;
    color: var(--white);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1.5rem;
    font-weight: 400;
    opacity: 0.9;
}

.hero-image-wrapper {
    position: relative;
    z-index: 2;
}

.hero-image {
    width: 100%;
    max-width: 500px;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
}

/* Section Styles */
section {
    min-height: 100vh;
    width: 100%;
    position: relative;
    display: flex;
    align-items: center;
    padding: 80px 0;
    overflow: hidden;
}

.section-header {
    margin-bottom: 60px;
    position: relative;
}

.section-header::after {
    content: '';
    position: absolute;
    bottom: -15px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-light), var(--primary));
    border-radius: 2px;
}

.icon-wrapper {
    display: inline-block;
    margin-bottom: 1rem;
}

.icon-wrapper i {
    font-size: 2.5rem;
    color: var(--primary);
}

.section-header h2 {
    font-size: 2.5rem;
    font-weight: 600;
    color: var(--primary-dark);
    margin: 0;
}

/* About Section */
.about-section {
    background: var(--white);
    position: relative;
    padding: 80px 0;
}

.about-content {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 50px;
    margin-top: 30px;
}

.about-text {
    flex: 1;
    padding: 20px;
}

.about-text .lead {
    font-size: 1.25rem;
    line-height: 1.8;
    color: var(--text-dark);
    text-align: left;
}

.about-image {
    flex: 1;
    position: relative;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.about-image img {
    width: 100%;
    height: auto;
    max-width: 450px;
    object-fit: cover;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
    transition: transform 0.3s ease;
}

.about-image img:hover {
    transform: scale(1.02);
}

.about-image::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 20px;
    width: calc(100% - 40px);
    height: calc(100% - 40px);
    background: linear-gradient(45deg, var(--primary-light), var(--primary));
    border-radius: 20px;
    opacity: 0.1;
    transform: rotate(-2deg);
    z-index: -1;
}

/* Distribution Section */
.distribution-section {
    background: var(--gray-100);
}

.distribution-section .container {
    max-width: 1400px;
    margin: 0 auto;
}

.distribution-image-wrapper {
    margin-bottom: 30px;
    text-align: center;
}

.distribution-image {
    width: 100%;
    max-width: 400px;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
}

.distribution-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 0;
}

.area-card {
    height: 100%;
    display: flex;
    flex-direction: column;
    background: var(--white);
    padding: 25px;
    border-radius: 15px;
    box-shadow: var(--shadow-md);
    transition: var(--transition);
}

.area-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.card-icon {
    margin-bottom: 1rem;
}

.card-icon i {
    font-size: 2rem;
    color: var(--primary);
}

.area-card h3 {
    font-size: 1.5rem;
    color: var(--primary-dark);
    margin-bottom: 0.5rem;
}

.area-card p {
    font-size: 1.1rem;
    color: var(--text-light);
    margin-bottom: 1rem;
}

.provinces {
    color: var(--text-light);
    line-height: 1.4;
}

/* Products Section */
.products-section {
    min-height: 100vh;
    padding: 80px 0;
}

.products-image-wrapper {
    margin-bottom: 50px;
    text-align: center;
}

.products-image {
    max-width: 100%;
    border-radius: 20px;
    box-shadow: var(--shadow-md);
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.product-card {
    height: 100%;
    padding: 25px;
    background: var(--white);
    border-radius: 15px;
    box-shadow: var(--shadow-md);
    transition: var(--transition);
    text-align: left;
    border: 1px solid var(--gray-200);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-light);
}

.product-card .card-icon {
    margin-bottom: 1.5rem;
    text-align: center;
    display: flex;
    justify-content: center;
}

.product-card .card-icon i {
    font-size: 2.5rem;
    color: var(--primary);
    transition: var(--transition);
    background: var(--gray-100);
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.product-card:hover .card-icon i {
    transform: scale(1.1);
    background: var(--primary);
    color: var(--white);
}

.product-card h4 {
    font-size: 1.4rem;
    color: var(--primary-dark);
    margin-bottom: 1.5rem;
    font-weight: 600;
    text-align: center;
    position: relative;
    padding-bottom: 15px;
}

.product-card h4:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background: var(--primary);
    border-radius: 2px;
}

.product-card .brand-name {
    font-weight: 600;
    color: var(--primary);
    font-size: 1.1rem;
    margin-bottom: 5px;
}

.product-card .brand-description {
    color: var(--text-light);
    margin-bottom: 15px;
    padding-left: 15px;
    border-left: 2px solid var(--gray-200);
}

.product-card .brand-item {
    margin-bottom: 20px;
}

.product-card .brand-item:last-child {
    margin-bottom: 0;
}

/* Responsive Design */
@media (min-width: 992px) {
    .distribution-section .row {
        display: flex;
        align-items: flex-start;
    }

    .distribution-image-wrapper {
        position: sticky;
        top: 100px;
    }

    .distribution-cards {
        margin-top: 0;
    }
}

@media (max-width: 991px) {
    .hero-section {
        min-height: calc(100vh - 56px);
        padding: 80px 0 40px;
        margin-top: 56px;
    }

    .hero-content {
        text-align: center;
        padding: 20px 0;
    }

    .hero-title {
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.2rem;
    }

    .hero-image-wrapper {
        margin-top: 20px;
        padding: 0 20px;
    }

    .hero-image {
        max-width: 70%;
        margin: 0 auto;
    }

    section {
        min-height: 100vh;
        padding: 60px 0;
        justify-content: center;
    }

    .section-header {
        margin-bottom: 40px;
    }

    .about-content {
        flex-direction: column;
        text-align: center;
        gap: 30px;
    }

    .about-text .lead {
        text-align: center;
        font-size: 1.2rem;
        padding: 0 15px;
    }

    .about-image {
        width: 100%;
        padding: 10px;
    }

    .about-image img {
        max-width: 100%;
        height: auto;
        max-height: 400px;
        width: auto;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 60px 0 30px;
    }

    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .hero-image {
        max-width: 80%;
    }

    section {
        padding: 50px 0;
    }

    .about-section {
        padding: 60px 0;
    }

    .about-content {
        gap: 20px;
    }

    .about-text {
        padding: 10px;
    }

    .about-text .lead {
        font-size: 1.1rem;
    }

    .about-image {
        padding: 5px;
    }

    .about-image img {
        max-height: 300px;
    }

    .about-image::before {
        top: 10px;
        left: 10px;
        width: calc(100% - 20px);
        height: calc(100% - 20px);
    }
}

/* เพิ่ม CSS สำหรับ header */
.navbar {
    width: 100% !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
}

.navbar-collapse {
    max-width: 100vw !important;
    overflow-x: hidden !important;
}

.container-fluid {
    padding-left: 15px !important;
    padding-right: 15px !important;
    max-width: 100vw !important;
    overflow-x: hidden !important;
}

/* ปรับแต่ง mobile menu */
@media (max-width: 991px) {
    .navbar-nav {
        width: 100% !important;
        padding: 10px 0 !important;
    }
    
    .navbar-toggler {
        padding: 8px 12px !important;
        font-size: 1.25rem !important;
        border: 2px solid var(--primary) !important;
    }

    .navbar-toggler-icon {
        width: 1.5em !important;
        height: 1.5em !important;
    }

    .navbar-brand img {
        max-height: 40px !important;
    }
}

/* แก้ไขปัญหา overflow ทั้งหมด */
html, body {
    overflow-x: hidden !important;
    width: 100% !important;
    max-width: 100% !important;
    position: relative !important;
}

/* เพิ่ม CSS สำหรับ Scroll Behavior */
html {
    scroll-snap-type: y mandatory;
    scroll-behavior: smooth;
}

section {
    scroll-snap-align: start;
    scroll-snap-stop: always;
}

/* แก้ไข Animation */
[data-aos] {
    transition-duration: 800ms;
}

.section-content {
    width: 100%;
}

/* Navigation Dots */
.scroll-nav {
    position: fixed;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 10px;
}

.scroll-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    border: 2px solid var(--primary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.scroll-dot.active {
    background: var(--primary);
    transform: scale(1.2);
}

/* Section Specific Adjustments */
.hero-section {
    padding: 120px 0 80px;
    display: flex;
    align-items: center;
}

.about-section,
.distribution-section,
.products-section {
    padding: 80px 0;
    display: flex;
    align-items: center;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    section {
        min-height: 100vh;
        padding: 60px 0;
    }
    
    .scroll-nav {
        display: none;
    }
}
</style>

<!-- Add Navigation Dots -->
<div class="scroll-nav">
    <div class="scroll-dot active" data-section="hero"></div>
    <div class="scroll-dot" data-section="about"></div>
    <div class="scroll-dot" data-section="distribution"></div>
    <div class="scroll-dot" data-section="products"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('section');
    const dots = document.querySelectorAll('.scroll-dot');
    let isScrolling = false;
    let currentSection = 0;

    // Add IDs to sections
    sections.forEach((section, index) => {
        section.id = section.classList[0].replace('-section', '');
    });

    // Update active dot based on scroll position
    function updateActiveDot() {
        const scrollPosition = window.pageYOffset;
        
        sections.forEach((section, index) => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            
            if (scrollPosition >= sectionTop - window.innerHeight/3) {
                dots.forEach(dot => dot.classList.remove('active'));
                dots[index].classList.add('active');
                currentSection = index;
            }
        });
    }

    // Smooth scroll to section
    function scrollToSection(index) {
        if (isScrolling) return;
        
        isScrolling = true;
        sections[index].scrollIntoView({ behavior: 'smooth' });
        
        setTimeout(() => {
            isScrolling = false;
        }, 1000);
    }

    // Handle dot click
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            scrollToSection(index);
        });
    });

    // Handle wheel event
    let wheelTimeout;
    window.addEventListener('wheel', (e) => {
        clearTimeout(wheelTimeout);
        
        wheelTimeout = setTimeout(() => {
            if (isScrolling) return;
            
            if (e.deltaY > 0 && currentSection < sections.length - 1) {
                scrollToSection(currentSection + 1);
            } else if (e.deltaY < 0 && currentSection > 0) {
                scrollToSection(currentSection - 1);
            }
        }, 50);
    }, { passive: true });

    // Update on scroll
    window.addEventListener('scroll', () => {
        requestAnimationFrame(updateActiveDot);
    }, { passive: true });

    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<?php
define('BASE_PATH', dirname(__FILE__));
include 'footer.php';
?>

</body>
</html>