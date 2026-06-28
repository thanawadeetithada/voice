<?php
session_start();
require_once 'db.php';

$modalMessage = '';
$modalType = 'error';
$submittedUsername = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $submittedUsername = $username;

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        if ($admin['userrole'] === 'user') {
            $modalMessage = 'กรุณาติดต่อแอดมิน';
            $modalType = 'warning';
        } else {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_role'] = $admin['userrole'];
            
            header("Location: admin_dashboard.php");
            exit;
        }
    } else {
        $modalMessage = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
        $modalType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Sarabun', sans-serif; }
        img { width: 25%; }
    </style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 w-full max-w-md text-center mx-4">
        <div class="mb-6 relative inline-block">
            <div class="absolute inset-0 bg-emerald-100 rounded-full blur-xl opacity-60"></div>
            <img src="img/logo.png" class="object-contain relative z-10 mx-auto" alt="Admin Mascot" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2951/295128.png'">
        </div>

        <h1 class="text-2xl font-bold text-emerald-900 mb-1">VOICESRI Admin</h1>
        <p class="text-sm text-slate-500 mb-8">เข้าสู่ระบบเพื่อจัดการข้อมูล</p>

        <form action="" method="POST" class="space-y-4 text-left">
            <div>
                <label class="text-sm font-medium text-slate-600">ชื่อผู้ใช้งาน</label>
                <input type="text" name="username" placeholder="กรอกชื่อผู้ใช้..." required 
                       value="<?php echo htmlspecialchars($submittedUsername); ?>"
                       class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-emerald-500 transition-colors">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">รหัสผ่าน</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="••••••••" required 
                           class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-emerald-500 transition-colors">
                    <button type="button" onclick="togglePassword('password', 'eye-icon')" class="absolute right-4 top-4 text-slate-400 hover:text-emerald-600 transition-colors">
                        <i id="eye-icon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="w-full bg-emerald-600 text-white font-bold p-3 rounded-xl hover:bg-emerald-700 mt-4 transition shadow-md shadow-emerald-200">
                เข้าสู่ระบบ
            </button>
        </form>
        
        <div class="flex justify-between items-center text-sm pt-4 mt-6 border-t border-slate-100">
            <a href="index.php" class="text-slate-400 hover:text-emerald-600">กลับหน้าแรก</a>
            <a href="register.php" class="text-emerald-600 font-medium hover:text-emerald-700">สมัครสมาชิกใหม่</a>
        </div>
    </div>

    <div id="alertModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden items-center justify-center opacity-0 transition-opacity duration-300">
        <div id="alertModalContent" class="bg-white rounded-3xl shadow-xl w-full max-w-sm mx-4 overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="p-6 text-center">
                <div id="modalIconContainer" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i id="modalIcon" class="text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">แจ้งเตือน</h3>
                <p id="alertMessage" class="text-slate-600 mb-6"></p>
                <button onclick="closeAlert()" class="w-full bg-slate-100 text-slate-700 font-bold py-3.5 px-4 rounded-xl hover:bg-slate-200 transition-colors">
                    ตกลง
                </button>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    function showAlert(message, type) {
        document.getElementById('alertMessage').innerText = message;
        
        const container = document.getElementById('modalIconContainer');
        const icon = document.getElementById('modalIcon');
        
        if(type === 'warning') {
            container.className = 'w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4';
            icon.className = 'fa-solid fa-triangle-exclamation text-3xl';
        } else {
            container.className = 'w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4';
            icon.className = 'fa-solid fa-circle-xmark text-3xl';
        }

        const modal = document.getElementById('alertModal');
        const modalContent = document.getElementById('alertModalContent');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        void modal.offsetWidth; 
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
    }

    function closeAlert() {
        const modal = document.getElementById('alertModal');
        const modalContent = document.getElementById('alertModalContent');
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    <?php if(!empty($modalMessage)): ?>
        showAlert("<?php echo addslashes($modalMessage); ?>", "<?php echo $modalType; ?>");
    <?php endif; ?>

        console.log("Check Session after login:", <?php echo json_encode($_SESSION ?? []); ?>);

    </script>
</body>
</html>