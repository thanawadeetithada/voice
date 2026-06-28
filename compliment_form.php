<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOICESRI - ชื่นชมบุคลากร/หน่วยงาน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Sarabun', sans-serif;
    }
    </style>
</head>

<body class="bg-white lg:bg-emerald-50/30 text-slate-800 min-h-screen flex flex-col">

    <header
        class="bg-emerald-700 text-white p-6 lg:px-10 lg:py-5 rounded-b-3xl lg:rounded-none shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto w-full flex justify-between items-center">
            <div class="flex items-center gap-4">

                <button onclick="window.history.back()"
                    class="flex items-center gap-2 hover:text-emerald-200 transition-colors pr-4 border-r border-emerald-500/50">
                    <i class="fa-solid fa-arrow-left text-lg"></i>
                </button>

                <h1 class="text-2xl font-bold tracking-tight cursor-pointer" onclick="window.location.href='index.php'">
                    VOICESRI
                </h1>
                <span
                    class="text-emerald-200 font-medium border-emerald-500/50">ชื่นชมบุคลากรหรือหน่วยงาน
</span>
            </div>

            <button class="hidden">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
    </header>

    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-8 lg:py-12">

        <div class="hidden lg:block mb-8">
            <h2 class="text-3xl font-bold text-emerald-900">ชื่นชมบุคลากรหรือหน่วยงาน</h2>
            <p class="hidden lg:block text-slate-600 mt-2 text-lg">ส่งต่อกำลังใจและคำชื่นชมของคุณ
                เพื่อเป็นพลังบวกให้กับทีมงานของเรา</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-stretch">

            <div class="lg:col-span-7">
                <div
                    class="h-full lg:bg-white lg:p-8 lg:rounded-3xl lg:shadow-sm lg:border lg:border-emerald-100 flex flex-col">

                    <div class="flex items-center border-b border-emerald-100 pb-3 lg:pb-4 mb-5">
                        <h3 class="font-bold text-emerald-800 flex items-center gap-3 lg:text-lg">
                            <span
                                class="bg-emerald-100 text-emerald-700 w-7 h-7 rounded-full flex items-center justify-center text-sm">1</span>
                            ข้อมูลจากผู้แจ้ง (Voice)
                        </h3>
                    </div>

                    <div class="space-y-5 flex-1 flex flex-col">
                        <div>
                            <label class="block text-sm lg:text-base font-medium text-slate-700 mb-1.5">ประเภทเรื่อง
                                <span class="text-red-500">*</span></label>
                            <select id="issue_type" onchange="toggleDetailsRequired()"
                                class="w-full p-3 lg:p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm lg:text-base outline-none focus:border-emerald-500 cursor-pointer">
                                <option value="">เลือกประเภทเรื่อง</option>
                                <option value="ปัญหาการปฏิบัติงาน">ปัญหาการปฏิบัติงาน</option>
                                <option value="ข้อเสนอแนะเพื่อพัฒนา">ข้อเสนอแนะเพื่อพัฒนา</option>
                                <option value="ชื่นชมบุคลากร/หน่วยงาน">ชื่นชมบุคลากร/หน่วยงาน</option>
                                <option value="ความปลอดภัย">ความปลอดภัย</option>
                                <option value="สิ่งแวดล้อม">สิ่งแวดล้อม</option>
                                <option value="เทคโนโลยีสารสนเทศ">เทคโนโลยีสารสนเทศ</option>
                                <option value="ทรัพยากรบุคคล">ทรัพยากรบุคคล</option>
                                <option value="การเรียนการสอน">การเรียนการสอน</option>
                                <option value="งานวิจัย">งานวิจัย</option>
                                <option value="อื่นๆ">อื่น ๆ (โปรดระบุในรายละเอียด)</option>
                            </select>
                        </div>

                        <div class="flex-1 flex flex-col">
                            <label class="block text-sm lg:text-base font-medium text-slate-700 mb-1.5">
                                รายละเอียด <span id="required-mark" class="text-red-500 hidden">*</span>
                            </label>
                            <textarea id="details" rows="4" placeholder="พิมพ์ข้อความที่ต้องการสื่อสาร..."
                                class="flex-1 w-full p-3 lg:p-4 bg-slate-50 border border-slate-200 rounded-xl text-sm lg:text-base focus:border-emerald-500 outline-none resize-none lg:min-h-[180px]"></textarea>
                        </div>

                        <div class="bg-orange-50/50 p-4 lg:p-6 rounded-xl border border-orange-100">
                            <label class="block text-sm lg:text-base font-medium text-slate-700 mb-3">
                                ผลกระทบที่เกิดขึ้น (เลือกได้หลายข้อ)
                            </label>

                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                                <?php 
                                $impacts = [
                                    'กระทบผู้ป่วย', 
                                    'กระทบนักศึกษา', 
                                    'กระทบบุคลากร', 
                                    'กระทบคุณภาพบริการ', 
                                    'กระทบความปลอดภัย', 
                                    'กระทบภาพลักษณ์องค์กร',
                                    'ยังไม่เกิดผลกระทบ แต่มีความเสี่ยง'
                                ];
                                
                                foreach($impacts as $index => $item): 
                                    $isLastItem = ($index === count($impacts) - 1);
                                    $colSpanClass = $isLastItem ? 'col-span-2 lg:col-span-3' : '';
                                ?>
                                <label
                                    class="flex items-center gap-2.5 p-2.5 border rounded-lg bg-white border-orange-200 text-sm cursor-pointer hover:border-orange-400 transition-colors <?php echo $colSpanClass; ?>">
                                    <input type="checkbox" name="impacts[]" value="<?php echo $item; ?>"
                                        class="rounded w-4 h-4 text-orange-500 focus:ring-orange-500 border-slate-300">
                                    <span class="text-slate-600 text-xs lg:text-sm">
                                        <?php echo $item; ?>
                                    </span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="lg:col-span-5 flex flex-col gap-5 lg:gap-[15px]">

                <div class="pt-4 lg:bg-white lg:p-8 lg:rounded-3xl lg:shadow-sm lg:border lg:border-emerald-100">
                    <div class="flex items-center border-b border-emerald-100 pb-3 lg:pb-4 mb-5">
                        <h3 class="font-bold text-emerald-800 flex items-center gap-3 lg:text-lg">
                            <span
                                class="bg-emerald-100 text-emerald-700 w-7 h-7 rounded-full flex items-center justify-center text-sm">2</span>
                             ข้อมูลสำหรับการวิเคราะห์
                        </h3>
                    </div>

                    <div class="space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label 
                                class="block text-sm lg:text-base font-medium text-slate-700 mb-1.5">เลือกสถานที่/หน่วยงาน
                                    <span class="text-red-500">*</span></label>
                                <select
                                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm lg:text-base outline-none focus:border-emerald-500">
                                    <option value="">เลือกสถานที่/หน่วยงาน</option>
                                    <option>OPD</option>
                                    <option>IPD</option>
                                    <option>ER</option>
                                    <option>ห้องผ่าตัด</option>
                                    <option>ศูนย์หัวใจ</option>
                                    <option>ห้องเรียน</option>
                                    <option>หน่วยงานสนับสนุน</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm lg:text-base font-medium text-slate-700 mb-1.5">วันที่เกิดเหตุ</label>
                                <input type="date"
                                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm lg:text-base outline-none focus:border-emerald-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm lg:text-base font-medium text-slate-700 mb-1.5">แนบรูปภาพ/เอกสาร
                                (ไม่บังคับ)</label>
                            <div
                                class="border-2 border-dashed border-emerald-200 bg-emerald-50/30 rounded-xl p-4 lg:p-5 text-center hover:bg-emerald-50 cursor-pointer transition-colors group">
                                <i data-lucide="image"
                                    class="mx-auto text-emerald-400 mb-2 w-8 h-8 group-hover:scale-110 transition-transform"></i>
                                <p class="text-sm lg:text-base text-emerald-600 font-medium">แตะเพื่อถ่ายรูป
                                    หรือเลือกจากคลัง</p>
                                <p class="text-xs text-slate-400 mt-1">รองรับไฟล์ JPG, PNG, PDF</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="flex-1 pt-4 lg:bg-white lg:p-8 lg:rounded-3xl lg:shadow-sm lg:border lg:border-emerald-100 flex flex-col">
                    <div class="flex items-center border-b border-emerald-100 pb-3 lg:pb-4 mb-5">
                        <h3 class="font-bold text-emerald-800 flex items-center gap-3 lg:text-lg">
                            <span
                                class="bg-emerald-100 text-emerald-700 w-7 h-7 rounded-full flex items-center justify-center text-sm">3</span>
                            การติดตามผล
                        </h3>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-sm lg:text-base font-medium text-slate-700 mb-2.5">ต้องการเปิดเผยตัวตนหรือไม่?</label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="identity" class="text-emerald-600 w-4 h-4 lg:w-5 lg:h-5"
                                        checked>
                                    <span class="text-sm lg:text-base text-slate-700">ไม่เปิดเผย</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="identity" class="text-emerald-600 w-4 h-4 lg:w-5 lg:h-5">
                                    <span class="text-sm lg:text-base text-slate-700">เปิดเผยตัวตน</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm lg:text-base font-medium text-slate-700 mb-2.5">ต้องการให้ติดต่อกลับหรือไม่?</label>
                            <div class="flex gap-6 mb-3">
                                <label class="flex items-center gap-2 cursor-pointer"
                                    onclick="document.getElementById('contactInput').style.display='none'">
                                    <input type="radio" name="contact" class="text-emerald-600 w-4 h-4 lg:w-5 lg:h-5"
                                        checked>
                                    <span class="text-sm lg:text-base text-slate-700">ไม่จำเป็น</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer"
                                    onclick="document.getElementById('contactInput').style.display='block'">
                                    <input type="radio" name="contact" class="text-emerald-600 w-4 h-4 lg:w-5 lg:h-5">
                                    <span class="text-sm lg:text-base text-slate-700">ต้องการ</span>
                                </label>
                            </div>

                            <div id="contactInput" style="display:none;" class="mt-3">
                                <input type="text" placeholder="ระบุช่องทางติดต่อ (เบอร์โทรศัพท์, อีเมล, LINE)"
                                    class="w-full p-3 lg:p-3.5 bg-slate-50 border border-emerald-200 rounded-xl text-sm lg:text-base focus:border-emerald-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div
                class="lg:col-span-12 pt-4 pb-10 lg:pb-0 flex gap-3 lg:gap-4 lg:justify-end border-t border-emerald-100 lg:border-none lg:mt-4">
                <button onclick="window.history.back()"
                    class="flex-1 lg:flex-none lg:w-40 py-3.5 px-4 bg-slate-100 text-slate-600 font-medium rounded-xl hover:bg-slate-200 transition-colors lg:text-lg">ยกเลิก</button>
                <button onclick="handleSubmit()"
                    class="flex-1 lg:flex-none lg:w-48 py-3.5 px-4 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-colors lg:text-lg">ส่งข้อมูล</button>
            </div>

        </div>
    </main>

    <script>
    lucide.createIcons();

    function toggleDetailsRequired() {
        const issueType = document.getElementById('issue_type').value;
        const requiredMark = document.getElementById('required-mark');
        
        if (issueType === 'อื่นๆ') {
            requiredMark.classList.remove('hidden');
        } else {
            requiredMark.classList.add('hidden');
        }
    }

    function handleSubmit() {
        const issueType = document.getElementById('issue_type').value;
        const details = document.getElementById('details').value.trim();

        if (issueType === "") {
            alert("กรุณาเลือกประเภทเรื่องก่อนส่งข้อมูลครับ");
            document.getElementById('issue_type').focus();
            return;
        }

        if (issueType === 'อื่นๆ' && details === "") {
            alert("กรุณาระบุรายละเอียดเพิ่มเติม เนื่องจากท่านเลือกประเภทเรื่อง 'อื่น ๆ'");
            document.getElementById('details').focus();
            return;
        }

        window.location.href = 'success.php';
    }
    </script>
</body>

</html>