<?php
// database connection
include('db_connect.php');

if (!isset($_GET['guest_no'])) {
    echo "<script>alert('ไม่พบรหัสผู้สมัคร'); window.close();</script>";
    exit;
}

$guest_no = $_GET['guest_no'];
$sql = "SELECT * FROM resume_job WHERE guest_no = ?";
$stmt = sqlsrv_query($conn, $sql, [$guest_no]);
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$data) {
    echo "<script>alert('ไม่พบข้อมูลผู้สมัคร'); window.close();</script>";
    exit;
}

?>

<!-- กำหนดตัวแปร -->

<?php
    $newsArray = explode(',', $data['news'] ?? '');
    $driverArray = explode(',', $data['driver'] ?? '');
    $licenseArray = explode(',', $data['drive_license'] ?? '');
    
?>

<!-- ปิดกำหนดตัวแปร -->


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลผู้สมัคร</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl p-6 my-6">
        <h4 class="text-2xl font-bold mb-4">แก้ไขข้อมูลผู้สมัคร : <?= htmlspecialchars($guest_no) ?></h4>
        <form id="adminEditForm" method="POST" enctype="multipart/form-data" action="update_guest_data.php">
            <input type="hidden" name="guest_no" value="<?= $guest_no ?>">

            <div><hr class="border-t border-gray-200 mt-4 mb-6"></div><br>
            <h2 class="text-xl font-semibold mb-4">ประวัติส่วนตัว</h2>
            <!-- คำนำหน้า ชื่อ สกุล เงิน -->
            <div class="grid grid-cols-12 gap-4 mb-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1">คำนำหน้า</label>
                    <select name="title" class="w-full border rounded-md px-3 py-2">
                        <option value="นาย" <?= $data['title']=='นาย'?'selected':'' ?>>นาย</option>
                        <option value="นาง" <?= $data['title']=='นาง'?'selected':'' ?>>นาง</option>
                        <option value="นางสาว" <?= $data['title']=='นางสาว'?'selected':'' ?>>นางสาว</option>
                    </select>
                </div>
                <div class="col-span-4">
                    <label class="block text-sm font-medium mb-1">ชื่อ-นามสกุล</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="name" value="<?= $data['name'] ?>">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1">ชื่อเล่น</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="nickname" value="<?= $data['nickname'] ?>">
                </div>
                <div class="col-span-4"> 
                    <label class="block text-sm font-medium mb-1">เงินเดือนที่ต้องการ</label>
                    <input type="number" class="w-full border rounded-md px-3 py-2" name="salary" value="<?= preg_replace('/[^\d.]/', '', $data['salary']) ?>">
                </div>
            </div>
            <!-- เพศ -->
            <div class="grid grid-cols-12 gap-4 mb-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1">เพศ</label>
                    <select name="sex" class="w-full border rounded-md px-3 py-2">
                        <option value="ชาย" <?= $data['sex']=='ชาย'?'selected':'' ?>>ชาย</option>
                        <option value="หญิง" <?= $data['sex']=='หญิง'?'selected':'' ?>>หญิง</option>
                        <option value="เพศทางเลือก" <?= $data['sex']=='เพศทางเลือก'?'selected':'' ?>>เพศทางเลือก</option>
                        <option value="ไม่ระบุ" <?= $data['sex']=='ไม่ระบุ'?'selected':'' ?>>ไม่ระบุ</option>
                    </select>
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium mb-1">วันเกิด</label>
                    <input type="date" class="w-full border rounded-md px-3 py-2" name="birthday" value="<?= isset($data['birthday']) ? $data['birthday']->format('Y-m-d') : '' ?>">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1">อายุ</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="age" value="<?= $data['age'] ?>">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1">สถานะ</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="status" value="<?= $data['status'] ?>">
                </div>
                <div class="col-span-3">
                    <label for="work_date" class="block text-sm font-medium mb-1">วันที่พร้อมเริ่มงาน</label>
                    <input type="date" class="w-full border rounded-md px-3 py-2" id="work_date" name="work_date" value="<?= isset($data['work_date']) ? $data['work_date']->format('Y-m-d') : '' ?>" >
                </div>
            </div>
            <!-- บัตรประชาชน -->
            <div class="grid grid-cols-12 gap-4 mb-4">
                <div class="col-span-4">
                    <label class="block text-sm font-medium mb-1">เลขบัตรประชาชน</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="idcard" value="<?= $data['idcard'] ?>">
                </div>
                <div class="col-span-4">
                    <label class="block text-sm font-medium mb-1">วันที่ออกบัตร</label>
                    <input type="date" class="w-full border rounded-md px-3 py-2" name="create_date" value="<?= isset($data['create_date']) ? $data['create_date']->format('Y-m-d') : '' ?>">
                </div>
                <div class="col-span-4">
                    <label class="block text-sm font-medium mb-1">วันหมดอายุบัตร</label>
                    <input type="date" class="w-full border rounded-md px-3 py-2" name="expiry_date" value="<?= isset($data['expiry_date']) ? $data['expiry_date']->format('Y-m-d') : '' ?>">
                </div>
            </div>
            <!-- สัญชาติ ศาสนา ส่วนสูง น้ำหนัก -->
            <div class="grid grid-cols-12 gap-4 mb-4">
                <div class="col-span-3">
                    <label class="block text-sm font-medium mb-1">สัญชาติ</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="nationality" value="<?= $data['nationality'] ?>">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium mb-1">ศาสนา</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="religion" value="<?= $data['religion'] ?>">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium mb-1">ส่วนสูง</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="height" value="<?= $data['height'] ?>">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium mb-1">น้ำหนัก</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="weight" value="<?= $data['weight'] ?>">
                </div>
            </div>
            <!-- เบอร์โทร อีเมล LINE ID -->
            <div class="grid grid-cols-12 gap-4 mb-4">
                <div class="col-span-4">
                    <label class="block text-sm font-medium mb-1">เบอร์โทร</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="phone" value="<?= $data['phone'] ?>">
                </div>
                <div class="col-span-4">
                    <label class="block text-sm font-medium mb-1">อีเมล</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="email" value="<?= $data['email'] ?>">
                </div>
                <div class="col-span-4">
                    <label class="block text-sm font-medium mb-1">LINE ID</label>
                    <input type="text" class="w-full border rounded-md px-3 py-2" name="line_id" value="<?= $data['line_id'] ?>">
                </div>
            </div>
            <!-- ที่อยู่ -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">ที่อยู่</label>
                <textarea class="w-full border rounded-md px-3 py-2" name="address" rows="1"><?= $data['address'] ?></textarea>
            </div>

            <!-- ทหาร  -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2"><b>สถานภาพทางทหาร</b></label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php
                    $militaryOptions = [
                        'ยังไม่เกณฑ์ทหาร',
                        'เกณฑ์ทหารแล้ว',
                        'จบ นศท. ชั้นปีที่ 3',
                        'ได้รับการยกเว้น'
                    ];
                    ?>
                    <?php foreach ($militaryOptions as $option): 
                        $id = strtolower(str_replace([' ', '.', 'ชั้นปีที่'], '_', $option));
                    ?>
                        <label class="inline-flex items-center space-x-2">
                            <input type="radio" name="military" value="<?= $option ?>"
                                class="form-radio text-blue-600" id="<?= $id ?>"
                                <?= $data['military'] == $option ? 'checked' : '' ?>>
                            <span><?= $option ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- โรคประจำตัว -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2"><b>ท่านมีโรคประจำตัวหรือไม่</b></label>
                <div class="flex flex-wrap gap-4 items-center">
                    <label class="inline-flex items-center space-x-2">
                        <input type="radio" name="congenital_disease_radio" value="ไม่มี"
                            class="form-radio text-blue-600"
                            <?= ($data['congenital_disease'] === 'ไม่มี') ? 'checked' : '' ?>>
                        <span>ไม่มี</span>
                    </label>

                    <label class="inline-flex items-center space-x-2">
                        <input type="radio" name="congenital_disease_radio" value="มี"
                            class="form-radio text-blue-600"
                            <?= ($data['congenital_disease'] !== 'ไม่มี') ? 'checked' : '' ?>>
                        <span>มี (โปรดระบุ)</span>
                    </label>

                    <input type="text" name="congenital_disease_text"
                        id="congenital_disease_text"
                        class="flex-1 min-w-[200px] border rounded-md px-3 py-2"
                        value="<?= ($data['congenital_disease'] !== 'ไม่มี') ? $data['congenital_disease'] : '' ?>">
                </div>
            </div>

            <!-- อวัยวะพิการ -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2"><b>ท่านมีอวัยวะส่วนไหนที่พิการบ้าง</b></label>
                <div class="flex flex-wrap gap-4 items-center">
                    <label class="inline-flex items-center space-x-2">
                        <input type="radio" name="disabled_radio" value="ปกติ"
                            class="form-radio text-blue-600"
                            <?= ($data['disabled'] === 'ปกติ') ? 'checked' : '' ?>>
                        <span>ปกติ</span>
                    </label>

                    <label class="inline-flex items-center space-x-2">
                        <input type="radio" name="disabled_radio" value="พิการ"
                            class="form-radio text-blue-600"
                            <?= ($data['disabled'] !== 'ปกติ') ? 'checked' : '' ?>>
                        <span>มี (โปรดระบุ)</span>
                    </label>

                    <input type="text" name="disabled_text"
                        id="disabled_text"
                        class="flex-1 min-w-[200px] border rounded-md px-3 py-2"
                        value="<?= ($data['disabled'] !== 'ปกติ') ? $data['disabled'] : '' ?>">
                </div>
            </div>


            
            <!-- ประวัติการศึกษา -->
            <div class="mb-6">
                <h4 class="text-lg font-bold underline mb-4">ประวัติการศึกษา</h4>
                <h5 class="text-md font-semibold mb-3">การศึกษาสูงสุด</h5>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="u_school" class="block font-medium mb-1">ชื่อสถาบัน</label>
                        <input type="text" class="w-full border rounded-md px-3 py-2" id="u_school" name="u_school"
                            value="<?= htmlspecialchars($data['u_school'] ?? '') ?>">
                    </div>

                    <div>
                        <label for="u_year" class="block font-medium mb-1">ปีการศึกษาที่จบ</label>
                        <input type="text" class="w-full border rounded-md px-3 py-2" id="u_year" name="u_year"
                            value="<?= htmlspecialchars($data['u_year'] ?? '') ?>">
                    </div>

                    <div class="col-md-3 margin_bt">
                        <label for="u_gpa" class="block font-medium mb-1">เกรดเฉลี่ย</label>
                        <input type="text" class="w-full border rounded-md px-3 py-2" id="u_gpa" name="u_gpa"
                            value="<?= htmlspecialchars($data['u_gpa'] ?? '') ?>">
                    </div>
                
                
                    <div>
                        <label for="u_educational" class="block font-medium mb-1">วุฒิการศึกษาที่ได้รับ</label>
                        <select class="w-full border rounded-md px-3 py-2" id="u_educational" name="u_educational">
                            <option disabled value="">กรุณาเลือกวุฒิการศึกษาที่ได้รับ</option>
                            <?php
                            $educationalOptions = ['ปริญญาโท', 'ปริญญาตรี', 'ปวส.', 'ปวช.', 'มัธยมศึกษาตอนปลาย', 'มัธยมศึกษาตอนต้น', 'ประถมศึกษา'];
                            foreach ($educationalOptions as $option) {
                                $selected = ($data['u_educational'] ?? '') === $option ? 'selected' : '';
                                echo "<option value=\"$option\" $selected>$option</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="u_major" class="block font-medium mb-1">สาขา/วิชาเอก</label>
                        <input type="text" class="w-full border rounded-md px-3 py-2" id="u_major" name="u_major"
                            value="<?= htmlspecialchars($data['u_major'] ?? '') ?>">
                    </div>
                </div>
            </div>
            

            <!-- การศึกษาก่อนหน้า -->
            <div class="mb-6">
                <h5 class="text-md font-semibold mb-3">การศึกษาก่อนหน้า</h5>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="v_school" class="block font-medium mb-1">ชื่อสถาบัน</label>
                        <input type="text" name="v_school" id="v_school"
                            class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['v_school'] ?? '') ?>">
                    </div>

                    <div>
                        <label for="v_year" class="block font-medium mb-1">ปีที่จบ</label>
                        <input type="text" name="v_year" id="v_year"
                            class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['v_year'] ?? '') ?>">
                    </div>

                    <div>
                        <label for="v_gpa" class="block font-medium mb-1">เกรดเฉลี่ย</label>
                        <input type="text" name="v_gpa" id="v_gpa"
                            class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['v_gpa'] ?? '') ?>">
                    </div>

                    <div>
                        <label for="v_educational" class="block font-medium mb-1">วุฒิการศึกษา</label>
                        <select name="v_educational" id="v_educational"
                            class="w-full border rounded-md px-3 py-2">
                            <option disabled value="">เลือกวุฒิ</option>
                            <?php
                            foreach ($educationalOptions as $opt) {
                                $sel = ($data['v_educational'] ?? '') === $opt ? 'selected' : '';
                                echo "<option value='$opt' $sel>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="v_major" class="block font-medium mb-1">สาขา/วิชาเอก</label>
                        <input type="text" name="v_major" id="v_major"
                            class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['v_major'] ?? '') ?>">
                    </div>
                </div>
            </div>


            <!-- ประวัติการทำงาน -->
            <br><h4 class="text-lg font-bold underline mb-4">ประวัติการทำงาน</h4>
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="<?= $i > 1 ? 'mb-6 border-t border-gray-200 pt-4' : 'mb-6 pt-2' ?>">
                    <h5 class="text-md font-semibold text-gray-800 mb-3">การทำงานที่ <?= $i ?></h5>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label for="company_<?= $i ?>" class="block font-medium mb-1">ชื่อสถานที่ทำงาน</label>
                            <input type="text" name="company_<?= $i ?>" id="company_<?= $i ?>"
                                class="w-full border rounded-md px-3 py-2"
                                value="<?= htmlspecialchars($data["company_$i"] ?? '') ?>" placeholder="กรอกชื่อสถานที่ทำงาน">
                        </div>

                        <div>
                            <label for="position_<?= $i ?>" class="block font-medium mb-1">ตำแหน่ง</label>
                            <input type="text" name="position_<?= $i ?>" id="position_<?= $i ?>"
                                class="w-full border rounded-md px-3 py-2"
                                value="<?= htmlspecialchars($data["position_$i"] ?? '') ?>" placeholder="กรอกตำแหน่ง">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                        <div>
                            <label for="datestart_<?= $i ?>" class="block font-medium mb-1">วันที่เริ่มงาน</label>
                            <input type="date" name="datestart_<?= $i ?>" id="datestart_<?= $i ?>"
                                class="w-full border rounded-md px-3 py-2"
                                value="<?= isset($data["datestart_$i"]) && $data["datestart_$i"] instanceof DateTime ? $data["datestart_$i"]->format('Y-m-d') : '' ?>">
                        </div>

                        <div>
                            <label for="dateend_<?= $i ?>" class="block font-medium mb-1">วันที่สิ้นสุดงาน</label>
                            <input type="date" name="dateend_<?= $i ?>" id="dateend_<?= $i ?>"
                                class="w-full border rounded-md px-3 py-2"
                                value="<?= isset($data["dateend_$i"]) && $data["dateend_$i"] instanceof DateTime ? $data["dateend_$i"]->format('Y-m-d') : '' ?>">
                        </div>

                        <div>
                            <label for="salary_<?= $i ?>" class="block font-medium mb-1">เงินเดือนที่ได้รับ</label>
                            <input type="text" name="salary_<?= $i ?>" id="salary_<?= $i ?>"
                                class="w-full border rounded-md px-3 py-2"
                                value="<?= htmlspecialchars($data["salary_$i"] ?? '') ?>" placeholder="กรอกเงินเดือน">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="detail_work_<?= $i ?>" class="block font-medium mb-1">ลักษณะงานโดยย่อ</label>
                            <input type="text" name="detail_work_<?= $i ?>" id="detail_work_<?= $i ?>"
                                class="w-full border rounded-md px-3 py-2"
                                value="<?= htmlspecialchars($data["detail_work_$i"] ?? '') ?>" maxlength="50" placeholder="กรอกลักษณะงาน">
                        </div>

                        <div>
                            <label for="remark_leave_<?= $i ?>" class="block font-medium mb-1">สาเหตุที่ลาออก</label>
                            <input type="text" name="remark_leave_<?= $i ?>" id="remark_leave_<?= $i ?>"
                                class="w-full border rounded-md px-3 py-2"
                                value="<?= htmlspecialchars($data["remark_leave_$i"] ?? '') ?>" maxlength="50" placeholder="กรอกเหตุผลลาออก">
                        </div>
                    </div>
                </div>
            <?php endfor; ?>




            <div><hr class="style-one"></div><br>
            <!-- ประวัติครอบครัว -->
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-900 mb-4">ประวัติครอบครัว</h4>

                <!-- ข้อมูลบิดา -->
                <h5 class="text-md font-semibold text-gray-800 mb-3">ข้อมูลบิดา</h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                    <div>
                        <label class="block font-medium mb-1">ชื่อ-นามสกุล บิดา</label>
                        <input type="text" name="father_name" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['father_name'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">อายุ</label>
                        <input type="text" name="father_age" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['father_age'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">อาชีพ</label>
                        <input type="text" name="father_occupation" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['father_occupation'] ?? '') ?>">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-3">
                    <div class="md:col-span-3">
                        <label class="block font-medium mb-1">ที่อยู่/สถานที่ทำงาน</label>
                        <input type="text" name="father_Place_work" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['father_Place_work'] ?? '') ?>">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block font-medium mb-1">โทรศัพท์มือถือ</label>
                        <input type="text" name="father_talephone" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['father_talephone'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">สถานภาพชีวิต</label>
                        <div class="flex gap-4 mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="father_status" value="ยังมีชีวิต"
                                    class="form-check-input" <?= ($data['father_status'] ?? '') === 'ยังมีชีวิต' ? 'checked' : '' ?>>
                                <span class="ml-2">ยังมีชีวิต</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="father_status" value="เสียชีวิต"
                                    class="form-check-input" <?= ($data['father_status'] ?? '') === 'เสียชีวิต' ? 'checked' : '' ?>>
                                <span class="ml-2">เสียชีวิต</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>




            <!-- ข้อมูลมารดา -->
            <div class="mb-8">
                <h5 class="text-md font-semibold text-gray-800 mb-3">ข้อมูลมารดา</h5>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                    <div>
                        <label class="block font-medium mb-1">ชื่อ-นามสกุล มารดา</label>
                        <input type="text" name="mother_name" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['mother_name'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">อายุ</label>
                        <input type="text" name="mother_age" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['mother_age'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">อาชีพ</label>
                        <input type="text" name="mother_occupation" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['mother_occupation'] ?? '') ?>">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-3">
                    <div class="md:col-span-3">
                        <label class="block font-medium mb-1">ที่อยู่/สถานที่ทำงาน</label>
                        <input type="text" name="mother_Place_work" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['mother_Place_work'] ?? '') ?>">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block font-medium mb-1">โทรศัพท์มือถือ</label>
                        <input type="text" name="mother_talephone" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['mother_talephone'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">สถานภาพชีวิต</label>
                        <div class="flex gap-4 mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="mother_status" value="ยังมีชีวิต"
                                    class="form-check-input" <?= ($data['mother_status'] ?? '') === 'ยังมีชีวิต' ? 'checked' : '' ?>>
                                <span class="ml-2">ยังมีชีวิต</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="mother_status" value="เสียชีวิต"
                                    class="form-check-input" <?= ($data['mother_status'] ?? '') === 'เสียชีวิต' ? 'checked' : '' ?>>
                                <span class="ml-2">เสียชีวิต</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ข้อมูลพี่น้อง -->
            <div class="mb-8">
                <h5 class="text-md font-semibold text-gray-800 mb-3">ข้อมูลพี่น้อง</h5>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-1">จำนวนพี่น้องทั้งหมดรวมตัวท่าน</label>
                        <input type="text" name="num_bro_sis" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['num_bro_sis'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">ท่านเป็นบุตรคนที่</label>
                        <input type="text" name="num_sir" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['num_sir'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- ข้อมูลคู่สมรส -->
            <div class="mb-8">
                <h5 class="text-md font-semibold text-gray-800 mb-3">ข้อมูลคู่สมรส</h5>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                    <div>
                        <label class="block font-medium mb-1">ชื่อ-นามสกุล คู่สมรส</label>
                        <input type="text" name="spouse_name" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['spouse_name'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">อายุ</label>
                        <input type="text" name="spouse_age" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['spouse_age'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">อาชีพ</label>
                        <input type="text" name="spouse_occupation" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['spouse_occupation'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">โทรศัพท์มือถือ</label>
                        <input type="text" name="spouse_talephone" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['spouse_talephone'] ?? '') ?>">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-3">
                    <div class="md:col-span-8">
                        <label class="block font-medium mb-1">ที่อยู่/สถานที่ทำงาน</label>
                        <input type="text" name="spouse_Place_work" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['spouse_Place_work'] ?? '') ?>">
                    </div>
                    <div class="md:col-span-4">
                        <label class="block font-medium mb-1">จำนวนบุตร</label>
                        <input type="text" name="children" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['children'] ?? '') ?>">
                    </div>
                </div>
            </div>


            <!-- ทักษะอื่นๆ -->
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-900 mb-4">ทักษะอื่นๆ</h4>

                <!-- ทักษะภาษาไทย -->
                <h5 class="text-md font-semibold text-gray-800 mb-2">ทักษะด้านภาษาไทย</h5>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <?php
                    $thaiSkills = [
                        'thai_listen' => 'การฟัง',
                        'thai_speak' => 'การพูด',
                        'thai_read' => 'การอ่าน',
                        'thai_write' => 'การเขียน'
                    ];
                    $levels = ['ดีมาก', 'ดี', 'ปานกลาง', 'ไม่ได้'];
                    foreach ($thaiSkills as $field => $label) {
                    ?>
                        <div>
                            <label class="block font-medium mb-1"><?= $label ?></label>
                            <select name="<?= $field ?>" class="w-full border rounded-md px-3 py-2">
                                <option value="" disabled>--เลือก--</option>
                                <?php foreach ($levels as $level): ?>
                                    <option value="<?= $level ?>" <?= ($data[$field] ?? '') === $level ? 'selected' : '' ?>><?= $level ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>

                <!-- ทักษะภาษาอังกฤษ -->
                <h5 class="text-md font-semibold text-gray-800 mb-2">ทักษะด้านภาษาอังกฤษ</h5>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <?php
                    $engSkills = [
                        'eng_listen' => 'การฟัง',
                        'eng_speak' => 'การพูด',
                        'eng_read' => 'การอ่าน',
                        'eng_write' => 'การเขียน'
                    ];
                    foreach ($engSkills as $field => $label) {
                    ?>
                        <div>
                            <label class="block font-medium mb-1"><?= $label ?></label>
                            <select name="<?= $field ?>" class="w-full border rounded-md px-3 py-2">
                                <option value="" disabled>--เลือก--</option>
                                <?php foreach ($levels as $level): ?>
                                    <option value="<?= $level ?>" <?= ($data[$field] ?? '') === $level ? 'selected' : '' ?>><?= $level ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>

                <!-- อื่นๆ -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-medium mb-1">ภาษาอื่นๆ</label>
                        <input type="text" name="other_languages" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['other_languages'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">การใช้โปรแกรมคอมพิวเตอร์</label>
                        <input type="text" name="computer_skill" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['computer_skill'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">ความสามารถพิเศษ/งานอดิเรก</label>
                        <input type="text" name="talent_skill" class="w-full border rounded-md px-3 py-2"
                            value="<?= htmlspecialchars($data['talent_skill'] ?? '') ?>">
                    </div>
                </div>
            </div>


            <!-- ช่องทางการสมัคร -->
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-900 mb-4">ช่องทางการสมัคร / การขับขี่</h4>

                <!-- ช่องทางการสมัคร -->
                <h5 class="text-sm font-semibold text-gray-700 mt-4 mb-2">ท่านทราบข้อมูลสมัครงานทางช่องทางไหน</h5>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-2 mb-4">
                    <?php
                    $newsOptions = ['เว็บไซต์บริษัท', 'Facebook บริษัท', 'กรมจัดหางาน', 'ญาติพี่น้องหรือเพื่อน ที่ทำงานในบริษัท', 'เว็บไซต์ท้องถิ่น'];
                    foreach ($newsOptions as $option) {
                        $checked = in_array($option, $newsArray) ? 'checked' : '';
                        echo "<label class='inline-flex items-center'>
                                <input type='checkbox' name='checkboxnews[]' value='$option' class='form-checkbox h-4 w-4 text-blue-600' $checked>
                                <span class='ml-2'>$option</span>
                            </label>";
                    }
                    ?>
                </div>

                <!-- สามารถขับขี่รถ -->
                <h5 class="text-sm font-semibold text-gray-700 mt-4 mb-2">ท่านสามารถขับขี่รถ</h5>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-x-4 gap-y-2 mb-4">
                    <?php
                    $driverOptions = ['รถจักรยานยนต์', 'รถยนต์', 'รถโฟล์คลิฟท์', 'รถ 6 ล้อ', 'รถ 10 ล้อ'];
                    foreach ($driverOptions as $option) {
                        $checked = in_array($option, $driverArray) ? 'checked' : '';
                        echo "<label class='inline-flex items-center'>
                                <input type='checkbox' name='checkboxdriver[]' value='$option' class='form-checkbox h-4 w-4 text-blue-600' $checked>
                                <span class='ml-2'>$option</span>
                            </label>";
                    }
                    ?>
                </div>

                <!-- ใบอนุญาตขับขี่ -->
                <h5 class="text-sm font-semibold text-gray-700 mt-4 mb-2">ท่านมีใบอนุญาตขับขี่</h5>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-2">
                    <?php
                    $licenseOptions = ['รถจักรยานยนต์', 'รถยนต์', 'รถ 6 ล้อ (ประเภท 2)'];
                    foreach ($licenseOptions as $option) {
                        $checked = in_array($option, $licenseArray) ? 'checked' : '';
                        echo "<label class='inline-flex items-center'>
                                <input type='checkbox' name='checkboxdrive_license[]' value='$option' class='form-checkbox h-4 w-4 text-blue-600' $checked>
                                <span class='ml-2'>$option</span>
                            </label>";
                    }
                    ?>
                </div>
            </div>


            <!-- บุคคลที่รู้จักและบุคคลอ้างอิง -->
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-900 mb-4">บุคคลที่รู้จักในบริษัท / บุคคลอ้างอิง</h4>

                <!-- คนที่รู้จัก -->
                <h5 class="text-sm font-semibold text-gray-700 mb-2">บุคคลที่ท่านรู้จักที่ทำงานในบริษัทนี้</h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block font-medium mb-1">ชื่อ-นามสกุล</label>
                        <input type="text" name="person_name" value="<?= htmlspecialchars($data['person_name'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">ตำแหน่งงาน</label>
                        <input type="text" name="person_position" value="<?= htmlspecialchars($data['person_position'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">ความสัมพันธ์</label>
                        <input type="text" name="person_relations" value="<?= htmlspecialchars($data['person_relations'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                    </div>
                </div>

                <!-- บุคคลอ้างอิง -->
                <h5 class="text-sm font-semibold text-gray-700 mb-2">บุคคลอ้างอิง</h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block font-medium mb-1">ชื่อ-นามสกุล</label>
                        <input type="text" name="person_referen_name" value="<?= htmlspecialchars($data['person_referen_name'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">ตำแหน่ง</label>
                        <input type="text" name="person_referen_position" value="<?= htmlspecialchars($data['person_referen_position'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">เบอร์โทรศัพท์</label>
                        <input type="text" name="person_referen_phone" value="<?= htmlspecialchars($data['person_referen_phone'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-1">ที่อยู่ หรือ สถานที่ทำงาน</label>
                        <input type="text" name="person_referen_address" value="<?= htmlspecialchars($data['person_referen_address'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">ความสัมพันธ์</label>
                        <input type="text" name="person_referen_relations" value="<?= htmlspecialchars($data['person_referen_relations'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                    </div>
                </div>
            </div>


            <!-- ข้อตกลง -->
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-900 mb-4">ข้อตกลง / Agreement</h4>

                <?php
                function renderRadio($label, $name, $value, $yesLabel = 'ได้', $noLabel = 'ไม่ได้', $yesValue = 'ได้', $noValue = 'ไม่ได้') {
                    ?>
                    <div class="mb-4">
                        <label class="block font-medium text-gray-800 mb-2"><?= $label ?></label>
                        <div class="flex items-center gap-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="<?= $name ?>" value="<?= $yesValue ?>" class="form-check-input" <?= $value === $yesValue ? 'checked' : '' ?>>
                                <span class="ml-2"><?= $yesLabel ?></span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="<?= $name ?>" value="<?= $noValue ?>" class="form-check-input" <?= $value === $noValue ? 'checked' : '' ?>>
                                <span class="ml-2"><?= $noLabel ?></span>
                            </label>
                        </div>
                    </div>
                    <?php
                }

                renderRadio(
                    '1.ท่านยินดีให้เราติดต่อสอบถามกับบริษัทที่ท่านทำงานอยู่หรือเคยทำงาน รวมถึงตรวจสอบวุฒิการศึกษาและคุณสมบัติของท่านหรือไม่',
                    'imformation',
                    $data['imformation'] ?? ''
                );

                renderRadio(
                    '2.ท่านเคยถูกจับหรือได้รับโทษในคดีอาญาโดยมีคำพิพากษาให้จำคุกหรือไม่',
                    'penalize',
                    $data['penalize'] ?? '',
                    'เคย',
                    'ไม่เคย',
                    'เคย',
                    'ไม่เคย'
                );

                renderRadio(
                    '3.ท่านเคยถูกให้ออกจากงานเนื่องจากปัญหาด้านความประพฤติหรือประสิทธิภาพการทำงานหรือไม่',
                    'dismiss',
                    $data['dismiss'] ?? '',
                    'เคย',
                    'ไม่เคย',
                    'เคย',
                    'ไม่เคย'
                );

                renderRadio(
                    '4.ท่านมีรายได้จากแหล่งอื่น เช่น เบี้ยหวัด บำเหน็จ หรือค่าตอบแทนจากการเจ็บป่วยหรือไม่',
                    'income_other',
                    $data['income_other'] ?? ''
                );

                renderRadio(
                    '5.ขณะนี้ท่านมีอาการเจ็บป่วย โรคเรื้อรัง หรือภาวะทางร่างกายอื่น ๆ ที่อยู่ในความดูแลของแพทย์หรือไม่',
                    'health',
                    $data['health'] ?? '',
                    'ใช่',
                    'ไม่ใช่',
                    'ใช่',
                    'ไม่ใช่'
                );

                renderRadio(
                    '6.หากท่านได้รับเข้าเป็นพนักงาน ท่านยินยอมให้บริษัทฯ ปรับเปลี่ยนตำแหน่งหน้าที่ตามความเหมาะสม โดยไม่ลดค่าจ้างหรือผลประโยชน์หรือไม่',
                    'move_job',
                    $data['move_job'] ?? ''
                );
                ?>
            </div>


            <div><hr class="style-one"></div>
            <!-- ข้อมูลเพิ่มเติม -->
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-900 mb-4">ข้อมูลเพิ่มเติม</h4>


                <?php
                $questions = [
                    'more_infor1' => '1. กรุณายกตัวอย่างการตั้งเป้าหมายที่ท้าทายของท่าน และอธิบายถึงวิธีการฝ่าฟันอุปสรรคต่าง ๆ จนกระทั่งประสบความสำเร็จ',
                    'more_infor2' => '2. กรุณาสรุปสถานการณ์ที่ท่านได้เสนอความคิดริเริ่มในการทำงานสำคัญ พร้อมทั้งเป็นผู้นำให้ทีมทำงานจนสำเร็จตามเป้าหมายที่วางไว้',
                    'more_infor3' => '3. กรุณาอธิบายสถานการณ์การแก้ปัญหาที่ท่านต้องค้นคว้าข้อมูลที่เกี่ยวข้อง แยกแยะประเด็นสำคัญ และตัดสินใจแนวทางดำเนินการเพื่อให้บรรลุผลตามที่ต้องการ',
                    'more_infor4' => '4. กรุณาอธิบายสถานการณ์ที่ท่านนำเสนอหรือชี้แจงประเด็น โดยใช้ข้อมูลที่ถูกต้องและน่าเชื่อถือ เพื่อสร้างความเห็นพ้องในการทำงานร่วมกับผู้อื่นอย่างมีประสิทธิภาพ',
                    'more_infor5' => '5. กรุณายกตัวอย่างสถานการณ์ที่ท่านทำงานร่วมกับผู้อื่นอย่างมีประสิทธิภาพ เพื่อให้งานสำคัญประสบความสำเร็จตามเป้าหมาย',
                    'more_infor6' => '6. กรุณาอธิบายถึงความคิดสร้างสรรค์ที่ท่านได้ริเริ่มขึ้น และมีบทบาทสำคัญในการสนับสนุนให้กิจกรรมหรือโครงการประสบความสำเร็จ'
                ];

                foreach ($questions as $key => $label) {
                    ?>
                    <div class="mb-5">
                        <label for="<?= $key ?>" class="block text-gray-800 font-medium mb-2"><?= $label ?></label>
                        <textarea
                            class="form-control w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-blue-200 focus:outline-none"
                            id="<?= $key ?>"
                            name="<?= $key ?>"
                            rows="3"
                            required><?= htmlspecialchars($data[$key] ?? '') ?></textarea>
                    </div>
                    <?php
                }
                ?>
            </div>


            <!-- แก้ไขรูปหลักฐาน -->
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-900 mb-4">ไฟล์แนบต่างๆ</h4>

                <?php
                $uploadFields = [
                    'picture_upload' => 'รูปถ่ายหน้าตรง',
                    'idcard_upload' => 'สำเนาบัตรประชาชน',
                    'address_upload' => 'สำเนาทะเบียนบ้าน',
                    'transcript_upload' => 'ใบแสดงผลการศึกษา',
                    'malitary_upload' => 'เอกสารรับรองทางทหาร',
                    'driver_upload' => 'เอกสารใบขับขี่',
                    'certify_upload' => 'หนังสือรับรองการทำงาน',
                    'portfolio_upload' => 'แฟ้มผลงาน'
                ];

                $branch = strtolower(substr($data['guest_no'], 0, 3));
                $guestFolder = $data['guest_no'];

                foreach ($uploadFields as $field => $label):
                    $filePath = "file_upload_{$branch}/{$guestFolder}/" . $data[$field];
                    ?>
                    <div class="mb-5">
                        <label class="block font-medium text-gray-800 mb-1"><?= $label ?></label>

                        <?php if (!empty($data[$field])): ?>
                            <div class="flex items-center justify-between mb-2">
                                <a href="<?= $filePath ?>" target="_blank" class="text-sm text-blue-600 underline">
                                    <?= $data[$field] ?>
                                </a>
                                <button type="button"
                                    onclick="deleteFile('<?= $field ?>')"
                                    class="text-sm text-red-500 hover:underline">
                                    ❌ ลบไฟล์
                                </button>
                            </div>
                        <?php endif; ?>

                        <input type="file" name="<?= $field ?>"
                            class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                            file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                <?php endforeach; ?>
            </div>


            <!-- ปุ่มบันทึกข้อมูล -->
            <div class="mt-8">
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-md shadow transition-all duration-200 ease-in-out">
                    บันทึกการแก้ไข
                </button>
            </div>

        </form>
    </div>

    <script>
        // submit form with AJAX
        $("#adminEditForm").on("submit", function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: "update_guest_data.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกข้อมูลเรียบร้อย',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.close();
                        window.opener.location.reload();
                    });
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถบันทึกข้อมูลได้'
                    });
                }
            });
        });




        // Handle radio button changes for congenital disease and disabled status
        function toggleTextField(radioName, textInputId, noValue) {
            const radios = document.getElementsByName(radioName);
            const textInput = document.getElementById(textInputId);

            function updateTextInput() {
                const selected = [...radios].find(r => r.checked)?.value;
                if (selected === noValue) {
                    textInput.value = ''; // Clear
                    textInput.style.display = 'none';
                } else {
                    textInput.style.display = 'block';
                }
            }

            radios.forEach(r => r.addEventListener('change', updateTextInput));
            updateTextInput(); // Initial run
        }

        // Apply to both sections
        toggleTextField("congenital_disease_radio", "congenital_disease_text", "ไม่มี");
        toggleTextField("disabled_radio", "disabled_text", "ปกติ");

        // end of radio button changes



        /// delete file function
        function deleteFile(field) {
            const guest_no = "<?= $guest_no ?>";

            Swal.fire({
                title: "คุณแน่ใจหรือไม่?",
                text: "คุณต้องการลบไฟล์นี้ใช่หรือไม่",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ใช่, ลบเลย!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("delete_file.php", { guest_no, field }, function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบไฟล์เรียบร้อย',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => location.reload());
                    }).fail(() => {
                        Swal.fire("ผิดพลาด", "ไม่สามารถลบไฟล์ได้", "error");
                    });
                }
            });
        }
        /// ปิดการลบไฟล์

    </script>
</body>

</html>
