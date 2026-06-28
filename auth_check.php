<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. ป้องกัน Browser จำแคช (แก้ปัญหากดปุ่ม Back ย้อนกลับมาดูหน้าได้หลังกด Logout)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 2. ตรวจสอบ 2 เงื่อนไข: "ต้องล็อกอินแล้ว" AND "ต้องมีสิทธิ์เป็น admin เท่านั้น"
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$is_admin     = isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin';

if (!$is_logged_in || !$is_admin) {
    header("Location: index.php"); // ส่งกลับไปหน้าล็อกอิน (หรือ index.php ตามต้องการ)
    exit;
}
?>