<?php 
require_once 'db.php';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$ticket = null;

if($id) {
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE ticket_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticket = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOICESRI - ติดตามสถานะ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Sarabun', sans-serif; }
    </style>
</head>

<body class="bg-white lg:bg-emerald-50/30 text-slate-800 min-h-screen flex flex-col">

    <header class="bg-emerald-700 text-white p-6 lg:px-10 lg:py-5 rounded-b-3xl lg:rounded-none shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto w-full flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button onclick="window.location.href='index.php'" class="flex items-center gap-2 hover:text-emerald-200 transition-colors pr-4 border-r border-emerald-500/50">
                    <i class="fa-solid fa-arrow-left text-lg"></i>
                </button>
                <h1 class="text-2xl font-bold tracking-tight cursor-pointer" onclick="window.location.href='index.php'">
                    VOICESRI
                </h1>
                <span class="text-emerald-200 font-medium border-emerald-500/50">ติดตามสถานะการดำเนินการ</span>
            </div>
            <button class="hidden">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
    </header>

    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-8 lg:py-12 flex justify-center">
        <div class="w-full lg:max-w-4xl lg:p-10 flex flex-col">
            
            <?php if($ticket): ?>
            
            <div class="mb-8 lg:mb-10 flex justify-between items-center border-b border-emerald-100 pb-5 gap-4">
                <div>
                    <h2 class="text-xl lg:text-3xl font-bold text-emerald-900">สถานะการดำเนินการ</h2>
                    <p class="text-sm lg:text-base text-slate-500 font-mono mt-2">Ticket ID: <span class="font-bold text-emerald-700"><?php echo htmlspecialchars($ticket['ticket_id']); ?></span></p>
                    <p class="text-sm mt-1 text-slate-600">เรื่อง: <span class="font-medium text-slate-800"><?php echo htmlspecialchars($ticket['form_category']); ?></span> (<?php echo htmlspecialchars($ticket['location']); ?>)</p>
                </div>
                <div class="w-16 h-16 lg:w-20 lg:h-20 lg:flex items-center justify-center shrink-0">
                    <img src="img/logo.png" alt="Mascot" class="w-full h-full object-contain" onerror="this.style.display='none'">
                </div>
            </div>

            <div class="relative mb-4 ml-2 lg:ml-4">
                
                <div class="absolute left-[11px] lg:left-[15px] top-2 bottom-2 w-[2px] bg-emerald-100 z-0"></div>

                <div class="space-y-8 lg:space-y-12">
                    
                    <div class="relative z-10 flex items-start gap-4 lg:gap-5">
                        <div class="w-6 h-6 lg:w-8 lg:h-8 rounded-full bg-emerald-600 border-4 border-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                            <i data-lucide="check" class="text-white w-3 h-3 lg:w-4 lg:h-4"></i>
                        </div>
                        <div class="pt-0.5 lg:pt-1">
                            <p class="text-sm lg:text-lg font-bold text-slate-800">รับเรื่องแล้ว</p>
                            <p class="text-xs lg:text-sm text-slate-500 mt-1"><?php echo date('d M Y, H:i น.', strtotime($ticket['created_at'])); ?></p>
                        </div>
                    </div>

                    <?php if($ticket['status'] != 'รับเรื่องแล้ว'): ?>
                    <div class="relative z-10 flex items-start gap-4 lg:gap-5">
                        <div class="<?php echo ($ticket['status'] == 'ปิดเรื่อง') ? 'bg-emerald-600' : 'bg-orange-400 animate-pulse shadow-md shadow-orange-200'; ?> w-6 h-6 lg:w-8 lg:h-8 rounded-full border-4 border-white flex items-center justify-center shrink-0 mt-0.5">
                            <?php if($ticket['status'] == 'ปิดเรื่อง'): ?>
                                <i data-lucide="check" class="text-white w-3 h-3 lg:w-4 lg:h-4"></i>
                            <?php endif; ?>
                        </div>
                        <div class="pt-0.5 lg:pt-1 w-full max-w-2xl">
                            <p class="text-sm lg:text-lg font-bold <?php echo ($ticket['status'] == 'ปิดเรื่อง') ? 'text-emerald-600' : 'text-orange-600'; ?>">
                                <?php echo htmlspecialchars($ticket['status']); ?>
                            </p>
                            <p class="text-xs lg:text-sm text-slate-500 mt-1">อัปเดตล่าสุดเมื่อ: <?php echo date('d M Y, H:i น.', strtotime($ticket['updated_at'])); ?></p>
                            
                            <?php if(!empty($ticket['feedback'])): ?>
                            <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-xl lg:rounded-2xl p-4 lg:p-5 relative shadow-sm w-full">
                                <div class="absolute -top-2 left-6 w-4 h-4 bg-emerald-50 rotate-45 border-l border-t border-emerald-200"></div>
                                <p class="text-xs lg:text-sm font-bold text-emerald-800 mb-2 flex items-center gap-1.5">
                                    <i data-lucide="shield-check" class="w-4 h-4 lg:w-5 lg:h-5 text-emerald-600"></i> ข้อความตอบกลับจากหน่วยงาน:
                                </p>
                                <p class="text-sm lg:text-base text-slate-700 leading-relaxed">
                                    "<?php echo nl2br(htmlspecialchars($ticket['feedback'])); ?>"
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
            
            <?php else: ?>
                <div class="text-center py-10">
                    <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="search-x" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-xl lg:text-2xl font-bold text-slate-800">ไม่พบข้อมูล Ticket ID นี้</h2>
                    <p class="text-slate-500 mt-2">โปรดตรวจสอบหมายเลขอีกครั้ง หรือลองค้นหาใหม่ในหน้าแรกครับ</p>
                    <button onclick="window.location.href='home.php'" class="mt-6 bg-slate-100 text-slate-600 px-6 py-2 rounded-xl font-medium hover:bg-slate-200">กลับหน้าแรก</button>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>

</html>