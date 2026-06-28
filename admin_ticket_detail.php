<?php
require_once 'auth_check.php';
require_once 'db.php';
$current_page = 'tickets';

// ฟังก์ชันจำแนกสีป้ายสถานะ 5 ระดับ
function getStatusColor($status) {
    switch ($status) {
        case 'ปิดเรื่อง':            return 'bg-emerald-100 text-emerald-800 border-emerald-300';
        case 'ดำเนินการแล้ว':        return 'bg-teal-100 text-teal-800 border-teal-300';
        case 'อยู่ระหว่างดำเนินการ': return 'bg-orange-100 text-orange-800 border-orange-300';
        case 'อยู่ระหว่างพิจารณา':   return 'bg-purple-100 text-purple-800 border-purple-300';
        case 'รับเรื่องแล้ว':        return 'bg-blue-100 text-blue-800 border-blue-300';
        default:                     return 'bg-slate-100 text-slate-800 border-slate-300';
    }
}

// =====================================================================
// ส่วนที่ 1: บันทึกการอัปเดตข้อมูลจากแอดมิน (POST Request)
// =====================================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_ticket'])) {
    $ticket_id_to_update = intval($_POST['ticket_id']);
    $new_status          = $_POST['status'];
    $new_urgency         = $_POST['urgency'];
    $new_root_cause      = trim($_POST['root_cause']);
    $new_action_taken    = trim($_POST['action_taken']);
    $new_feedback        = trim($_POST['feedback']);
    $admin_id            = $_SESSION['admin_id'] ?? 0;

    // ดึงเวลาเก่ามาตรวจสอบว่าช่องไหนยังเป็น NULL อยู่บ้าง (ป้องกันการประทับเวลาทับของเดิมเมื่อกดบันทึกซ้ำ)
    $q = $conn->query("SELECT admin_received, review_at, in_progress_at, resolved_at, closed_at FROM tickets WHERE id = $ticket_id_to_update");
    $old = $q->fetch_assoc();

    // ตรรกะน้ำตก (Waterfall Auto-fill): จะประทับเวลาและ ID ก็ต่อเมื่อช่องนั้นยังว่างอยู่
    $s_rec  = empty($old['admin_received']) ? ", admin_received = '$admin_id'"                             : "";
    $s_rev  = empty($old['review_at'])      ? ", admin_review = '$admin_id', review_at = NOW()"           : "";
    $s_prog = empty($old['in_progress_at']) ? ", admin_in_progress = '$admin_id', in_progress_at = NOW()" : "";
    $s_res  = empty($old['resolved_at'])    ? ", admin_resolved = '$admin_id', resolved_at = NOW()"       : "";
    $s_clos = empty($old['closed_at'])      ? ", admin_closed = '$admin_id', closed_at = NOW()"           : "";

    $sql_time_set = "";
    if ($new_status === 'รับเรื่องแล้ว') {
        $sql_time_set = $s_rec;
    } elseif ($new_status === 'อยู่ระหว่างพิจารณา') {
        $sql_time_set = $s_rec . $s_rev;
    } elseif ($new_status === 'อยู่ระหว่างดำเนินการ') {
        $sql_time_set = $s_rec . $s_rev . $s_prog;
    } elseif ($new_status === 'ดำเนินการแล้ว') {
        $sql_time_set = $s_rec . $s_rev . $s_prog . $s_res;
    } elseif ($new_status === 'ปิดเรื่อง') {
        $sql_time_set = $s_rec . $s_rev . $s_prog . $s_res . $s_clos;
    }

    $stmt = $conn->prepare("UPDATE tickets SET status = ?, urgency = ?, root_cause = ?, action_taken = ?, feedback = ? $sql_time_set WHERE id = ?");
    $stmt->bind_param("sssssi", $new_status, $new_urgency, $new_root_cause, $new_action_taken, $new_feedback, $ticket_id_to_update);
    $stmt->execute();
    
    header("Location: admin_tickets.php");
    exit;
}

// =====================================================================
// ส่วนที่ 2: ดึงข้อมูล Ticket มาแสดงผล (GET Request)
// =====================================================================
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

// [Silent Touch]: ถ้าตั๋วนี้ยังไม่เคยมีใครเปิดอ่าน (admin_received เป็น NULL) ให้แสตมป์ ID แอดมินคนนี้ทันที!
if ($ticket && empty($ticket['admin_received']) && isset($_SESSION['admin_id'])) {
    $aid = intval($_SESSION['admin_id']);
    $conn->query("UPDATE tickets SET admin_received = $aid WHERE id = $id");
    $ticket['admin_received'] = $aid;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details - VOICESRI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Sarabun', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 h-screen flex overflow-hidden">

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 z-20 lg:hidden backdrop-blur-sm hidden" onclick="toggleSidebar()"></div>
    
    <div id="sidebar" class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-slate-200 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shrink-0">
        <div class="h-20 flex items-center px-6 gap-3 border-b border-slate-100 bg-emerald-50/50">
            <img src="img/logo.png" class="w-8 h-8 rounded-full border border-emerald-200 shadow-sm object-contain" alt="Logo" onerror="this.style.display='none'">
            <span class="text-xl font-bold text-slate-800 tracking-tight">VOICE<span class="text-emerald-600">SRI</span></span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <?php
            $menu = [
                'dashboard' => ['url' => 'admin_dashboard.php', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                'tickets'   => ['url' => 'admin_tickets.php',   'icon' => 'inbox',            'label' => 'Tickets Inbox'],
                'settings'  => ['url' => 'admin_settings.php',  'icon' => 'settings',         'label' => 'Settings']
            ];
            foreach($menu as $key => $item):
                $activeClass = ($current_page == $key) ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : 'text-slate-500 hover:bg-emerald-50 hover:text-emerald-700';
            ?>
            <a href="<?php echo $item['url']; ?>" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors <?php echo $activeClass; ?>">
                <i data-lucide="<?php echo $item['icon']; ?>" class="w-5 h-5"></i> <?php echo $item['label']; ?>
            </a>
            <?php endforeach; ?>
        </nav>
        <div class="p-4 border-t border-slate-100">
            <a href="logout.php" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-500 hover:text-red-500 transition-colors bg-slate-50 rounded-xl">
                <i data-lucide="log-out" class="w-5 h-5"></i> ออกจากระบบ
            </a>
        </div>
    </div>

    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full relative">
        
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 md:px-8 flex items-center justify-between sticky top-0 z-10 shrink-0">
            <div class="flex items-center gap-3 w-full">
                <button onclick="toggleSidebar()" class="p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="flex items-center gap-2 md:gap-4 shrink-0">
                <div class="h-8 w-px bg-slate-200 mx-2"></div>
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-slate-800"><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></p>
                        <p class="text-xs text-emerald-600 font-medium">ผู้ดูแลระบบ</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-100 border border-emerald-200 rounded-full flex items-center justify-center text-emerald-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-8 lg:p-10 w-full">
            <?php if($ticket): ?>
            <div class="w-full space-y-6 md:space-y-8">
                
                <div>
                    <a href="admin_tickets.php" class="inline-flex items-center gap-2 text-slate-500 hover:text-emerald-600 text-sm font-semibold transition-colors">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปหน้ารายการ Inbox
                    </a>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-slate-200 w-full">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight"><?php echo htmlspecialchars($ticket['ticket_id']); ?></h1>
                            <span class="px-3 py-1 rounded-lg text-xs font-bold bg-emerald-600 text-white shadow-sm"><?php echo htmlspecialchars($ticket['form_category']); ?></span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 flex flex-wrap items-center gap-x-3 gap-y-1 pt-1">
                            <span class="inline-flex items-center gap-1"><i data-lucide="clock" class="w-4 h-4 text-slate-400"></i> วันที่แจ้ง: <?php echo date('d M Y, H:i น.', strtotime($ticket['created_at'])); ?></span>
                            <span class="hidden sm:inline text-slate-300">•</span>
                            <span class="inline-flex items-center gap-1"><i data-lucide="map-pin" class="w-4 h-4 text-red-500"></i> สถานที่/หน่วยงาน: <strong class="text-slate-800"><?php echo htmlspecialchars($ticket['location']); ?></strong></span>
                        </p>
                    </div>
                    <div>
                        <span class="px-4 py-2 rounded-full text-xs sm:text-sm font-extrabold border shadow-sm inline-block whitespace-nowrap <?php echo getStatusColor($ticket['status']); ?>">
                            <?php echo htmlspecialchars($ticket['status']); ?>
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start w-full">
                    
                    <div class="lg:col-span-6 space-y-6 w-full">
                        
                        <h2 class="text-base font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="user-check" class="w-4 h-4 text-emerald-600"></i> ข้อมูลต้นทางจากผู้แจ้ง (Voice Details)
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm">
                                <span class="text-xs text-slate-400 font-medium block">ประเภทเรื่อง:</span>
                                <span class="text-sm font-bold text-slate-800 mt-0.5 block"><?php echo htmlspecialchars($ticket['issue_type']); ?></span>
                            </div>
                            <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm">
                                <span class="text-xs text-slate-400 font-medium block">วันที่เกิดเหตุจริง:</span>
                                <span class="text-sm font-bold text-slate-800 mt-0.5 block"><?php echo !empty($ticket['incident_date']) ? date('d/m/Y', strtotime($ticket['incident_date'])) : 'ไม่ได้ระบุ'; ?></span>
                            </div>
                            <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm">
                                <span class="text-xs text-slate-400 font-medium block">สถานะตัวตน:</span>
                                <span class="text-sm font-bold <?php echo ($ticket['identity_status'] == 'เปิดเผยตัวตน') ? 'text-emerald-600' : 'text-slate-700'; ?> mt-0.5 block">
                                    <?php echo htmlspecialchars($ticket['identity_status']); ?>
                                </span>
                            </div>
                        </div>

                        <?php if(!empty($ticket['contact_info'])): ?>
                        <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200 text-blue-900 text-sm flex items-center gap-3">
                            <i data-lucide="phone-call" class="w-5 h-5 text-blue-600 shrink-0"></i>
                            <div>
                                <span class="text-xs text-blue-500 block font-semibold">ช่องทางติดต่อกลับที่ผู้ใช้ระบุ:</span>
                                <span class="font-bold"><?php echo htmlspecialchars($ticket['contact_info']); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">รายละเอียดปัญหา:</label>
                            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line min-h-[120px]">
                                <?php echo htmlspecialchars($ticket['details']); ?>
                            </div>
                        </div>

                        <?php if(!empty($ticket['impacts'])): ?>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-orange-600 uppercase tracking-wider flex items-center gap-1.5">
                                <i data-lucide="alert-triangle" class="w-4 h-4 inline"></i> ผลกระทบที่เกิดขึ้น:
                            </label>
                            <div class="flex flex-wrap gap-2 pt-1">
                                <?php foreach(explode(',', $ticket['impacts']) as $imp): ?>
                                    <span class="px-3 py-1.5 bg-orange-50 border border-orange-200 text-orange-800 rounded-xl text-xs sm:text-sm font-medium shadow-sm">
                                        <?php echo trim(htmlspecialchars($imp)); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if(!empty($ticket['attachment'])): ?>
                        <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-white rounded-xl border border-emerald-200 text-emerald-600 shrink-0">
                                    <i data-lucide="paperclip" class="w-6 h-6"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs font-bold text-slate-700">หลักฐาน/ไฟล์ที่แนบมา</p>
                                    <p class="text-xs text-slate-400 truncate max-w-[200px] sm:max-w-[280px]"><?php echo htmlspecialchars($ticket['attachment']); ?></p>
                                </div>
                            </div>
                            <a href="<?php echo htmlspecialchars($ticket['attachment']); ?>" target="_blank" class="w-full sm:w-auto px-5 py-2.5 bg-white hover:bg-emerald-600 hover:text-white text-emerald-700 border border-emerald-300 hover:border-emerald-600 rounded-xl text-xs font-bold shadow-sm transition-all text-center shrink-0 inline-flex items-center justify-center gap-2">
                                <i data-lucide="external-link" class="w-4 h-4"></i> เปิดดูไฟล์แนบ
                            </a>
                        </div>
                        <?php endif; ?>

                    </div>

                    <div class="lg:col-span-6 bg-slate-100/70 p-6 sm:p-8 rounded-3xl border border-slate-200/80 space-y-6 w-full">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-2">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-white shadow-md">
                                    <i data-lucide="clipboard-edit" class="h-4 w-4"></i>
                                </span>
                                บันทึกผลการดำเนินงาน (Backend Action)
                            </h2>
                            <span class="text-xs text-slate-400">อัปเดตล่าสุด: <?php echo date('d/m/Y H:i', strtotime($ticket['updated_at'])); ?></span>
                        </div>

                        <form method="POST" action="" class="space-y-5">
                            <input type="hidden" name="update_ticket" value="1">
                            <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">อัปเดตสถานะ (Status) <span class="text-red-500">*</span></label>
                                    <select name="status" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all shadow-sm">
                                        <option value="รับเรื่องแล้ว"        <?php if($ticket['status'] == 'รับเรื่องแล้ว') echo 'selected'; ?>>รับเรื่องแล้ว</option>
                                        <option value="อยู่ระหว่างพิจารณา"   <?php if($ticket['status'] == 'อยู่ระหว่างพิจารณา') echo 'selected'; ?>>อยู่ระหว่างพิจารณา</option>
                                        <option value="อยู่ระหว่างดำเนินการ" <?php if($ticket['status'] == 'อยู่ระหว่างดำเนินการ') echo 'selected'; ?>>อยู่ระหว่างดำเนินการ</option>
                                        <option value="ดำเนินการแล้ว"        <?php if($ticket['status'] == 'ดำเนินการแล้ว') echo 'selected'; ?>>ดำเนินการแล้ว</option>
                                        <option value="ปิดเรื่อง"            <?php if($ticket['status'] == 'ปิดเรื่อง') echo 'selected'; ?>>ปิดเรื่อง</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ระดับความด่วน (Urgency Level)</label>
                                    <select name="urgency" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all shadow-sm">
                                        <option value="Low"      <?php if($ticket['urgency'] == 'Low') echo 'selected'; ?>>🟢 ทั่วไป (Low Priority)</option>
                                        <option value="Medium"   <?php if(empty($ticket['urgency']) || $ticket['urgency'] == 'Medium') echo 'selected'; ?>>🟡 ปานกลาง (Medium - SLA 3 วัน)</option>
                                        <option value="High"     <?php if($ticket['urgency'] == 'High') echo 'selected'; ?>>🟠 ด่วน (High - SLA 24 ชม.)</option>
                                        <option value="Critical" <?php if($ticket['urgency'] == 'Critical') echo 'selected'; ?>>🔴 ด่วนที่สุด (Critical - SLA 4 ชม.)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1 flex justify-between">
                                    <span>Root Cause (สาเหตุต้นตอของปัญหา)</span>
                                    <span class="text-[11px] text-slate-400 font-normal">สำหรับผู้รับผิดชอบวิเคราะห์</span>
                                </label>
                                <textarea name="root_cause" rows="2" placeholder="ระบุสาเหตุเชิงลึกที่ทำให้เกิดปัญหานี้..." class="w-full p-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all shadow-sm resize-y"><?php echo htmlspecialchars($ticket['root_cause'] ?? ''); ?></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1 flex justify-between">
                                    <span>Action Taken (การลงมือแก้ไขปัญหา)</span>
                                    <span class="text-[11px] text-slate-400 font-normal">บันทึกทางเทคนิคหลังบ้าน</span>
                                </label>
                                <textarea name="action_taken" rows="3" placeholder="ระบุขั้นตอน วิธีการ หรือมาตรการที่ได้ลงมือแก้ไขไปแล้ว..." class="w-full p-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all shadow-sm resize-y"><?php echo htmlspecialchars($ticket['action_taken'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="bg-emerald-50 p-4 sm:p-5 rounded-2xl border border-emerald-200 shadow-sm space-y-2">
                                <label class="block text-xs font-bold text-emerald-900 flex items-center gap-1.5">
                                    <i data-lucide="message-square-check" class="w-4 h-4 text-emerald-600"></i>
                                    Feedback to Reporter (ปิด Loop ตอบกลับผู้แจ้ง)
                                </label>
                                <p class="text-[11px] text-emerald-700 leading-tight">ข้อความนี้จะถูกส่งไปแสดงให้ผู้ใช้เห็นในหน้าเว็บ "ติดตามสถานะ" (Track)</p>
                                <textarea name="feedback" rows="3" placeholder="พิมพ์คำชี้แจง คำขอบคุณ หรือผลลัพธ์ที่จะสื่อสารให้ผู้แจ้งทราบ..." class="w-full p-3.5 bg-white border border-emerald-300 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all shadow-inner resize-y"><?php echo htmlspecialchars($ticket['feedback'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="pt-4 flex flex-col sm:flex-row items-center justify-end gap-3 border-t border-slate-200">
                                <a href="admin_tickets.php" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-slate-200 text-slate-600 border border-slate-300 rounded-xl text-sm font-bold transition-all text-center">ยกเลิก</a>
                                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-emerald-600/20 inline-flex items-center justify-center gap-2 cursor-pointer">
                                    <i data-lucide="save" class="w-4 h-4"></i> บันทึกผลการดำเนินงาน
                                </button>
                            </div>
                        </form>

                    </div>

                </div>

            </div>
            <?php else: ?>
                <div class="text-center py-20 w-full max-w-lg mx-auto">
                    <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="file-question" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">ไม่พบข้อมูล Ticket ที่เลือกในระบบ</h2>
                    <p class="text-sm text-slate-500 mt-1">ตั๋วปัญหานี้อาจถูกลบออกไปแล้ว หรือลิงก์ไม่ถูกต้อง</p>
                    <a href="admin_tickets.php" class="mt-6 px-6 py-2.5 bg-emerald-600 text-white font-bold text-sm rounded-xl inline-flex items-center gap-2 shadow-md hover:bg-emerald-700 transition-all">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปหน้ารายการ Inbox
                    </a>
                </div>
            <?php endif; ?>

        </main>
    </div>

    <script>
        lucide.createIcons();
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if(sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>