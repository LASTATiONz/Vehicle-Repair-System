<!-- TOP NAVIGATOR BAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="home_job.php">
            <img src="images/LOGO_ORNATE1.png" alt="ORNATE SDO Logo" style="height: 50px;">
            <span>ORNATE SDO</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'home_job.php' ? 'active' : ''; ?>" href="home_job.php">
                        <i class="fas fa-briefcase"></i> ตำแหน่งงานว่าง
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'resume_ornategroup.php' ? 'active' : ''; ?>" href="resume_ornategroup.php">
                        <i class="fas fa-file-alt"></i> กรอกใบสมัครงาน
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="about.php">
                        <i class="fas fa-info-circle"></i> เกี่ยวกับเรา
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="contact.php">
                        <i class="fas fa-envelope"></i> ติดต่อเรา
                    </a>
                </li>
                                <?php
                // Ensure session is started. If this header is included in pages
                // where session_start() is already called at the very top, this check is fine.
                // Otherwise, you might need to call session_start() here if it's not already active.
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                if (isset($_SESSION['user_id'])): // User is logged in
                ?>
                    <li class="nav-item mx-lg-2 my-2 my-lg-0"> <!-- Added margin for spacing -->
                        <a class="nav-link btn btn-danger btn-sm d-flex align-items-center justify-content-center" href="guest_logout.php" title="ออกจากระบบ" style="padding: 0.375rem 0.75rem;">
                            <i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ
                        </a>
                    </li>

                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<!--END TOP NAVIGATOR BAR -->
<br /> <br />

<style>
    :root {
        --primary-blue: #0056B3;
        --secondary-blue: #003366;
        --highlight-blue: #4A90E2;
        --light-blue: rgba(74, 144, 226, 0.1);
        --white: #FFFFFF;
        --gray-dark: #343a40;
    }

    /* Navbar Styles */
    .navbar {
        background: var(--primary-blue);
        padding: 15px 0;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .navbar-brand span {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--white);
        letter-spacing: 0.5px;
    }

    .navbar-nav {
        gap: 15px;
    }

    .navbar-nav .nav-link {
        color: var(--white) !important;
        font-weight: 600;
        padding: 8px 16px;
        transition: all 0.3s ease;
        position: relative;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
        border-radius: 4px;
        overflow: hidden;
    }

    .navbar-nav .nav-link i {
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }

    .navbar-nav .nav-link::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background-color: var(--white);
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .navbar-nav .nav-link:hover::before {
        width: 80%;
    }

    .navbar-nav .nav-link:hover {
        color: var(--white) !important;
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .navbar-nav .nav-link:hover i {
        transform: scale(1.1) rotate(5deg);
    }

    .navbar-nav .nav-link.active {
        color: var(--white) !important;
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
    }

    .navbar-nav .nav-link.active::before {
        width: 80%;
    }

    .navbar-toggler {
        border: none;
        padding: 0.5rem;
        font-size: 1.5rem;
        color: var(--white);
        transition: all 0.3s ease;
    }

    .navbar-toggler:focus {
        box-shadow: none;
        outline: none;
    }

    .navbar-toggler:hover {
        color: rgba(255, 255, 255, 0.8);
        transform: scale(1.1);
    }

    @media (max-width: 991px) {
        .navbar-nav {
            padding: 20px 0;
        }

        .navbar-nav .nav-link {
            padding: 10px 20px;
            margin: 5px 0;
        }
    }

    @media (max-width: 768px) {
        .navbar-brand span {
            font-size: 1.2rem;
        }

        .navbar-brand img {
            height: 40px;
        }

        .navbar-nav .nav-link {
            font-size: 0.9rem;
        }
    }

    /* Scroll Effect */
    .navbar.scrolled {
        background: rgba(0, 86, 179, 0.98);
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    // Navbar Scroll Effect
    $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
            $('.navbar').addClass('scrolled');
        } else {
            $('.navbar').removeClass('scrolled');
        }
    });

    // Hover Effect for Icons
    $('.nav-link').hover(
        function() {
            $(this).find('i').css('transform', 'scale(1.1) rotate(5deg)');
        },
        function() {
            $(this).find('i').css('transform', 'scale(1) rotate(0deg)');
        }
    );
</script>