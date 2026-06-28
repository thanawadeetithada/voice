<?php
require_once 'auth_check.php';
require_once 'db.php';

$current_page = 'dashboard';

// ดึงข้อมูลภาพรวมจากฐานข้อมูล
// 1. จำนวน VOC ทั้งหมด
$resTotal = $conn->query("SELECT COUNT(*) AS total FROM tickets");
$totalTickets = $resTotal->fetch_assoc()['total'];

// 2. เปอร์เซ็นต์การปิดเรื่อง (ถือว่าสถานะ 'ปิดเรื่อง' คือแก้ไขแล้ว)
$resClosed = $conn->query("SELECT COUNT(*) AS closed FROM tickets WHERE status = 'ปิดเรื่อง'");
$closedTickets = $resClosed->fetch_assoc()['closed'];
$resolutionPercent = $totalTickets > 0 ? round(($closedTickets / $totalTickets) * 100) : 0;

// 3. ปัญหาที่พบมากที่สุด (Top Pain Points) ดึงมา 3 อันดับแรก
$resTopPains = $conn->query("SELECT issue_type, COUNT(*) as count FROM tickets GROUP BY issue_type ORDER BY count DESC LIMIT 3");
$topPains = [];
$colors = ['bg-red-500', 'bg-orange-400', 'bg-amber-400'];
$i = 0;
if($resTopPains->num_rows > 0) {
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

// 4. รายการล่าสุด 5 รายการ
$resRecent = $conn->query("SELECT ticket_id, issue_type, location, created_at FROM tickets ORDER BY created_at DESC LIMIT 5");
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

    body {
        font-family: 'Sarabun', sans-serif;
    }

    img {
        object-fit: contain;
        border-width: 1px;
    }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800 h-screen flex overflow-hidden">

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 z-20 lg:hidden backdrop-blur-sm hidden"
        onclick="toggleSidebar()"></div>

    <div id="sidebar"
        class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-slate-200 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="h-20 flex items-center px-6 gap-3 border-b border-slate-100 bg-emerald-50/50">
            <img src="img/logo.png" class="w-8 h-8 rounded-full border-emerald-200 shadow-sm" alt="Logo"
                onerror="this.style.display='none'">
            <span class="text-xl font-bold text-slate-800 tracking-tight">VOICE<span
                    class="text-emerald-600">SRI</span></span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <?php
            $menu = [
                'dashboard' => ['url' => 'admin_dashboard.php', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                'tickets' => ['url' => 'admin_tickets.php', 'icon' => 'inbox', 'label' => 'Tickets Inbox'],
                'settings' => ['url' => 'admin_settings.php', 'icon' => 'settings', 'label' => 'Settings']
            ];
            foreach($menu as $key => $item):
                $activeClass = ($current_page == $key) ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : 'text-slate-500 hover:bg-emerald-50 hover:text-emerald-700';
            ?>
            <a href="<?php echo $item['url']; ?>"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors <?php echo $activeClass; ?>">
                <i data-lucide="<?php echo $item['icon']; ?>" class="w-5 h-5"></i> <?php echo $item['label']; ?>
            </a>
            <?php endforeach; ?>
        </nav>
        <div class="p-4 border-t border-slate-100">
            <a href="index.php"
                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-500 hover:text-red-500 transition-colors bg-slate-50 rounded-xl">
                <i data-lucide="log-out" class="w-5 h-5"></i> กลับหน้าแรก
            </a>
        </div>
    </div>

    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full relative">
        <header
            class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 md:px-6 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-3 w-full">
                <button onclick="toggleSidebar()"
                    class="p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="flex items-center gap-2 md:gap-4 shrink-0">
                <div class="h-8 w-px bg-slate-200 mx-2"></div>
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-slate-800">
                            <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
                        <p class="text-xs text-emerald-600 font-medium">ผู้ดูแลระบบ</p>
                    </div>
                    <div
                        class="w-10 h-10 bg-emerald-100 border border-emerald-200 rounded-full flex items-center justify-center text-emerald-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-6 pb-20">
            <div class="space-y-6">
                <div class="flex justify-between items-end">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
                        <p class="text-slate-500 text-sm mt-1">ภาพรวมข้อมูลการรับฟังเสียง VOC จากฐานข้อมูล</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white rounded-2xl p-6 border border-emerald-100 shadow-sm">
                        <div
                            class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mb-4">
                            <i data-lucide="inbox"></i></div>
                        <h3 class="text-3xl font-bold text-slate-800 mb-1"><?php echo number_format($totalTickets); ?>
                        </h3>
                        <p class="text-sm font-medium text-slate-600">จำนวน VOC ทั้งหมด</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-orange-100 shadow-sm">
                        <div
                            class="w-10 h-10 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center mb-4">
                            <i data-lucide="check-circle"></i></div>
                        <h3 class="text-3xl font-bold text-slate-800 mb-1"><?php echo $resolutionPercent; ?>%</h3>
                        <p class="text-sm font-medium text-slate-600">% เรื่องที่ปิดได้ (Resolution)</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-teal-100 shadow-sm opacity-60">
                        <div
                            class="w-10 h-10 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center mb-4">
                            <i data-lucide="clock"></i></div>
                        <h3 class="text-3xl font-bold text-slate-800 mb-1">N/A</h3>
                        <p class="text-sm font-medium text-slate-600">Median Response Time</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-blue-100 shadow-sm opacity-60">
                        <div
                            class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                            <i data-lucide="trending-up"></i></div>
                        <h3 class="text-3xl font-bold text-slate-800 mb-1">N/A</h3>
                        <p class="text-sm font-medium text-slate-600">Improvement เกิดขึ้นจริง</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-800 mb-6">Top Pain Points (หมวดหมู่ปัญหา)</h3>
                        <div class="space-y-4">
                            <?php if(!empty($topPains)): ?>
                            <?php foreach($topPains as $p): ?>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span
                                        class="font-medium text-slate-700"><?php echo htmlspecialchars($p['name']); ?></span>
                                    <span class="text-slate-500"><?php echo $p['count']; ?> รายการ
                                        (<?php echo $p['val']; ?>%)</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="<?php echo $p['color']; ?> h-1.5 rounded-full"
                                        style="width: <?php echo $p['val']; ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <p class="text-sm text-slate-500">ยังไม่มีข้อมูล</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="bg-orange-50/50 p-6 rounded-2xl border border-orange-100 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <i data-lucide="clock" class="text-orange-600 w-5 h-5"></i>
                            <h3 class="text-md font-bold text-orange-900">รายการรับฟังเสียงล่าสุด (5 รายการ)</h3>
                        </div>
                        <ul class="text-sm text-slate-700 space-y-3">
                            <?php if($resRecent->num_rows > 0): ?>
                            <?php while($recent = $resRecent->fetch_assoc()): ?>
                            <li class="flex flex-col border-b border-orange-200/50 pb-2 last:border-0 last:pb-0">
                                <span
                                    class="font-bold text-slate-800"><?php echo htmlspecialchars($recent['ticket_id']); ?></span>
                                <span class="text-slate-600">เรื่อง:
                                    <?php echo htmlspecialchars($recent['issue_type']); ?>
                                    (<?php echo htmlspecialchars($recent['location']); ?>)</span>
                                <span
                                    class="text-xs text-slate-400 mt-1"><?php echo date('d M Y H:i', strtotime($recent['created_at'])); ?></span>
                            </li>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <li>ไม่มีข้อมูลรายการล่าสุด</li>
                            <?php endif; ?>
                        </ul>
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