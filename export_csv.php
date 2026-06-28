<?php
require_once 'auth_check.php'; // ป้องกันคนนอกเข้าถึง
require_once 'db.php';

// รับค่าฟิลเตอร์
$selected_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$selected_quarter = isset($_GET['quarter']) ? $_GET['quarter'] : 'all';

// สร้างเงื่อนไข Query
$whereClause = "WHERE YEAR(created_at) = $selected_year";
$filename_suffix = "Y" . $selected_year;

if ($selected_quarter !== 'all') {
    $quarter = intval($selected_quarter);
    $whereClause .= " AND QUARTER(created_at) = $quarter";
    $filename_suffix .= "_Q" . $quarter;
} else {
    $filename_suffix .= "_All";
}

// ตั้งชื่อไฟล์ที่จะให้ดาวน์โหลด
$filename = "VOC_Report_" . $filename_suffix . "_" . date('Ymd_His') . ".csv";

// กำหนด Header เพื่อบังคับดาวน์โหลดเป็นไฟล์ CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// เปิด Output Stream
$output = fopen('php://output', 'w');

// แทรก BOM (Byte Order Mark) เพื่อให้ Excel อ่านภาษาไทย (UTF-8) ได้ถูกต้อง
fputs($output, "\xEF\xBB\xBF");

// กำหนดหัวคอลัมน์ (Headers) ของไฟล์ CSV
$headers = [
    'Ticket ID',
    'หมวดหมู่แบบฟอร์ม',
    'ประเภทเรื่อง',
    'สถานที่/หน่วยงาน',
    'วันที่เกิดเหตุ',
    'รายละเอียด',
    'ผลกระทบ',
    'สถานะตัวตน',
    'ข้อมูลติดต่อกลับ',
    'สถานะปัจจุบัน',
    'ความเร่งด่วน',
    'Root Cause (สาเหตุต้นตอ)',
    'Action Taken (การแก้ไข)',
    'Feedback (ตอบกลับผู้แจ้ง)',
    'วันที่แจ้งเรื่อง',
    'วันที่ปิดเรื่อง'
];
fputcsv($output, $headers);

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM tickets $whereClause ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // จัดเตรียมข้อมูลแต่ละแถวให้ตรงกับหัวคอลัมน์
        $lineData = [
            $row['ticket_id'],
            $row['form_category'],
            $row['issue_type'],
            $row['location'],
            !empty($row['incident_date']) ? date('d/m/Y', strtotime($row['incident_date'])) : '-',
            $row['details'],
            $row['impacts'],
            $row['identity_status'],
            $row['contact_info'],
            $row['status'],
            $row['urgency'],
            $row['root_cause'],
            $row['action_taken'],
            $row['feedback'],
            date('d/m/Y H:i', strtotime($row['created_at'])),
            !empty($row['closed_at']) ? date('d/m/Y H:i', strtotime($row['closed_at'])) : '-'
        ];
        // เขียนข้อมูลลงไฟล์ CSV
        fputcsv($output, $lineData);
    }
}

// ปิด Output Stream
fclose($output);
exit();
?>