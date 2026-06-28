<?php
// 1. เริ่มต้น Session เพื่อเข้าถึงข้อมูลที่กำลังล็อกอินอยู่
session_start();

// 2. ล้างค่าตัวแปร Session ทั้งหมด
$_SESSION = array();

// 3. ทำลาย Session Cookie ที่ฝังอยู่ในเบราว์เซอร์ (เพื่อความปลอดภัยสูงสุด)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. ทำลาย Session ในฝั่งเซิร์ฟเวอร์
session_destroy();

// 5. เปลี่ยนทิศทาง (Redirect) กลับไปยังหน้าล็อกอิน
// หมายเหตุ: ปรับแก้ "admin_login.php" ให้ตรงกับชื่อไฟล์หน้าล็อกอินของคุณ
header("Location: admin_login.php");
exit();
?>