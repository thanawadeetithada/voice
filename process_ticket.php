<?php
require_once 'db.php'; // ไฟล์เชื่อม DB ที่คุณมีอยู่แล้ว

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_category = $_POST['form_category'] ?? '';
    $issue_type = $_POST['issue_type'] ?? '';
    $details = $_POST['details'] ?? '';
    $impacts = isset($_POST['impacts']) ? implode(", ", $_POST['impacts']) : '';
    $location = $_POST['location'] ?? '';
    $incident_date = !empty($_POST['incident_date']) ? $_POST['incident_date'] : NULL;
    $identity_status = $_POST['identity'] ?? 'ไม่เปิดเผย';
    $contact_info = $_POST['contact_info'] ?? '';

    // การจัดการอัปโหลดไฟล์ (ถ้ามี)
    $attachment = NULL;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_extension = pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
            $attachment = $target_file;
        }
    }

    // สร้าง Ticket ID แบบสุ่มก่อน เพื่อจอง ID (ป้องกันการชนกัน)
    $year = date('Y'); // ปัจจุบันคือ 2026
    $temp_ticket_id = "VOC-" . $year . "-" . time();

    // บันทึกลง Database
    $stmt = $conn->prepare("INSERT INTO tickets (ticket_id, form_category, issue_type, details, impacts, location, incident_date, attachment, identity_status, contact_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $temp_ticket_id, $form_category, $issue_type, $details, $impacts, $location, $incident_date, $attachment, $identity_status, $contact_info);
    
    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        // อัปเดต Ticket ID ให้สวยงาม (เช่น VOC-2026-0001)
        $formatted_id = "VOC-" . $year . "-" . str_pad($last_id, 4, '0', STR_PAD_LEFT);
        $conn->query("UPDATE tickets SET ticket_id = '$formatted_id' WHERE id = $last_id");
        
        // เด้งไปหน้าติดตามสถานะพร้อมส่ง ID ไป
        header("Location: success.php?id=" . $formatted_id);
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
?>