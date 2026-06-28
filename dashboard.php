<?php
$current_page = 'dashboard';
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
        img {
            object-fit: contain;
            border-width: 1px;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 h-screen flex overflow-hidden">

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 z-20 lg:hidden backdrop-blur-sm hidden" onclick="toggleSidebar()"></div>
    
    <div id="sidebar" class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-slate-200 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="h-20 flex items-center px-6 gap-3 border-b border-slate-100 bg-emerald-50/50">
            <img src="img/logo.png" class="w-8 h-8 rounded-full border-emerald-200 shadow-sm" alt="Logo" onerror="this.style.display='none'">
            <span class="text-xl font-bold text-slate-800 tracking-tight">VOICE<span class="text-emerald-600">SRI</span></span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <?php
            $menu = [
                'dashboard' => ['url' => 'dashboard.php', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                'tickets' => ['url' => 'tickets.php', 'icon' => 'inbox', 'label' => 'Tickets Inbox'],
                'reports' => ['url' => 'reports.php', 'icon' => 'file-text', 'label' => 'Reports'],
                'settings' => ['url' => 'settings.php', 'icon' => 'settings', 'label' => 'Settings']
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
            <a href="admin-login.php" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-500 hover:text-red-500 transition-colors bg-slate-50 rounded-xl">
                <i data-lucide="log-out" class="w-5 h-5"></i> ออกจากระบบ
            </a>
        </div>
    </div>

    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full relative">
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 md:px-6 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-3 w-full">
                <button onclick="toggleSidebar()" class="p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="hidden md:flex items-center bg-slate-100 px-4 py-2 rounded-full w-96 border border-transparent focus-within:border-emerald-300 focus-within:bg-white transition-all">
                    <i data-lucide="search" class="text-slate-400 mr-2 w-4 h-4"></i>
                    <input type="text" placeholder="ค้นหา Ticket ID, เรื่อง..." class="bg-transparent border-none outline-none text-sm w-full">
                </div>
            </div>
            <div class="flex items-center gap-2 md:gap-4 shrink-0">
                <div class="h-8 w-px bg-slate-200 mx-2"></div>
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-slate-800">Admin</p>
                        <p class="text-xs text-emerald-600 font-medium">จัดการระบบ</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-100 border border-emerald-200 rounded-full flex items-center justify-center text-emerald-600">
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
                        <p class="text-slate-500 text-sm mt-1">ภาพรวมข้อมูลการรับฟังเสียง VOC</p>
                    </div>
                    <button class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm flex items-center gap-2 hover:bg-slate-50 shadow-sm font-medium">
                        Export CSV / Excel
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white rounded-2xl p-6 border border-emerald-100 shadow-sm">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mb-4"><i data-lucide="inbox"></i></div>
                        <h3 class="text-3xl font-bold text-slate-800 mb-1">1,204</h3>
                        <p class="text-sm font-medium text-slate-600">จำนวน VOC ทั้งหมด</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-orange-100 shadow-sm">
                        <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center mb-4"><i data-lucide="check-circle"></i></div>
                        <h3 class="text-3xl font-bold text-slate-800 mb-1">85%</h3>
                        <p class="text-sm font-medium text-slate-600">% เรื่องที่ปิดได้ (Resolution)</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-teal-100 shadow-sm">
                        <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center mb-4"><i data-lucide="clock"></i></div>
                        <h3 class="text-3xl font-bold text-slate-800 mb-1">2.4h</h3>
                        <p class="text-sm font-medium text-slate-600">Median Response Time</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-blue-100 shadow-sm">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-4"><i data-lucide="trending-up"></i></div>
                        <h3 class="text-3xl font-bold text-slate-800 mb-1">45</h3>
                        <p class="text-sm font-medium text-slate-600">Improvement เกิดขึ้นจริง</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-800 mb-6">Top 5 Pain Points</h3>
                        <div class="space-y-4">
                            <?php 
                            $pains = [
                                ['name' => 'แอร์ไม่เย็น (OPD)', 'val' => 45, 'color' => 'bg-red-500'],
                                ['name' => 'ระบบ IT ช้า', 'val' => 29, 'color' => 'bg-orange-400'],
                                ['name' => 'ที่จอดรถไม่พอ', 'val' => 18, 'color' => 'bg-amber-400']
                            ];
                            foreach($pains as $p): ?>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-slate-700"><?php echo $p['name']; ?></span>
                                    <span class="text-slate-500"><?php echo $p['val']; ?>%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="<?php echo $p['color']; ?> h-1.5 rounded-full" style="width: <?php echo $p['val']; ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="bg-orange-50/50 p-6 rounded-2xl border border-orange-100 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <i data-lucide="refresh-ccw" class="text-orange-600 w-5 h-5"></i>
                            <h3 class="text-md font-bold text-orange-900">Recurring Issues (เกิดซ้ำ)</h3>
                        </div>
                        <ul class="text-sm text-slate-700 space-y-2 list-disc list-inside">
                            <li>ห้องน้ำตึกผู้ป่วยนอกน้ำไม่ไหล <span class="text-orange-600 font-semibold">(12 ครั้ง)</span></li>
                            <li>ลิฟต์ตัวที่ 3 ค้างบ่อย <span class="text-orange-600 font-semibold">(5 ครั้ง)</span></li>
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