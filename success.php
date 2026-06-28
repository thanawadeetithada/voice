<?php $id = isset($_GET['id']) ? $_GET['id'] : 'VOC-XXXX-XXXX'; ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOICESRI - ส่งข้อมูลสำเร็จ</title>
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
                <h1 class="text-2xl font-bold tracking-tight cursor-pointer" onclick="window.location.href='index.php'">
                    VOICESRI
                </h1>
                <span class="hidden lg:inline-block text-emerald-200 font-medium border-l border-emerald-500/50 pl-4 ml-2">ทำรายการสำเร็จ</span>
            </div>
            <button class="hidden">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
    </header>

    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-8 lg:py-12 flex items-center justify-center">
        
        <div class="w-full lg:max-w-2xl  lg:p-12 flex flex-col items-center text-center">
            
            <div class="mb-6 relative">
                <div class="absolute inset-0 bg-orange-100 rounded-full blur-2xl opacity-50"></div>
                <img src="img/logo.png" class="w-28 h-28 lg:w-36 lg:h-36 object-contain relative z-10 mx-auto" alt="Success" onerror="this.src='https://placehold.co/400x400/2f7c47/fff?text=Mascot+Happy'">
            </div>

            <h2 class="text-2xl lg:text-3xl font-bold text-emerald-800 mb-2">ขอบคุณครับ</h2>
            <p class="text-slate-600 text-sm lg:text-base mb-8">ที่ร่วมเป็นส่วนหนึ่งในการพัฒนาองค์กรของเรา</p>
            
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 lg:p-8 w-full mb-8 shadow-inner">
                <p class="text-xs lg:text-sm text-emerald-600 uppercase tracking-wider mb-2 font-bold">Ticket ID ของคุณคือ</p>
                <p class="text-2xl lg:text-4xl font-mono font-bold text-emerald-700 tracking-wider"><?php echo htmlspecialchars($id); ?></p>
                <p class="text-xs lg:text-sm text-slate-500 mt-4">* กรุณาบันทึกรหัสนี้ไว้เพื่อใช้ติดตามความคืบหน้า</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-3 lg:gap-4 w-full">
                <button onclick="window.location.href='index.php'" class="flex-1 py-3.5 lg:py-4 px-4 bg-slate-100 text-slate-600 font-medium rounded-xl lg:rounded-2xl hover:bg-slate-200 transition-colors order-2 lg:order-1">
                    กลับหน้าหลัก
                </button>
                <button onclick="window.location.href='track.php?id=<?php echo htmlspecialchars($id); ?>'" class="flex-1 py-3.5 lg:py-4 px-4 bg-orange-500 text-white font-bold rounded-xl lg:rounded-2xl hover:bg-orange-600 shadow-md shadow-orange-200 transition-colors order-1 lg:order-2">
                    ดูสถานะตอนนี้เลย
                </button>
            </div>
            
        </div>

    </main>

    <script>lucide.createIcons();</script>
</body>

</html>