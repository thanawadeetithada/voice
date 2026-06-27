<?php
$type = isset($_GET['type']) ? $_GET['type'] : 'ปัญหาการปฏิบัติงาน';
$isProblem = (strpos($type, 'ปัญหา') !== false || strpos($type, 'ความปลอดภัย') !== false || strpos($type, 'สิ่งแวดล้อม') !== false);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOICESRI - แบบฟอร์ม</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Sarabun', sans-serif;
    }
    </style>
</head>

<body class="bg-emerald-50/30 text-slate-800">

    <div class="md:py-10 min-h-screen flex flex-col items-center">
        <div
            class="w-full max-w-md mx-auto bg-white min-h-screen md:min-h-[85vh] shadow-2xl relative flex flex-col md:rounded-3xl border border-emerald-100">

            <div class="bg-emerald-700 text-white p-6 rounded-b-3xl shadow-md flex justify-between items-center">
                <h1 class="text-2xl font-bold tracking-tight cursor-pointer" onclick="window.location.href='index.php'">
                    VOICESRI</h1>
            </div>

            <div class="flex-1 overflow-y-auto pb-20 p-6 space-y-6">

                <div class="space-y-4">
                    <div class="flex items-center border-b border-emerald-100 pb-2">
                        <h3 class="font-bold text-emerald-800 flex items-center gap-2">
                            <span
                                class="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                            ข้อมูลเรื่องราว
                        </h3>
                        <button onClick={goHome} className="bg-emerald-800/50 p-2 rounded-full hover:bg-emerald-800"><X size={20} /></button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">ประเภทเรื่อง <span
                                class="text-red-500">*</span></label>
                        <select
                            class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 outline-none">
                            <option <?php echo $type == 'ปัญหาการปฏิบัติงาน' ? 'selected' : ''; ?>>ปัญหาการปฏิบัติงาน
                            </option>
                            <option <?php echo $type == 'ข้อเสนอแนะเพื่อพัฒนา' ? 'selected' : ''; ?>>
                                ข้อเสนอแนะเพื่อพัฒนา</option>
                            <option <?php echo $type == 'ชื่นชมบุคลากร/หน่วยงาน' ? 'selected' : ''; ?>>
                                ชื่นชมบุคลากร/หน่วยงาน</option>
                            <option <?php echo $type == 'ความปลอดภัย' ? 'selected' : ''; ?>>ความปลอดภัย</option>
                            <option <?php echo $type == 'สิ่งแวดล้อม' ? 'selected' : ''; ?>>สิ่งแวดล้อม</option>
                            <option>อื่น ๆ (โปรดระบุในรายละเอียด)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">รายละเอียด <span
                                class="text-red-500">*</span></label>
                        <textarea rows="3" placeholder="พิมพ์ข้อความที่ต้องการสื่อสาร..."
                            class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 outline-none resize-none"></textarea>
                    </div>

                    <?php if($isProblem): ?>
                    <div class="bg-orange-50/50 p-4 rounded-xl border border-orange-100">
                        <label class="block text-sm font-medium text-slate-700 mb-2">ผลกระทบที่เกิดขึ้น
                            (เลือกได้หลายข้อ)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <?php 
                        $impacts = ['กระทบผู้ป่วย', 'กระทบนักศึกษา', 'กระทบบุคลากร', 'กระทบคุณภาพบริการ', 'กระทบความปลอดภัย', 'กระทบภาพลักษณ์องค์กร'];
                        foreach($impacts as $item): ?>
                            <label
                                class="flex items-center gap-2 p-2 border rounded-lg bg-white border-orange-200 text-sm cursor-pointer">
                                <input type="checkbox" class="rounded text-orange-500 focus:ring-orange-500">
                                <span class="text-slate-600 text-xs"><?php echo $item; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="space-y-4 pt-4">
                    <div class="flex items-center border-b border-emerald-100 pb-2">
                        <h3 class="font-bold text-emerald-800 flex items-center gap-2">
                            <span
                                class="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                            บริบทและหลักฐาน
                        </h3>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">สถานที่/หน่วยงาน <span
                                    class="text-red-500">*</span></label>
                            <select
                                class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-emerald-500">
                                <option value="">เลือกสถานที่</option>
                                <option>OPD</option>
                                <option>IPD</option>
                                <option>ER</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">วันที่เกิดเหตุการณ์</label>
                            <input type="date"
                                class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">แนบรูปภาพ/เอกสาร
                            (ไม่บังคับ)</label>
                        <div
                            class="border-2 border-dashed border-emerald-200 bg-emerald-50/30 rounded-xl p-6 text-center hover:bg-emerald-50 cursor-pointer">
                            <i data-lucide="image" class="mx-auto text-emerald-400 mb-2 w-8 h-8"></i>
                            <p class="text-sm text-emerald-600 font-medium">แตะเพื่อถ่ายรูป หรือเลือกจากคลัง</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-4">
                    <div class="flex items-center border-b border-emerald-100 pb-2">
                        <h3 class="font-bold text-emerald-800 flex items-center gap-2">
                            <span
                                class="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                            การติดตามผล
                        </h3>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">ต้องการเปิดเผยตัวตนหรือไม่?</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="identity" class="text-emerald-600 w-4 h-4" checked>
                                <span class="text-sm text-slate-700">ไม่เปิดเผย (Anonymous)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="identity" class="text-emerald-600 w-4 h-4">
                                <span class="text-sm text-slate-700">เปิดเผยตัวตน</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-2">ต้องการให้ติดต่อกลับหรือไม่?</label>
                        <div class="flex gap-4 mb-3">
                            <label class="flex items-center gap-2 cursor-pointer"
                                onclick="document.getElementById('contactInput').style.display='none'">
                                <input type="radio" name="contact" class="text-emerald-600 w-4 h-4" checked>
                                <span class="text-sm text-slate-700">ไม่จำเป็น</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer"
                                onclick="document.getElementById('contactInput').style.display='block'">
                                <input type="radio" name="contact" class="text-emerald-600 w-4 h-4">
                                <span class="text-sm text-slate-700">ต้องการ</span>
                            </label>
                        </div>

                        <div id="contactInput" style="display:none;" class="mt-2">
                            <input type="text" placeholder="ระบุช่องทางติดต่อ (เบอร์โทรศัพท์, อีเมล, LINE)"
                                class="w-full p-3 bg-slate-50 border border-emerald-200 rounded-xl text-sm focus:border-emerald-500 outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-6 pb-8 flex gap-3">
                    <button onclick="window.location.href='index.php'"
                        class="flex-1 py-3 px-4 bg-slate-100 text-slate-600 font-medium rounded-xl hover:bg-slate-200">ยกเลิก</button>
                    <button onclick="window.location.href='success.php?id=VOC-2026-0005'"
                        class="flex-1 py-3 px-4 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200">ส่งข้อมูล</button>
                </div>

            </div>
        </div>
    </div>

    <script>
    lucide.createIcons();
    </script>
</body>

</html>