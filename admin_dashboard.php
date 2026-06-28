<?php
require_once 'auth_check.php';
require_once 'db.php';

$current_page = 'dashboard';

// --- จัดการตัวกรอง (Filter) ปีและไตรมาส ---
$selected_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$selected_quarter = isset($_GET['quarter']) ? intval($_GET['quarter']) : ceil(date('n') / 3);

// สร้างเงื่อนไข Query
$whereClause = "WHERE YEAR(created_at) = $selected_year";
if ($selected_quarter != 'all') {
    $whereClause .= " AND QUARTER(created_at) = $selected_quarter";
} else {
    $selected_quarter = 'all';
}

// --- 1. ดึงข้อมูลตั๋วทั้งหมดในไตรมาสที่เลือกเพื่อนำมาประมวลผล ---
$sqlAll = "SELECT * FROM tickets $whereClause";
$resultAll = $conn->query($sqlAll);

$tickets = [];
while($row = $resultAll->fetch_assoc()) {
    $tickets[] = $row;
}

$totalTickets = count($tickets);

// ตัวแปรสำหรับเก็บสถิติต่างๆ
$closedTickets = 0;
$suggestionsCount = 0;
$complimentsCount = 0;
$problemCount = 0; // เปลี่ยนมาใช้นับการแจ้งปัญหาแทน
$responseTimes = [];
$slaMetCount = 0;
$slaTotalApplicable = 0;

$categoryCount = [];
$locationCount = [];

foreach ($tickets as $t) {
    // แยกตามหมวดหมู่
    $cat = $t['form_category'];
    $categoryCount[$cat] = ($categoryCount[$cat] ?? 0) + 1;
    
    // แยกตามสถานที่
    $loc = $t['location'];
    $locationCount[$loc] = ($locationCount[$loc] ?? 0) + 1;

    // นับแยกตามประเภทแบบฟอร์ม (form_category)
    if ($cat == 'ข้อเสนอแนะเพื่อพัฒนา') $suggestionsCount++;
    if ($cat == 'ชื่นชมบุคลากร/หน่วยงาน') $complimentsCount++;
    if ($cat == 'แจ้งปัญหา') $problemCount++;

    // การปิดเรื่อง
    if ($t['status'] == 'ปิดเรื่อง') {
        $closedTickets++;
    }

    // Response Time (หาเวลาที่แอดมินเริ่มตอบสนองแรกสุด)
    $created = strtotime($t['created_at']);
    $first_response = null;
    if (!empty($t['review_at'])) $first_response = strtotime($t['review_at']);
    elseif (!empty($t['in_progress_at'])) $first_response = strtotime($t['in_progress_at']);
    
    if ($first_response) {
        $diff_hours = ($first_response - $created) / 3600;
        $responseTimes[] = $diff_hours;
    }

    // SLA Calculation
    if (!empty($t['urgency']) && $t['urgency'] != 'Low') {
        $slaTotalApplicable++;
        if (!empty($t['resolved_at']) || !empty($t['closed_at'])) {
            $end_time = !empty($t['resolved_at']) ? strtotime($t['resolved_at']) : strtotime($t['closed_at']);
            $process_hours = ($end_time - $created) / 3600;
            
            $sla_limit = 72; // Default Medium
            if ($t['urgency'] == 'Critical') $sla_limit = 4;
            if ($t['urgency'] == 'High') $sla_limit = 24;
            
            if ($process_hours <= $sla_limit) {
                $slaMetCount++;
            }
        }
    }
}

// คำนวณเปอร์เซ็นต์
$resolutionPercent = $totalTickets > 0 ? round(($closedTickets / $totalTickets) * 100) : 0;
$slaPercent = $slaTotalApplicable > 0 ? round(($slaMetCount / $slaTotalApplicable) * 100) : 0;

$medianResponseTime = "N/A";
if (count($responseTimes) > 0) {
    sort($responseTimes);
    $count = count($responseTimes);
    
    // แก้ไข: เพิ่ม (int) เพื่อแปลงผลลัพธ์เป็นจำนวนเต็ม ป้องกัน Error จาก Intelephense
    $mid = (int) floor($count / 2); 
    
    if ($count % 2 == 0) {
        $median = ($responseTimes[$mid - 1] + $responseTimes[$mid]) / 2;
    } else {
        $median = $responseTimes[$mid];
    }
    
    // แปลงผลลัพธ์ให้อ่านง่าย
    if ($median < 1) {
        $medianResponseTime = round($median * 60) . " นาที";
    } else {
        $medianResponseTime = round($median, 1) . " ชม.";
    }
}

// --- 2. Top 10 Pain Points ---
$resTopPains = $conn->query("SELECT issue_type, COUNT(*) as count FROM tickets $whereClause GROUP BY issue_type ORDER BY count DESC LIMIT 10");
$topPains = [];
$colors = ['bg-red-500', 'bg-orange-500', 'bg-amber-500', 'bg-emerald-500', 'bg-teal-500', 'bg-cyan-500', 'bg-blue-500', 'bg-indigo-500', 'bg-violet-500', 'bg-fuchsia-500'];
$i = 0;
if($resTopPains && $resTopPains->num_rows > 0) {
    while($row = $resTopPains->fetch_assoc()) {
        $percent = $totalTickets > 0 ? round(($row['count'] / $totalTickets) * 100) : 0;
        $topPains[] = [
            'name' => $row['issue_type'],
            'val' => $percent,
            'count' => $row['count'],
            'color' => $colors[$i % count($colors)]
        ];
        $i++;
    }
}

// --- 3. Recurring Issues (ปัญหาซ้ำซากในพื้นที่เดิม) ---
$resRecurring = $conn->query("SELECT issue_type, location, COUNT(*) as count FROM tickets $whereClause GROUP BY issue_type, location HAVING count > 1 ORDER BY count DESC LIMIT 5");

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - VOICESRI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Sarabun', sans-serif; }
    img { object-fit: contain; border-width: 1px; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 h-screen flex overflow-hidden">

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 z-20 lg:hidden backdrop-blur-sm hidden" onclick="toggleSidebar()"></div>

    <div id="sidebar" class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-slate-200 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shrink-0">
        <div class="h-20 flex items-center px-6 gap-3 border-b border-slate-100 bg-emerald-50/50">
            <img src="img/logo.png" class="w-8 h-8 rounded-full border border-emerald-200 shadow-sm" alt="Logo" onerror="this.style.display='none'">
            <span class="text-xl font-bold text-slate-800 tracking-tight">VOICE<span class="text-emerald-600">SRI</span></span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <?php
            $menu = [
                'dashboard' => ['url' => 'admin_dashboard.php', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                'tickets' => ['url' => 'admin_tickets.php', 'icon' => 'inbox', 'label' => 'Tickets Inbox'],
                'settings' => ['url' => 'admin_settings.php', 'icon' => 'settings', 'label' => 'Settings']
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
            <a href="index.php" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-500 hover:text-red-500 transition-colors bg-slate-50 rounded-xl">
                <i data-lucide="log-out" class="w-5 h-5"></i> กลับหน้าแรก
            </a>
        </div>
    </div>

    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full relative">
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 md:px-6 flex items-center justify-between sticky top-0 z-10 shrink-0">
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

        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 pb-20">
            <div class="space-y-6 max-w-7xl mx-auto">
                
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Executive Dashboard</h1>
                        <p class="text-slate-500 text-sm mt-1">รายงานสรุปผลการรับฟังเสียงสะท้อน (VOC) รายไตรมาส</p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                        <form method="GET" class="flex flex-wrap items-center gap-2 bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm w-full lg:w-auto">
                            <select name="year" class="px-3 py-2 bg-slate-50 rounded-lg text-sm font-medium text-slate-700 outline-none focus:ring-1 focus:ring-emerald-500">
                                <?php for($y = 2024; $y <= date('Y')+1; $y++): ?>
                                    <option value="<?php echo $y; ?>" <?php echo $y == $selected_year ? 'selected' : ''; ?>>ปี <?php echo $y; ?></option>
                                <?php endfor; ?>
                            </select>
                            <select name="quarter" class="px-3 py-2 bg-slate-50 rounded-lg text-sm font-medium text-slate-700 outline-none focus:ring-1 focus:ring-emerald-500">
                                <option value="all" <?php echo $selected_quarter === 'all' ? 'selected' : ''; ?>>ทุกไตรมาส (ทั้งปี)</option>
                                <option value="1" <?php echo $selected_quarter == 1 ? 'selected' : ''; ?>>Q1 (ม.ค. - มี.ค.)</option>
                                <option value="2" <?php echo $selected_quarter == 2 ? 'selected' : ''; ?>>Q2 (เม.ย. - มิ.ย.)</option>
                                <option value="3" <?php echo $selected_quarter == 3 ? 'selected' : ''; ?>>Q3 (ก.ค. - ก.ย.)</option>
                                <option value="4" <?php echo $selected_quarter == 4 ? 'selected' : ''; ?>>Q4 (ต.ค. - ธ.ค.)</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-bold hover:bg-slate-700 transition-colors cursor-pointer">
                                กรองข้อมูล
                            </button>
                        </form>
                        <a href="export_csv.php?year=<?php echo $selected_year; ?>&quarter=<?php echo $selected_quarter; ?>" class="hidden lg:inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition-colors shadow-sm">
                            <i data-lucide="download" class="w-4 h-4"></i> Export Report
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                    <div class="bg-white rounded-2xl p-5 lg:p-6 border border-emerald-100 shadow-sm relative overflow-hidden">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mb-3">
                            <i data-lucide="inbox"></i>
                        </div>
                        <h3 class="text-3xl font-extrabold text-slate-800 mb-1"><?php echo number_format($totalTickets); ?></h3>
                        <p class="text-xs lg:text-sm font-medium text-slate-500">จำนวน VOC ทั้งหมด</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 lg:p-6 border border-blue-100 shadow-sm relative overflow-hidden">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-3">
                            <i data-lucide="clock"></i>
                        </div>
                        <h3 class="text-3xl font-extrabold text-slate-800 mb-1"><?php echo $medianResponseTime; ?></h3>
                        <p class="text-xs lg:text-sm font-medium text-slate-500">Median Response Time</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 lg:p-6 border border-amber-100 shadow-sm relative overflow-hidden">
                        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center mb-3">
                            <i data-lucide="zap"></i>
                        </div>
                        <h3 class="text-3xl font-extrabold text-slate-800 mb-1"><?php echo $slaPercent; ?>%</h3>
                        <p class="text-xs lg:text-sm font-medium text-slate-500">% ตอบกลับภายใน SLA</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 lg:p-6 border border-teal-100 shadow-sm relative overflow-hidden">
                        <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center mb-3">
                            <i data-lucide="check-circle-2"></i>
                        </div>
                        <h3 class="text-3xl font-extrabold text-slate-800 mb-1"><?php echo $resolutionPercent; ?>%</h3>
                        <p class="text-xs lg:text-sm font-medium text-slate-500">% เรื่องที่ปิดได้ (Resolution)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <div class="lg:col-span-4 flex flex-col gap-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex-1">
                            <h3 class="text-base font-bold text-slate-800 mb-5 border-b border-slate-100 pb-3">ประเภทเสียงสะท้อน (VOC Types)</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl border border-red-100">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-red-200 text-red-700 p-2 rounded-lg"><i data-lucide="alert-triangle" class="w-5 h-5"></i></div>
                                        <span class="text-sm font-bold text-red-900">แจ้งปัญหา / แจ้งเหตุ</span>
                                    </div>
                                    <span class="text-lg font-extrabold text-red-700"><?php echo number_format($problemCount); ?></span>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-xl border border-orange-100">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-orange-200 text-orange-700 p-2 rounded-lg"><i data-lucide="lightbulb" class="w-5 h-5"></i></div>
                                        <span class="text-sm font-bold text-orange-900">ข้อเสนอแนะเพื่อพัฒนา</span>
                                    </div>
                                    <span class="text-lg font-extrabold text-orange-700"><?php echo number_format($suggestionsCount); ?></span>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 bg-pink-50 rounded-xl border border-pink-100">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-pink-200 text-pink-700 p-2 rounded-lg"><i data-lucide="heart" class="w-5 h-5"></i></div>
                                        <span class="text-sm font-bold text-pink-900">คำชื่นชม</span>
                                    </div>
                                    <span class="text-lg font-extrabold text-pink-700"><?php echo number_format($complimentsCount); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm max-h-[300px] overflow-y-auto">
                            <h3 class="text-base font-bold text-slate-800 mb-4 sticky top-0 bg-white pb-2 border-b border-slate-100">แยกตามหน่วยงาน (Location)</h3>
                            <ul class="space-y-3">
                                <?php 
                                arsort($locationCount); // เรียงจากมากไปน้อย
                                if(!empty($locationCount)): 
                                    foreach($locationCount as $loc => $count): 
                                ?>
                                <li class="flex justify-between items-center text-sm">
                                    <span class="text-slate-600 truncate pr-2"><i data-lucide="map-pin" class="w-3.5 h-3.5 inline text-slate-400 mr-1"></i> <?php echo htmlspecialchars($loc); ?></span>
                                    <span class="font-bold text-slate-800 bg-slate-100 px-2.5 py-0.5 rounded-full"><?php echo $count; ?></span>
                                </li>
                                <?php 
                                    endforeach; 
                                else: 
                                ?>
                                    <li class="text-sm text-slate-500">ไม่มีข้อมูล</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="lg:col-span-8 flex flex-col gap-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                            <h3 class="text-lg font-bold text-slate-800 mb-6">Top 10 Pain Points (ปัญหาที่พบมากที่สุด)</h3>
                            <div class="space-y-5">
                                <?php if(!empty($topPains)): ?>
                                    <?php foreach($topPains as $index => $p): ?>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1.5">
                                            <span class="font-bold text-slate-700 flex items-center gap-2">
                                                <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[10px]"><?php echo $index + 1; ?></span>
                                                <?php echo htmlspecialchars($p['name']); ?>
                                            </span>
                                            <span class="text-slate-500 font-medium"><?php echo $p['count']; ?> รายการ (<?php echo $p['val']; ?>%)</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="<?php echo $p['color']; ?> h-2.5 rounded-full transition-all duration-500" style="width: <?php echo $p['val']; ?>%"></div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="py-10 text-center text-slate-500 flex flex-col items-center">
                                        <i data-lucide="folder-search" class="w-10 h-10 mb-2 opacity-50"></i>
                                        ยังไม่มีข้อมูลในไตรมาสนี้
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="bg-red-50/50 p-6 rounded-2xl border border-red-100 shadow-sm">
                            <div class="flex items-center gap-2 mb-4 border-b border-red-100 pb-3">
                                <i data-lucide="repeat" class="text-red-500 w-5 h-5"></i>
                                <h3 class="text-base font-bold text-red-900">Recurring Issues (ปัญหาที่เกิดซ้ำซากในพื้นที่เดิม)</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-sm">
                                    <thead>
                                        <tr class="text-slate-500 border-b border-red-200/50">
                                            <th class="pb-2 font-medium">ประเภทปัญหา</th>
                                            <th class="pb-2 font-medium">สถานที่เกิดเหตุ</th>
                                            <th class="pb-2 font-medium text-center">ความถี่ (ครั้ง)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-red-100">
                                        <?php if($resRecurring && $resRecurring->num_rows > 0): ?>
                                            <?php while($rec = $resRecurring->fetch_assoc()): ?>
                                            <tr>
                                                <td class="py-2.5 font-bold text-slate-700"><?php echo htmlspecialchars($rec['issue_type']); ?></td>
                                                <td class="py-2.5 text-slate-600"><i data-lucide="map-pin" class="w-3 h-3 inline mr-1 text-red-400"></i><?php echo htmlspecialchars($rec['location']); ?></td>
                                                <td class="py-2.5 text-center">
                                                    <span class="inline-flex bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded-md">
                                                        <?php echo $rec['count']; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="py-4 text-center text-slate-500">เยี่ยมมาก! ไม่พบปัญหาเกิดซ้ำในพื้นที่เดิม</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
    lucide.createIcons();
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar.classList.contains('-translate-x-full')) {
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