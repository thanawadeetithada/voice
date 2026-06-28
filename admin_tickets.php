<?php
require_once 'auth_check.php';
require_once 'db.php';
$current_page = 'tickets';

// ฟังก์ชันจำแนกสีสถานะ
function getStatusColor($status) {
    switch ($status) {
        case 'ปิดเรื่อง': return 'bg-emerald-50 text-emerald-600 border-emerald-200';
        case 'รับเรื่องแล้ว': return 'bg-blue-50 text-blue-600 border-blue-200';
        default: return 'bg-orange-50 text-orange-600 border-orange-200'; // อยู่ระหว่างดำเนินการ
    }
}

// จัดการการลบข้อมูล (เมื่อมีการกดยืนยันจาก Modal)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_ticket'])) {
    $delete_id = intval($_POST['delete_id']);
    
    $deleteStmt = $conn->prepare("DELETE FROM tickets WHERE id = ?");
    $deleteStmt->bind_param("i", $delete_id);
    if($deleteStmt->execute()) {
        header("Location: admin_tickets.php");
        exit;
    }
}
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
        img { object-fit: contain; border-width: 1px; }
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
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 md:px-6 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-3 w-full">
                <button onclick="toggleSidebar()" class="p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="flex items-center gap-2 md:gap-4 shrink-0">
                <div class="h-8 w-px bg-slate-200 mx-2"></div>
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-slate-800"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
                        <p class="text-xs text-emerald-600 font-medium">ผู้ดูแลระบบ</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-100 border border-emerald-200 rounded-full flex items-center justify-center text-emerald-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-6 pb-20">
            <div class="space-y-6">
                <h1 class="text-2xl font-bold text-slate-800">Ticket Inbox</h1>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead>
                                <tr class="text-xs text-slate-500 uppercase tracking-wider bg-slate-50 border-b border-slate-200">
                                    <th class="p-4 font-semibold">Ticket ID</th>
                                    <th class="p-4 font-semibold">ประเภท</th>
                                    <th class="p-4 font-semibold">หน่วยงาน</th>
                                    <th class="p-4 font-semibold">วันที่สร้าง</th>
                                    <th class="p-4 font-semibold">สถานะ</th>
                                    <th class="p-4 font-semibold text-center w-32">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tickets ORDER BY created_at DESC";
                                $result = $conn->query($sql);
                                if($result->num_rows > 0):
                                    while($row = $result->fetch_assoc()):
                                        $statusClass = getStatusColor($row['status']);
                                ?>
                                <tr class="border-b border-slate-50 hover:bg-emerald-50/30">
                                    <td class="p-4 text-sm font-bold text-emerald-700"><?php echo htmlspecialchars($row['ticket_id']); ?></td>
                                    <td class="p-4 text-sm text-slate-700"><?php echo htmlspecialchars($row['issue_type']); ?></td>
                                    <td class="p-4 text-sm text-slate-500"><?php echo htmlspecialchars($row['location']); ?></td>
                                    <td class="p-4 text-sm text-slate-500"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border <?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-center space-x-2">
                                        <a href="admin_ticket_detail.php?id=<?php echo $row['id']; ?>" class="inline-flex items-center justify-center p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="แก้ไข/รายละเอียด">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <button type="button" onclick="openDeleteModal(<?php echo $row['id']; ?>)" class="inline-flex items-center justify-center p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="ลบ">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile;
                                else: 
                                ?>
                                    <tr><td colspan="6" class="p-4 text-center text-slate-500">ไม่มีข้อมูล</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">ยืนยันการลบข้อมูล</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500">คุณแน่ใจหรือไม่ที่จะลบ Ticket นี้? <br>การกระทำนี้ไม่สามารถเรียกคืนข้อมูลได้</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form method="POST" action="">
                        <input type="hidden" name="delete_ticket" value="1">
                        <input type="hidden" name="delete_id" id="delete_id_input" value="">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            ลบข้อมูล
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </div>
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

        // Modal Functions
        function openDeleteModal(id) {
            document.getElementById('delete_id_input').value = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</body>
</html>