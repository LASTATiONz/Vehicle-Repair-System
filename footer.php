<!-- Footer -->
<footer class="footer mt-5">
    <div class="container">
        <div class="row">
            <!-- Company Info -->
            <div class="col-lg-4 mb-4">
                <div class="footer-brand">
                    <img src="images/LOGO_ORNATE1.png" alt="ORNATE SDO Logo" class="footer-logo">
                    <div class="brand-text">
                        <h5 class="brand-name">ORNATE SDO</h5>
                        <span class="brand-sub">บริษัท ออร์เนท เอสดีโอ จำกัด</span>
                    </div>
                </div>
                <p class="footer-desc">ตัวแทนจำหน่ายสินค้าอย่างเป็นทางการของ P&G (Procter & Gamble) บริษัทผู้ผลิตสินค้าอุปโภคบริโภคชั้นนำระดับโลก ที่ดำเนินธุรกิจมายาวนานกว่า 165 ปี</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/ornate.sdosrt" target="_blank" class="social-link">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://line.me/ti/p/NKzv6seCSM" target="_blank" class="social-link">
                        <i class="fab fa-line"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-4 mb-4">
                <h5>เกี่ยวกับเรา</h5>
                <ul class="footer-links">
                    <li><a href="home_job.php">ตำแหน่งงานที่เปิดรับ</a></li>
                    <li><a href="resume_ornategroup.php">ร่วมงานกับเรา</a></li>
                    <li><a href="about.php">เกี่ยวกับบริษัท</a></li>
                    <li><a href="contact.php">ติดต่อเรา</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4 mb-4">
                <h5>ติดต่อเรา</h5>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>31/237 ม.2 ถ.สุราษฎร์-นครศรีฯ ต.บางกุ้ง อ.เมือง จ.สุราษฎร์ธานี 84000</span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <span>077-281-972</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>hrsrt@ornategroup.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            <div class="row">
                <div class="col-12 text-center">
                    <p>&copy; 2025 ORNATE SDO. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Footer Styles */
    .footer {
        background: #f8f9fa;
        color: #333;
        padding: 60px 0 30px;
        position: relative;
        border-top: 1px solid #e9ecef;
        box-shadow: 0 -1px 10px rgba(0,0,0,0.05);
    }

    .footer-brand {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .footer-logo {
        height: 45px;
        filter: none;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
    }

    .brand-name {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
        color: #0056b3;
        line-height: 1.2;
    }

    .brand-sub {
        font-size: 0.85rem;
        color: #666;
    }

    .footer-desc {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #666;
        margin-bottom: 25px;
    }

    .footer h5 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #0056b3;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        padding-bottom: 10px;
    }

    .footer h5::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background: #0056b3;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: #666;
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-links a:hover {
        color: #0056b3;
        transform: translateX(5px);
    }

    .footer-contact {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-contact li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 15px;
        font-size: 0.95rem;
        color: #666;
    }

    .footer-contact li i {
        color: #0056b3;
        font-size: 1.1rem;
        margin-top: 3px;
    }

    .social-links {
        display: flex;
        gap: 15px;
    }

    .social-link {
        color: #666;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        color: #0056b3;
        transform: translateY(-3px);
    }

    .footer-bottom {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .footer-bottom p {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
    }

    @media (max-width: 768px) {
        .footer {
            padding: 40px 0 20px;
        }

        .footer-brand {
            justify-content: center;
            margin-bottom: 20px;
            text-align: center;
            flex-direction: column;
            align-items: center;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .footer h5 {
            text-align: center;
        }

        .footer h5::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .footer-links {
            text-align: center;
        }

        .social-links {
            justify-content: center;
            margin-bottom: 30px;
        }

        .footer-contact li {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 10px;
        }

        .footer-contact li i {
            margin-bottom: 5px;
        }

        .footer-bottom {
            margin-top: 30px;
            padding-top: 15px;
            text-align: center;
        }

        .footer-desc {
            text-align: center;
            margin-top: 10px;
        }
    }

    
    @media (max-width: 425px) {
        .footer {
            padding: 30px 0 15px;
        }

        .footer-brand {
            flex-direction: column;
            text-align: center;
        }

        .footer-logo {
            margin-bottom: 10px;
        }

        .footer h5 {
            font-size: 1rem;
            text-align: center;
        }

        .footer-links {
            text-align: center;
        }

        .footer-contact li {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 10px;
        }

        .footer-contact li i {
            margin-bottom: 5px;
        }

        .social-links {
            justify-content: center;
        }

        .footer-bottom {
            text-align: center;
        }

        .footer-desc {
            font-size: 0.85rem;
            line-height: 1.4;
            margin-bottom: 20px;
            text-align: center;
        }
    }

    

</style> 