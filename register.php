<?php
session_start();
require_once 'db.php';

$modalMessage = '';
$modalType = ''; 
$submittedUsername = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $submittedUsername = $username; // เก็บค่า username ไว้เพื่อนำไปแสดงกลับใน form กรณีเกิดข้อผิดพลาด

    if (empty($username) || empty($password)) {
        $modalMessage = 'กรุณากรอกข้อมูลให้ครบถ้วน';
        $modalType = 'error';
    } elseif ($password !== $confirm_password) {
        $modalMessage = 'รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน';
        $modalType = 'error';
    } else {
        // เช็คว่ามี username นี้ในระบบหรือยัง
        $stmt_check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $modalMessage = 'ชื่อผู้ใช้งานนี้มีในระบบแล้ว กรุณาใช้ชื่ออื่น';
            $modalType = 'error';
        } else {
            // สมัครสมาชิก
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user'; // ตั้งค่าเริ่มต้นเป็น user

            $stmt_insert = $conn->prepare("INSERT INTO admins (username, password, userrole) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $hashed_password, $role);
            
            if ($stmt_insert->execute()) {
                $modalMessage = 'สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ';
                $modalType = 'success';
                $submittedUsername = ''; // เคลียร์ username หลังสมัครสำเร็จ
            } else {
                $modalMessage = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง';
                $modalType = 'error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - VOICESRI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Sarabun', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 w-full max-w-md text-center mx-4">
        
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-user-plus text-2xl"></i>
        </div>

        <h1 class="text-2xl font-bold text-emerald-900 mb-1">สมัครสมาชิก</h1>
        <p class="text-sm text-slate-500 mb-8">สร้างบัญชีใหม่สำหรับ VOICESRI</p>

        <form action="" method="POST" id="registerForm" class="space-y-4 text-left">
            <div>
                <label class="text-sm font-medium text-slate-600">ชื่อผู้ใช้งาน</label>
                <input type="text" name="username" placeholder="กำหนดชื่อผู้ใช้..." required 
                       value="<?php echo htmlspecialchars($submittedUsername); ?>"
                       class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-emerald-500 transition-colors">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">รหัสผ่าน</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="••••••••" required 
                           class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-emerald-500 transition-colors">
                    <button type="button" onclick="togglePassword('password', 'eye-icon-1')" class="absolute right-4 top-4 text-slate-400 hover:text-emerald-600 transition-colors">
                        <i id="eye-icon-1" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">ยืนยันรหัสผ่าน</label>
                <div class="relative">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required 
                           class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-emerald-500 transition-colors">
                    <button type="button" onclick="togglePassword('confirm_password', 'eye-icon-2')" class="absolute right-4 top-4 text-slate-400 hover:text-emerald-600 transition-colors">
                        <i id="eye-icon-2" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" onclick="return validateForm(event)" class="w-full bg-emerald-600 text-white font-bold p-3 rounded-xl hover:bg-emerald-700 mt-4 transition shadow-md shadow-emerald-200">
                สมัครสมาชิก
            </button>
        </form>
        
        <div class="text-center pt-4 mt-6 border-t border-slate-100">
            <p class="text-sm text-slate-500">มีบัญชีอยู่แล้ว? <a href="admin_login.php" class="text-emerald-600 font-medium hover:text-emerald-700">เข้าสู่ระบบ</a></p>
        </div>
    </div>

    <div id="alertModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden items-center justify-center opacity-0 transition-opacity duration-300">
        <div id="alertModalContent" class="bg-white rounded-3xl shadow-xl w-full max-w-sm mx-4 overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="p-6 text-center">
                <div id="modalIconContainer" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i id="modalIcon" class="text-3xl"></i>
                </div>
                <h3 id="modalTitle" class="text-xl font-bold text-slate-800 mb-2">แจ้งเตือน</h3>
                <p id="alertMessage" class="text-slate-600 mb-6"></p>
                <button id="modalBtn" onclick="closeAlert()" class="w-full bg-slate-100 text-slate-700 font-bold py-3.5 px-4 rounded-xl hover:bg-slate-200 transition-colors">
                    ตกลง
                </button>
            </div>
        </div>
    </div>

    <script>
    let isSuccess = false;

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
        const title = document.getElementById('modalTitle');
        const btn = document.getElementById('modalBtn');
        
        if(type === 'success') {
            container.className = 'w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4';
            icon.className = 'fa-solid fa-circle-check text-3xl';
            title.innerText = 'สำเร็จ!';
            btn.className = 'w-full bg-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl hover:bg-emerald-700 transition-colors';
            isSuccess = true;
        } else {
            container.className = 'w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4';
            icon.className = 'fa-solid fa-circle-xmark text-3xl';
            title.innerText = 'เกิดข้อผิดพลาด';
            btn.className = 'w-full bg-slate-100 text-slate-700 font-bold py-3.5 px-4 rounded-xl hover:bg-slate-200 transition-colors';
            isSuccess = false;
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
            
            // หากสำเร็จ ให้ redirect ไปหน้าล็อกอินหลังจากปิด modal
            if(isSuccess) {
                window.location.href = 'admin_login.php';
            }
        }, 300);
    }

    // ตรวจสอบความถูกต้องของรหัสผ่านฝั่ง Client
    function validateForm(e) {
        const pass = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;
        
        if(pass !== confirm && pass !== '' && confirm !== '') {
            e.preventDefault(); // ยกเลิกการ submit
            showAlert("รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน", "error");
            return false;
        }
        return true;
    }

    // เรียกใช้ Modal หากมีข้อความจาก PHP
    <?php if(!empty($modalMessage)): ?>
        showAlert("<?php echo addslashes($modalMessage); ?>", "<?php echo $modalType; ?>");
    <?php endif; ?>
    </script>
</body>
</html>