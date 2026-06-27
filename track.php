<?php $id = isset($_GET['id']) ? $_GET['id'] : 'VOC-2026-0001'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดตามสถานะ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Sarabun', sans-serif; }

        .logodisplay{
            width: 10%;
        }
    </style>
</head>
<body class="bg-emerald-50/30 text-slate-800">

<div class="md:py-10 min-h-screen flex flex-col items-center">
    <div class="w-full max-w-md mx-auto bg-white min-h-screen md:min-h-[85vh] shadow-2xl relative flex flex-col md:rounded-3xl border border-emerald-100">
        
        <div class="bg-emerald-700 text-white p-6 rounded-b-3xl shadow-md flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight cursor-pointer" onclick="window.location.href='index.php'">VOICESRI</h1>
            <button onclick="window.location.href='index.php'" class="bg-emerald-800/50 p-2 rounded-full hover:bg-emerald-800">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto pb-20 p-6">
            <!-- <button onclick="window.location.href='index.php'" class="flex items-center gap-2 text-emerald-600 text-sm font-medium mb-6">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับ
            </button> -->

            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h2 class="text-xl font-bold text-emerald-900">สถานะการดำเนินการ</h2>
                    <p class="text-sm text-slate-500 font-mono mt-1">Ticket ID: <?php echo htmlspecialchars($id); ?></p>
                </div>
                <div class="flex items-center justify-center overflow-hidden logodisplay">
                    <img src="img/logo.png" alt="Mascot" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="relative pl-6 border-l-2 border-emerald-100 space-y-8 mb-8">
                
                <div class="relative">
                    <div class="absolute -left-[35px] bg-emerald-600 w-6 h-6 rounded-full border-4 border-white flex items-center justify-center">
                        <i data-lucide="check" class="text-white w-3 h-3"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-800">รับเรื่องแล้ว</p>
                    <p class="text-xs text-slate-500">27 มิ.ย. 2026, 09:30 น.</p>
                </div>

                <div class="relative">
                    <div class="absolute -left-[35px] bg-emerald-600 w-6 h-6 rounded-full border-4 border-white flex items-center justify-center">
                        <i data-lucide="check" class="text-white w-3 h-3"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-800">อยู่ระหว่างพิจารณา</p>
                    <p class="text-xs text-slate-500">27 มิ.ย. 2026, 10:15 น.</p>
                </div>

                <div class="relative">
                    <div class="absolute -left-[35px] bg-orange-400 w-6 h-6 rounded-full border-4 border-white animate-pulse shadow-md shadow-orange-200"></div>
                    <p class="text-sm font-semibold text-orange-600">อยู่ระหว่างดำเนินการ</p>
                    <p class="text-xs text-slate-500">คาดว่าจะแล้วเสร็จภายใน 3 วัน</p>
                    
                    <div class="mt-3 bg-emerald-50 border border-emerald-200 rounded-xl p-4 relative shadow-sm">
                        <div class="absolute -top-2 left-4 w-4 h-4 bg-emerald-50 rotate-45 border-l border-t border-emerald-200"></div>
                        <p class="text-xs font-bold text-emerald-800 mb-1 flex items-center gap-1">
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i> ข้อความตอบกลับจากหน่วยงาน:
                        </p>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            "รับทราบปัญหาเรื่องแอร์ห้องพักญาติไม่เย็นครับ ตอนนี้ได้แจ้งช่างซ่อมบำรุงเข้าตรวจสอบแล้ว เบื้องต้นพบน้ำยาแอร์ขาด กำลังเติมน้ำยาครับ"
                        </p>
                    </div>
                </div>

                <div class="relative opacity-40">
                    <div class="absolute -left-[35px] bg-slate-300 w-6 h-6 rounded-full border-4 border-white"></div>
                    <p class="text-sm font-semibold text-slate-500">ดำเนินการแล้ว / ปิดเรื่อง</p>
                </div>

            </div>
        </div>

    </div>
</div>
<script>lucide.createIcons();</script>
</body>
</html>