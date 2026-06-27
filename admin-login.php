<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Sarabun', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 w-full max-w-md text-center mx-4">
        <div class="mb-6 relative inline-block">
            <div class="absolute inset-0 bg-emerald-100 rounded-full blur-xl opacity-60"></div>
            <img src="https://placehold.co/200x200/2f7c47/fff?text=Mascot" class="w-24 h-24 object-cover relative z-10 mx-auto rounded-full border-4 border-white shadow-sm" alt="Admin Mascot">
        </div>

        <h1 class="text-2xl font-bold text-emerald-900 mb-1">VOICESRI Admin</h1>
        <p class="text-sm text-slate-500 mb-8">ระบบจัดการเสียงเพื่อการพัฒนา</p>

        <form action="admin.php" method="GET" class="space-y-4 text-left">
            <input type="hidden" name="view" value="dash">
            <div>
                <label class="text-sm font-medium text-slate-600">Email / Username</label>
                <input type="text" value="admin" class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Password</label>
                <input type="password" value="password" class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none">
            </div>
            <button type="submit" class="w-full bg-emerald-600 text-white font-bold p-3 rounded-xl hover:bg-emerald-700 mt-4 transition shadow-md shadow-emerald-200">
                เข้าสู่ระบบ
            </button>
        </form>
        
        <div class="text-center pt-4 mt-4 border-t border-slate-100">
            <a href="form.php" class="text-sm text-slate-400 hover:text-emerald-600">กลับหน้าแรกของผู้ใช้ทั่วไป</a>
        </div>
    </div>

</body>
</html>