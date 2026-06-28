<?php $id = isset($_GET['id']) ? $_GET['id'] : 'VOC-2026-0001'; ?>
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
                <span class="hidden lg:inline-block text-emerald-200 font-medium border-l border-emerald-500/50 pl-4 ml-2">ติดตามสถานะการดำเนินการ</span>
            </div>
            <button class="hidden">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
    </header>

    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-8 lg:py-12 flex justify-center">
        
        <div class="w-full lg:max-w-4xl lg:bg-white lg:p-10 lg:rounded-3xl lg:shadow-sm lg:border lg:border-emerald-100 flex flex-col">
            
            <div class="mb-8 lg:mb-10 flex justify-between items-end border-b border-emerald-100 pb-5">
                <div>
                    <h2 class="text-xl lg:text-3xl font-bold text-emerald-900">สถานะการดำเนินการ</h2>
                    <p class="text-sm lg:text-base text-slate-500 font-mono mt-2">Ticket ID: <span class="font-bold text-emerald-700"><?php echo htmlspecialchars($id); ?></span></p>
                </div>
                <div class="w-12 h-12 lg:w-16 lg:h-16 hidden lg:block">
                    <img src="img/logo.png" alt="Mascot" class="w-full object-cover" onerror="this.style.display='none'">
                </div>
            </div>

            <div class="relative pl-6 lg:pl-8 border-l-2 border-emerald-100 space-y-8 lg:space-y-12 mb-4 ml-2 lg:ml-4">
                
                <div class="relative">
                    <div class="absolute -left-[35px] lg:-left-[43px] bg-emerald-600 w-6 h-6 lg:w-8 lg:h-8 rounded-full border-4 border-white flex items-center justify-center">
                        <i data-lucide="check" class="text-white w-3 h-3 lg:w-4 lg:h-4"></i>
                    </div>
                    <p class="text-sm lg:text-lg font-bold text-slate-800">รับเรื่องแล้ว</p>
                    <p class="text-xs lg:text-sm text-slate-500 mt-1">27 มิ.ย. 2026, 09:30 น.</p>
                </div>

                <div class="relative">
                    <div class="absolute -left-[35px] lg:-left-[43px] bg-emerald-600 w-6 h-6 lg:w-8 lg:h-8 rounded-full border-4 border-white flex items-center justify-center">
                        <i data-lucide="check" class="text-white w-3 h-3 lg:w-4 lg:h-4"></i>
                    </div>
                    <p class="text-sm lg:text-lg font-bold text-slate-800">อยู่ระหว่างพิจารณา</p>
                    <p class="text-xs lg:text-sm text-slate-500 mt-1">27 มิ.ย. 2026, 10:15 น.</p>
                </div>

                <div class="relative">
                    <div class="absolute -left-[35px] lg:-left-[43px] bg-orange-400 w-6 h-6 lg:w-8 lg:h-8 rounded-full border-4 border-white animate-pulse shadow-md shadow-orange-200"></div>
                    <p class="text-sm lg:text-lg font-bold text-orange-600">อยู่ระหว่างดำเนินการ</p>
                    <p class="text-xs lg:text-sm text-slate-500 mt-1">คาดว่าจะแล้วเสร็จภายใน 3 วัน</p>
                    
                    <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-xl lg:rounded-2xl p-4 lg:p-5 relative shadow-sm max-w-2xl">
                        <div class="absolute -top-2 left-6 w-4 h-4 bg-emerald-50 rotate-45 border-l border-t border-emerald-200"></div>
                        <p class="text-xs lg:text-sm font-bold text-emerald-800 mb-2 flex items-center gap-1.5">
                            <i data-lucide="shield-check" class="w-4 h-4 lg:w-5 lg:h-5 text-emerald-600"></i> ข้อความตอบกลับจากหน่วยงาน:
                        </p>
                        <p class="text-sm lg:text-base text-slate-700 leading-relaxed">
                            "รับทราบปัญหาเรื่องแอร์ห้องพักญาติไม่เย็นครับ ตอนนี้ได้แจ้งช่างซ่อมบำรุงเข้าตรวจสอบแล้ว เบื้องต้นพบน้ำยาแอร์ขาด กำลังเติมน้ำยาครับ"
                        </p>
                    </div>
                </div>

                <div class="relative opacity-40">
                    <div class="absolute -left-[35px] lg:-left-[43px] bg-slate-300 w-6 h-6 lg:w-8 lg:h-8 rounded-full border-4 border-white"></div>
                    <p class="text-sm lg:text-lg font-bold text-slate-500">ดำเนินการแล้ว / ปิดเรื่อง</p>
                </div>

            </div>

        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>

</html>