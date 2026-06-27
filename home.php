<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOICESRI - หน้าแรก</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Sarabun', sans-serif; }

        .intro-card {
            position: absolute;
            display: flex;
            width: 50%;
        }

        .img-logo {
            opacity: 0.9;
            object-fit: cover;
            width: 40%;
            margin-left: auto;
        }
    </style>
</head>
<body class="bg-emerald-50/30 font-sans text-slate-800">

    <div class="md:py-10 min-h-screen flex flex-col items-center">
        <div class="w-full max-w-md mx-auto bg-white min-h-screen md:min-h-[85vh] shadow-2xl relative overflow-hidden flex flex-col md:rounded-3xl border border-emerald-100">
            
            <div class="bg-emerald-700 text-white p-6 rounded-b-3xl shadow-md relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-600 rounded-full opacity-50 blur-2xl"></div>
                
                <div class="flex justify-between items-center mb-2 relative z-10">
                    <h1 class="text-2xl font-bold tracking-tight flex items-center gap-2 cursor-pointer" onclick="window.location.reload();">
                        VOICESRI
                    </h1>
                </div>
                <p class="text-emerald-100 text-sm relative z-10">รับฟัง ตอบกลับ ติดตาม เพื่อพัฒนาไปด้วยกัน</p>
            </div>

            <div class="flex-1 overflow-y-auto pb-20 w-full p-6 space-y-6">
                
                <div class="bg-gradient-to-r from-emerald-50 to-orange-50 border border-emerald-100 rounded-2xl p-5 flex items-center relative overflow-hidden shadow-sm">
                    <div class="w-2/3 z-10 relative">
                        <h2 class="text-lg font-bold text-emerald-800 mb-1">สวัสดีครับ!</h2>
                        <p class="text-xs text-emerald-700 leading-relaxed font-medium">ผมชื่อ <span class="text-orange-600 font-bold">เสียงเสรี</span> ยินดีรับฟัง<br/>ทุกเสียงนะครับ</p>
                    </div>
                    <div class="intro-card right-5">
                        <img src="img/logo.png" alt="มาสคอตเสียงเสรี" class="img-logo drop-shadow-lg" />
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-md font-bold text-slate-700">คุณต้องการทำอะไรวันนี้?</h2>
                    
                    <a href="form.php?type=ปัญหาการปฏิบัติงาน" class="w-full bg-white border border-slate-200 hover:border-red-400 hover:shadow-md transition-all p-4 rounded-2xl flex items-center gap-4 group cursor-pointer block">
                        <div class="flex items-center w-full">
                            <div class="bg-red-50 text-red-500 p-3 rounded-xl group-hover:bg-red-500 group-hover:text-white transition-colors">
                                <i data-lucide="alert-triangle"></i>
                            </div>
                            <div class="text-left flex-1 ml-4">
                                <h3 class="font-semibold text-slate-800">แจ้งปัญหา</h3>
                                <p class="text-xs text-slate-500">รายงานปัญหา อุปสรรค หรือความเสี่ยง</p>
                            </div>
                            <i data-lucide="chevron-right" class="text-slate-300 group-hover:text-red-400"></i>
                        </div>
                    </a>

                    <a href="form.php?type=ข้อเสนอแนะเพื่อพัฒนา" class="w-full bg-white border border-slate-200 hover:border-orange-400 hover:shadow-md transition-all p-4 rounded-2xl flex items-center gap-4 group cursor-pointer block">
                        <div class="flex items-center w-full">
                            <div class="bg-orange-50 text-orange-500 p-3 rounded-xl group-hover:bg-orange-500 group-hover:text-white transition-colors">
                                <i data-lucide="lightbulb"></i>
                            </div>
                            <div class="text-left flex-1 ml-4">
                                <h3 class="font-semibold text-slate-800">เสนอแนวทางพัฒนา</h3>
                                <p class="text-xs text-slate-500">ไอเดียใหม่ๆ เพื่อปรับปรุงองค์กร</p>
                            </div>
                            <i data-lucide="chevron-right" class="text-slate-300 group-hover:text-orange-400"></i>
                        </div>
                    </a>

                    <a href="form.php?type=ชื่นชมบุคลากร" class="w-full bg-white border border-slate-200 hover:border-pink-400 hover:shadow-md transition-all p-4 rounded-2xl flex items-center gap-4 group cursor-pointer block">
                        <div class="flex items-center w-full">
                            <div class="bg-pink-50 text-pink-500 p-3 rounded-xl group-hover:bg-pink-500 group-hover:text-white transition-colors">
                                <i data-lucide="heart"></i>
                            </div>
                            <div class="text-left flex-1 ml-4">
                                <h3 class="font-semibold text-slate-800">ชื่นชมบุคลากร</h3>
                                <p class="text-xs text-slate-500">ส่งต่อกำลังใจให้คนทำงาน</p>
                            </div>
                            <i data-lucide="chevron-right" class="text-slate-300 group-hover:text-pink-400"></i>
                        </div>
                    </a>
                </div>

                <div class="bg-emerald-50/80 border border-emerald-100 p-5 rounded-2xl mt-8 shadow-sm">
                    <h2 class="text-sm font-semibold text-emerald-800 mb-3 flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4 text-emerald-600"></i> ติดตามสถานะเรื่องของคุณ
                    </h2>
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            id="searchTicketId"
                            placeholder="เช่น VOC-2026-0001" 
                            class="flex-1 p-3 rounded-xl border border-emerald-200 text-sm focus:outline-none focus:border-emerald-500 bg-white"
                        />
                        <button 
                            onclick="searchTicket()"
                            class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-emerald-700 shadow-md shadow-emerald-200"
                        >
                            ค้นหา
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // เรนเดอร์ Lucide Icons
        lucide.createIcons();

    </script>
</body>
</html>