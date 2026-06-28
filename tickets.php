<?php
$current_page = 'tickets';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Inbox - VOICESRI</title>
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
            
            <?php if($action == 'list'): ?>
            <div class="space-y-6">
                <h1 class="text-2xl font-bold text-slate-800">Ticket Inbox</h1>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-4 border-b border-slate-100 flex justify-between bg-slate-50/50">
                        <div class="flex gap-2">
                            <select class="p-2 border border-slate-200 rounded-lg text-sm bg-white"><option>ทุกสถานะ</option></select>
                        </div>
                    </div>
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="text-xs text-slate-500 uppercase tracking-wider bg-slate-50 border-b border-slate-200">
                                    <th class="p-4 font-semibold">Ticket ID</th>
                                    <th class="p-4 font-semibold">ประเภท</th>
                                    <th class="p-4 font-semibold">หน่วยงาน</th>
                                    <th class="p-4 font-semibold">ความเร่งด่วน</th>
                                    <th class="p-4 font-semibold">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-slate-50 hover:bg-emerald-50/30 cursor-pointer" onclick="window.location.href='tickets.php?action=detail'">
                                    <td class="p-4 text-sm font-bold text-emerald-700">VOC-2026-0001</td>
                                    <td class="p-4 text-sm text-slate-700">ปัญหาการปฏิบัติงาน</td>
                                    <td class="p-4 text-sm text-slate-500">ER</td>
                                    <td class="p-4"><span class="bg-red-50 text-red-600 px-2 py-1 rounded text-xs font-bold border border-red-100">สูง</span></td>
                                    <td class="p-4"><span class="bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-xs font-bold border border-orange-200">อยู่ระหว่างดำเนินการ</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php elseif($action == 'detail'): ?>
            <div class="space-y-6">
                <a href="tickets.php" class="flex items-center gap-2 text-slate-500 hover:text-emerald-600 text-sm font-medium">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปหน้ารายการ
                </a>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-2xl font-bold text-emerald-800">VOC-2026-0001</h2>
                    <p class="text-sm text-slate-500 mt-1">วันที่แจ้ง: 25 มิ.ย. 2026, 14:20 น. • แผนก: ER</p>
                    <div class="mt-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <p class="text-sm text-slate-700">แอร์ในห้องพักคอยญาติหน้า ER ไม่เย็นเลยครับ...</p>
                    </div>
                    
                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <h3 class="text-lg font-bold text-emerald-900 mb-4 flex items-center gap-2">
                            <i data-lucide="settings" class="w-5 h-5 text-emerald-600"></i> การจัดการ (Admin Action)
                        </h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
                                <select class="w-full p-2 bg-white border border-emerald-200 rounded-xl text-sm outline-none">
                                    <option selected>อยู่ระหว่างดำเนินการ</option>
                                    <option>ปิดเรื่อง</option>
                                </select>
                            </div>
                        </div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Feedback to Reporter</label>
                        <textarea rows="3" class="w-full p-3 bg-white border border-emerald-300 rounded-xl text-sm outline-none">กำลังเติมน้ำยาแอร์ครับ</textarea>
                        <div class="mt-4 text-right">
                            <button class="bg-emerald-600 text-white px-6 py-2 rounded-xl text-sm font-bold">บันทึกการอัปเดต</button>
                        </div>
                    </div>
                </div>
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