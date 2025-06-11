<?php
session_start();

// ทำลาย $_SESSION["guest_no"]
if (isset($_SESSION["guest_no"])) {
    unset($_SESSION["guest_no"]); // ทำลาย session ตัวแปร guest_no
}
// ส่วนที่เหลือของโค้ดใน home.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB ORNATE SDO</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/css/home_job.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Favicon -->
    <link rel="icon" href="images/LOGO_ORNATE1.png" type="image/icon type">


    <script>
        // เพิ่ม script สำหรับ navbar scroll effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.navbar').addClass('scrolled');
            } else {
                $('.navbar').removeClass('scrolled');
            }
        });
    </script>
</head>

<body>


	<div class="m-0">
		<?php
		//include connect database
		include 'db_connect.php';
		include 'header.php';
		?>
	</div>


	    <!-- Hero Section -->
		<section class="hero-section">
            <div class="overlay text-center text-white">
                <h1 class="hero-title">ร่วมงานกับเราในวันนี้</h1>
                <p class="hero-subtitle">โอกาสในการทำงานที่น่าตื่นเต้นรอคุณอยู่</p>
                <a href="resume_ornategroup.php" class="cta-btn">สมัครงานตอนนี้</a>
            </div>
        </section>



    <!-- Job Listings Section -->
    <div id="job-listings" class="container mt-5">
        <div class="row mb-4">
			<div class="text-center">
				<h2 class="job-header">ตำแหน่งงานที่เปิดรับ</h2>
				<p class="job-subtitle">ค้นหาตำแหน่งงานที่คุณสนใจและสมัครงานได้ทันที</p>
			</div>

            <!-- Search Bar -->
			<div class="filter-section">
				<div class="filter-tags">
					<button class="filter-btn active" data-branch="">ทั้งหมด</button>
					<button class="filter-btn" data-branch="CHM">เชียงใหม่</button>
					<button class="filter-btn" data-branch="HDY">หาดใหญ่</button>
					<button class="filter-btn" data-branch="SRT">สุราษฎร์ธานี</button>
				</div>

				<div class="search-container">
					<div class="search-box">
						<i class="fas fa-search"></i>
						<input type="text" id="search" placeholder="ค้นหาตำแหน่งงาน...">
					</div>
				</div>

			</div>

			<!-- ข้อมูลจะแสดงในส่วนนี้ผ่าน AJAX -->
			<div id="job-results" class="row"></div>


        </div>

        <!-- Job Cards -->

    </div>

    <?php
        define('BASE_PATH', dirname(__FILE__));
        include 'footer.php';
    ?>


	<script>
		$(document).ready(function () {
			function fetchJobs(searchText = "", branch = "") {
				$.ajax({
					url: "search_jobs.php",
					method: "POST",
					data: { search: searchText, branch: branch },
					dataType: "json",
					success: function (response) {
						$("#job-results").empty();
						
						if (response.status === 'success' && response.data && response.data.length > 0) {
							response.data.forEach(function (job) {
								let isNewJob = job.is_new_job === 1;
								let branchName;
								
								switch(job.branch.trim()) {
									case 'CHM':
										branchName = 'เชียงใหม่';
										break;
									case 'HDY':
										branchName = 'หาดใหญ่';
										break;
									case 'SRT':
										branchName = 'สำนักงานใหญ่ สุราษฎร์ธานี';
										break;
									default:
										branchName = job.branch_name;
								}

								let jobCard = `
										<div class='col-12 col-sm-12 col-md-6 col-xl-4 mb-4'>
											<div class='card job-card shadow-sm'>
											${isNewJob ? '<span class="new-job-badge"><i class="fas fa-star"></i> ตำแหน่งงานใหม่ล่าสุด</span>' : ''}
												<div class='card-body'>
													<h5 class='card-title d-flex align-items-center'>
														<i class='fa-solid fa-thumbtack'></i>
														<span class='position-title ms-2'>${job.position}</span>
													</h5>
													    <p class='job-desc'><strong>รายละเอียดงาน:</strong></p>
														<ul class='job-details-list'>
															${job.job_attribute1 ? `<li><i class="fa-solid fa-check-circle"></i> ${job.job_attribute1}</li>` : ""}
															${job.job_attribute2 ? `<li><i class="fa-solid fa-check-circle"></i> ${job.job_attribute2}</li>` : ""}
															${job.job_attribute3 ? `<li><i class="fa-solid fa-check-circle"></i> ${job.job_attribute3}</li>` : ""}
															${job.job_attribute4 ? `<li><i class="fa-solid fa-check-circle"></i> ${job.job_attribute4}</li>` : ""}
															${job.job_attribute5 ? `<li><i class="fa-solid fa-check-circle"></i> ${job.job_attribute5}</li>` : ""}
														</ul>
													<div class='job-details mt-2'>
														<span class='badge salary'><i class='fa-solid fa-coins'></i> เงินเดือน: ${job.salary}</span>
													<span class='badge branch'><i class='fa-solid fa-map-marker-alt'></i> สาขา: ${branchName}</span>
													</div>
													<div class='mt-3 d-flex justify-content-between'>
														<a href='resume_ornategroup.php?job_no=${job.job_no}' class='btn btn-primary btn-sm flex-fill me-2'>
															<i class='fa-solid fa-paper-plane'></i> สมัครงาน
														</a>
													<a href='#' class='btn btn-outline-secondary btn-sm flex-fill view-details' data-job-no='${job.job_no}'>
															<i class='fa-solid fa-info-circle'></i> รายละเอียด
														</a>
													</div>
												</div>
											</div>
										</div>
									`;
								$("#job-results").append(jobCard);
								});
							} else {
							$("#job-results").html('<div class="col-12"><div class="alert alert-warning">ไม่พบตำแหน่งงาน</div></div>');
							}
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", error);
						$("#job-results").html('<div class="col-12"><div class="alert alert-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล กรุณาลองใหม่อีกครั้ง</div></div>');
					}
				});
			}

			// โหลดข้อมูลเมื่อหน้าเว็บโหลดเสร็จ
			fetchJobs();

			// เมื่อพิมพ์ในช่องค้นหาให้ดึงข้อมูลอัตโนมัติ
			$("#search").on("keyup", function () {
				let searchText = $(this).val();
				let branch = $(".filter-btn.active").data("branch") || "";
				fetchJobs(searchText, branch);
			});

			// เมื่อกดปุ่มกรองสาขา
			$(".filter-btn").on("click", function () {
				$(".filter-btn").removeClass("active");
				$(this).addClass("active");
				let branch = $(this).data("branch");
				let searchText = $("#search").val();
				fetchJobs(searchText, branch);
			});
		});
	</script>

    <!-- เพิ่ม HTML สำหรับ Popup -->
    <div class="job-popup">
        <div class="popup-content">
            <div class="popup-header">
                <h3 class="popup-title"></h3>
                <button class="popup-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="popup-body">
                <div class="popup-section">
                    <h4 class="popup-section-title">
                        <i class="fas fa-money-bill-wave"></i>
                        เงินเดือน
                    </h4>
                    <div class="salary-info"></div>
                </div>
                <div class="popup-section">
                    <h4 class="popup-section-title">
                        <i class="fas fa-clipboard-list"></i>
                        รายละเอียดงาน
                    </h4>
                    <ul class="popup-details"></ul>
                </div>
                <div class="popup-section">
                    <h4 class="popup-section-title">
                        <i class="fas fa-user-tie"></i>
                        คุณสมบัติผู้สมัคร
                    </h4>
                    <ul class="popup-list requirements-list"></ul>
                </div>
                <div class="popup-section">
                    <h4 class="popup-section-title">
                        <i class="fas fa-building"></i>
                        สถานที่ปฏิบัติงาน
                    </h4>
                    <div class="location-info"></div>
                </div>
                <div class="popup-section">
                    <h4 class="popup-section-title">
                        <i class="fas fa-award"></i>
                        สวัสดิการที่ได้รับ
                    </h4>
                    <div class="benefits-info"></div>
                </div>
                <div class="popup-section">
                    <h4 class="popup-section-title">
                        <i class="fas fa-headset"></i>
                        ช่องทางติดต่อสอบถาม
                    </h4>
                    <div class="contact-info"></div>
                </div>
            </div>
            <div class="popup-footer">
                <a href="#" class="popup-btn popup-btn-secondary">
                    <i class="fas fa-times"></i>
                    ปิด
                </a>
                <a href="#" class="popup-btn popup-btn-primary apply-btn">
                    <i class="fas fa-paper-plane"></i>
                    สมัครงาน
                </a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // เปิด Popup เมื่อคลิกปุ่มรายละเอียด
            $(document).on('click', '.view-details', function(e) {
                e.preventDefault();
                const jobNo = $(this).data('job-no');
                const $popup = $('.job-popup');
                
                // ดึงข้อมูลงานผ่าน AJAX
                $.ajax({
                    url: 'get_job_details.php',
                    method: 'POST',
                    data: { job_no: jobNo },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success' && response.data) {
                            const job = response.data;
                            
                            // กำหนดข้อมูลใน Popup
                            $popup.find('.popup-title').text(job.position);
                            
                            // แสดงเงินเดือน
                            $popup.find('.salary-info').html(`
                                <div class="salary-amount">
                                    <i class="fas fa-coins"></i> ${job.salary}
                                </div>
                            `);
                            
                            // แสดงรายละเอียดงาน
                            let detailsHtml = '';
                            if (job.job_attribute1) detailsHtml += `<li class="popup-detail-item"><i class="fas fa-angle-right"></i><span>${job.job_attribute1}</span></li>`;
                            if (job.job_attribute2) detailsHtml += `<li class="popup-detail-item"><i class="fas fa-angle-right"></i><span>${job.job_attribute2}</span></li>`;
                            if (job.job_attribute3) detailsHtml += `<li class="popup-detail-item"><i class="fas fa-angle-right"></i><span>${job.job_attribute3}</span></li>`;
                            if (job.job_attribute4) detailsHtml += `<li class="popup-detail-item"><i class="fas fa-angle-right"></i><span>${job.job_attribute4}</span></li>`;
                            if (job.job_attribute5) detailsHtml += `<li class="popup-detail-item"><i class="fas fa-angle-right"></i><span>${job.job_attribute5}</span></li>`;
                            
                            $popup.find('.popup-details').html(detailsHtml);
                            
                            // แสดงคุณสมบัติผู้สมัคร
                            $popup.find('.requirements-list').html(`
                                ${job.requirements ? job.requirements.map(req => 
                                    `<li class="popup-detail-item"><i class="fas fa-check"></i><span>${req.text}</span></li>`
                                ).join('') : '<li>ไม่พบข้อมูลคุณสมบัติผู้สมัคร</li>'}
                            `);
                            
                            // แสดงสถานที่ปฏิบัติงาน
                            let branchDisplay = '';
                            switch(job.branch.trim()) {
                                case 'CHM':
                                    branchDisplay = 'เชียงใหม่';
                                    break;
                                case 'HDY':
                                    branchDisplay = 'หาดใหญ่';
                                    break;
                                case 'SRT':
                                    branchDisplay = 'สำนักงานใหญ่ สุราษฎร์ธานี';
                                    break;
                                default:
                                    branchDisplay = job.branch_name;
                            }
                            
                            $popup.find('.location-info').html(`
                                <div class="location-details">
                                    <div class="mb-3">
                                        <i class="fas fa-building me-2"></i> 
                                        <strong>สาขา:</strong> ${branchDisplay}
                                    </div>
                                    <div>
                                        <i class="fas fa-map-marker-alt me-2"></i> 
                                        <strong>ที่อยู่:</strong> ${job.job_location || 'ไม่ระบุ'}
                                    </div>
                                </div>
                            `);
                            
                            // แสดงสวัสดิการ
                            $popup.find('.benefits-info').html(`
                                <div class="benefits-list">
                                    ${job.benefits ? job.benefits.map(benefit => 
                                        `<div class="mb-2"><i class="fas ${benefit.icon}"></i> ${benefit.text}</div>`
                                    ).join('') : '<div>ไม่พบข้อมูลสวัสดิการ</div>'}
                                </div>
                            `);
                            
                            // แสดงช่องทางติดต่อ
                            let contactHtml = '';
                            switch(job.branch.trim()) {
                                case 'CHM':
                                    contactHtml = `
                                        <div class="mb-2">
                                            <i class="fas fa-phone me-2"></i>
                                            <strong>เบอร์โทร:</strong> 053-850-111
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-envelope me-2"></i>
                                            <strong>อีเมล:</strong> hr.chm@ornatesdo.com
                                        </div>
                                        <div class="mb-2">
                                            <i class="fab fa-line me-2"></i>
                                            <strong>Line ID:</strong> 
                                            <a href="https://line.me/ti/p/~0813702662" target="_blank" class="text-primary">
                                                0813702662
                                            </a>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fab fa-facebook me-2"></i>
                                            <strong>Facebook:</strong> 
                                            <a href="https://www.facebook.com/ornate.sdo.7" target="_blank" class="text-primary">
                                                ติดตามเราได้ที่นี่
                                            </a>
                                        </div>
                                    `;
                                    break;
                                case 'HDY':
                                    contactHtml = `
                                        <div class="mb-2">
                                            <i class="fas fa-phone me-2"></i>
                                            <strong>เบอร์โทร:</strong> 074-230-990
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-envelope me-2"></i>
                                            <strong>อีเมล:</strong> hr.hdy@ornatesdo.com
                                        </div>
                                        <div class="mb-2">
                                            <i class="fab fa-line me-2"></i>
                                            <strong>Line ID:</strong> 
                                            <a href="https://line.me/ti/p/~hrornhdy" target="_blank" class="text-primary">
                                                hrornhdy
                                            </a>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fab fa-facebook me-2"></i>
                                            <strong>Facebook:</strong> 
                                            <a href="https://www.facebook.com/ornate.sdohdy" target="_blank" class="text-primary">
                                                ติดตามเราได้ที่นี่
                                            </a>
                                        </div>
                                    `;
                                    break;
                                case 'SRT':
                                    contactHtml = `
                                        <div class="mb-2">
                                            <i class="fas fa-phone me-2"></i>
                                            <strong>เบอร์โทร:</strong> 077-960-111
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-envelope me-2"></i>
                                            <strong>อีเมล:</strong> hr.srt@ornatesdo.com
                                        </div>
                                        <div class="mb-2">
                                            <i class="fab fa-line me-2"></i>
                                            <strong>Line ID:</strong> 
                                            <a href="https://line.me/ti/p/NKzv6seCSM" target="_blank" class="text-primary">
                                                NKzv6seCSM
                                            </a>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fab fa-facebook me-2"></i>
                                            <strong>Facebook:</strong> 
                                            <a href="https://www.facebook.com/ornate.sdosrt" target="_blank" class="text-primary">
                                                ติดตามเราได้ที่นี่
                                            </a>
                                        </div>
                                    `;
                                    break;
                                default:
                                    // แสดงข้อมูลติดต่อจาก API
                                    if (job.contact_info && job.contact_info.length > 0) {
                                        contactHtml = job.contact_info.map(contact => `
                                            <div class="mb-2">
                                                <i class="fas ${contact.icon} me-2"></i>
                                                <strong>${contact.text}</strong>
                                            </div>
                                        `).join('');
                                    } else {
                                        contactHtml = '<div>ไม่พบข้อมูลการติดต่อ</div>';
                                    }
                            }
                            
                            $popup.find('.contact-info').html(`
                                <div class="contact-details">
                                    ${contactHtml}
                                </div>
                            `);
                            
                            // กำหนดลิงก์ปุ่มสมัครงาน
                            $popup.find('.apply-btn').attr('href', `resume_ornategroup.php?job_no=${job.job_no}`);
                            
                            // แสดง Popup
                            $popup.addClass('active');
                        } else {
                            alert(response.error || 'เกิดข้อผิดพลาดในการโหลดข้อมูล');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                    }
                });
            });

            // ปิด Popup
            $('.popup-close, .popup-btn-secondary').click(function(e) {
                e.preventDefault();
                $('.job-popup').removeClass('active');
            });

            // ปิด Popup เมื่อคลิกพื้นหลัง
            $('.job-popup').click(function(e) {
                if (e.target === this) {
                    $(this).removeClass('active');
                }
            });
        });
    </script>

    <!-- Search Section -->

    <script>
    $(document).ready(function() {
        // เพิ่มการทำงานของปุ่มค้นหา
        $('.search-btn').click(function() {
            let searchText = $('#search').val();
            let branch = $('.filter-btn.active').data('branch') || '';
            fetchJobs(searchText, branch);
        });

        // เพิ่มการทำงานเมื่อกด Enter
        $('#search').keypress(function(e) {
            if(e.which == 13) {
                let searchText = $(this).val();
                let branch = $('.filter-btn.active').data('branch') || '';
                fetchJobs(searchText, branch);
            }
        });
    });
	</script>

</body>
</html>