<?php $id = isset($_GET['id']) ? $_GET['id'] : 'VOC-XXXX-XXXX'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ส่งข้อมูลสำเร็จ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Sarabun', sans-serif; }
    </style>
</head>
<body class="bg-emerald-50/30 text-slate-800">

<div class="md:py-10 min-h-screen flex flex-col items-center">
    <div class="w-full max-w-md mx-auto bg-white min-h-screen md:min-h-[85vh] shadow-2xl relative flex flex-col md:rounded-3xl border border-emerald-100">
        
        <div class="p-8 text-center flex flex-col items-center justify-center h-full min-h-[60vh] flex-1">
            <div class="mb-4 relative">
                <div class="absolute inset-0 bg-orange-100 rounded-full blur-xl opacity-50"></div>
                <img src="https://placehold.co/400x400/2f7c47/fff?text=Mascot+Happy" class="w-32 h-32 object-cover relative z-10 mx-auto rounded-full border-4 border-white shadow-lg" alt="Success">
            </div>

            <h2 class="text-2xl font-bold text-emerald-800 mb-2">ขอบคุณค่ะ</h2>
            <p class="text-slate-600 text-sm mb-8">ที่ร่วมเป็นส่วนหนึ่งในการพัฒนาองค์กรของเรา</p>
            
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 w-full mb-8 shadow-inner">
                <p class="text-xs text-emerald-600 uppercase tracking-wider mb-1 font-bold">Ticket ID ของคุณคือ</p>
                <p class="text-2xl font-mono font-bold text-emerald-700"><?php echo htmlspecialchars($id); ?></p>
                <p class="text-xs text-slate-500 mt-2">* กรุณาบันทึกรหัสนี้ไว้เพื่อใช้ติดตามความคืบหน้า</p>
            </div>

            <div class="space-y-3 w-full mt-auto mb-8">
                <button onclick="window.location.href='track.php?id=<?php echo $id; ?>'" class="w-full py-3 px-4 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 shadow-md shadow-orange-200">ดูสถานะตอนนี้เลย</button>
                <button onclick="window.location.href='index.php'" class="w-full py-3 px-4 bg-white border border-slate-200 text-slate-600 font-medium rounded-xl hover:bg-slate-50">กลับหน้าหลัก</button>
            </div>
        </div>

    </div>
</div>

</body>
</html>