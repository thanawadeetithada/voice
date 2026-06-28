<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOICESRI - หน้าแรก</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Sarabun', sans-serif;
    }

    .logo {
        display: flex;
        justify-content: flex-end;
        align-items: flex-end;
        right: 15px;
        position: absolute;
        width: 15%;
    }

    .img-logo {
        opacity: 0.9;
        object-fit: cover;
        width: 80%;
    }
    </style>
</head>

<body class="bg-white lg:bg-emerald-50/30 text-slate-800 min-h-screen flex flex-col">

    <header
        class="bg-emerald-700 text-white p-6 lg:px-10 lg:py-5 rounded-b-3xl lg:rounded-none shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto w-full flex justify-between items-center">
            <div class="flex items-center gap-4">
                <h1 class="text-2xl font-bold tracking-tight cursor-pointer" onclick="window.location.reload();">
                    VOICESRI
                </h1>
                <span
                    class="hidden lg:inline-block text-emerald-200 font-medium border-l border-emerald-500/50 pl-4 ml-2">รับฟัง
                    ตอบกลับ ติดตาม</span>
            </div>
            <button class="hidden">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
    </header>

    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-8 lg:py-12">

        <div class="hidden lg:block mb-8">
            <h2 class="text-3xl font-bold text-emerald-900">หน้าแรก</h2>
            <p class="text-slate-600 mt-2 text-lg">ยินดีต้อนรับเข้าสู่ระบบเสียงเสรี คุณต้องการทำอะไรวันนี้?</p>
        </div>

        <div class="lg:bg-white lg:p-8 lg:rounded-3xl lg:shadow-sm lg:border lg:border-emerald-100 flex flex-col gap-8">

            <div
                class="bg-gradient-to-r from-emerald-50 to-orange-50 border border-emerald-100 rounded-2xl p-5 lg:p-8 flex items-center relative overflow-hidden shadow-sm">
                <div class="w-2/3 lg:w-4/5 z-10 relative">
                    <h2 class="text-lg lg:text-2xl font-bold text-emerald-800 mb-1 lg:mb-2">สวัสดีครับ!</h2>
                    <p class="text-xs lg:text-base text-emerald-700 leading-relaxed font-medium">ผมชื่อ <span
                            class="text-orange-600 font-bold">เสียงเสรี</span> ยินดีรับฟังทุกเสียงนะครับ</p>
                </div>
                <div class="logo lg:!w-28 lg:!right-12">
                    <img src="img/logo.png" alt="มาสคอต" class="img-logo drop-shadow-lg"
                        onerror="this.style.display='none'" />
                </div>
            </div>

            <div>
                <h2 class="text-lg lg:text-xl font-bold text-slate-700 mb-4 lg:mb-6 lg:hidden">เมนูการใช้งาน</h2>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
                    <a href="problem_form.php"
                        class="bg-white border border-slate-200 hover:border-red-400 hover:shadow-md transition-all p-4 lg:p-6 rounded-2xl flex lg:flex-col items-center lg:items-start gap-4 lg:gap-5 group cursor-pointer">
                        <div
                            class="bg-red-50 text-red-500 p-3 lg:p-4 rounded-xl group-hover:bg-red-500 group-hover:text-white transition-colors">
                            <i data-lucide="alert-triangle" class="lg:w-7 lg:h-7"></i>
                        </div>
                        <div class="text-left flex-1">
                            <h3 class="font-bold text-slate-800 lg:text-lg mb-1">แจ้งปัญหา</h3>
                            <p class="text-xs lg:text-sm text-slate-500">รายงานปัญหา อุปสรรค หรือความเสี่ยง</p>
                        </div>
                        <i data-lucide="chevron-right" class="text-slate-300 group-hover:text-red-400 lg:hidden"></i>
                    </a>

                    <a href="suggestion_form.php"
                        class="bg-white border border-slate-200 hover:border-orange-400 hover:shadow-md transition-all p-4 lg:p-6 rounded-2xl flex lg:flex-col items-center lg:items-start gap-4 lg:gap-5 group cursor-pointer">
                        <div
                            class="bg-orange-50 text-orange-500 p-3 lg:p-4 rounded-xl group-hover:bg-orange-500 group-hover:text-white transition-colors">
                            <i data-lucide="lightbulb" class="lg:w-7 lg:h-7"></i>
                        </div>
                        <div class="text-left flex-1">
                            <h3 class="font-bold text-slate-800 lg:text-lg mb-1">เสนอแนวทางพัฒนา</h3>
                            <p class="text-xs lg:text-sm text-slate-500">ไอเดียใหม่ๆ เพื่อปรับปรุงองค์กร</p>
                        </div>
                        <i data-lucide="chevron-right" class="text-slate-300 group-hover:text-orange-400 lg:hidden"></i>
                    </a>

                    <a href="compliment_form.php"
                        class="bg-white border border-slate-200 hover:border-pink-400 hover:shadow-md transition-all p-4 lg:p-6 rounded-2xl flex lg:flex-col items-center lg:items-start gap-4 lg:gap-5 group cursor-pointer">
                        <div
                            class="bg-pink-50 text-pink-500 p-3 lg:p-4 rounded-xl group-hover:bg-pink-500 group-hover:text-white transition-colors">
                            <i data-lucide="heart" class="lg:w-7 lg:h-7"></i>
                        </div>
                        <div class="text-left flex-1">
                            <h3 class="font-bold text-slate-800 lg:text-lg mb-1">ชื่นชมบุคลากรหรือหน่วยงาน</h3>
                            <p class="text-xs lg:text-sm text-slate-500">ส่งต่อกำลังใจให้คนทำงาน</p>
                        </div>
                        <i data-lucide="chevron-right" class="text-slate-300 group-hover:text-pink-400 lg:hidden"></i>
                    </a>
                </div>
            </div>

            <div
                class="bg-emerald-50/80 lg:bg-slate-50 border border-emerald-100 p-5 lg:p-6 rounded-2xl flex flex-col lg:flex-row lg:items-center justify-between gap-4 lg:gap-8 mt-2 lg:mt-4">
                <div class="w-full lg:w-auto">
                    <h2 class="text-sm lg:text-base font-bold text-emerald-800 mb-2 lg:mb-1 flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4 lg:w-5 lg:h-5 text-emerald-600"></i>
                        ติดตามสถานะเรื่องของคุณ
                    </h2>
                    <p class="hidden lg:block text-sm text-slate-500">กรอกหมายเลข Ticket ID เพื่อดูความคืบหน้า</p>
                </div>
                <div class="flex gap-2 w-full lg:w-1/2">
                    <input type="text" id="searchTicketId" placeholder="เช่น VOC-2026-0001"
                        class="flex-1 p-3 lg:p-3.5 rounded-xl border border-emerald-200 text-sm lg:text-base focus:outline-none focus:border-emerald-500 bg-white" />
                    <button onclick="searchTicket()"
                        class="bg-emerald-600 text-white px-5 lg:px-8 py-3 lg:py-3.5 rounded-xl text-sm lg:text-base font-medium hover:bg-emerald-700 shadow-md shadow-emerald-200 whitespace-nowrap">
                        ค้นหา
                    </button>
                </div>
            </div>

        </div>
    </main>

    <script>
    lucide.createIcons();

    function searchTicket() {
        const id = document.getElementById('searchTicketId').value;
        if (id) window.location.href = 'track.php?id=' + id;
    }
    </script>
</body>

</html>