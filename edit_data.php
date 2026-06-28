<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
$current_page = 'settings';
$error_msg = '';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_settings.php");
    exit;
}

$target_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $new_username = trim($_POST['username']);
    $new_role = $_POST['userrole'];
    $new_password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($new_username)) {
        $error_msg = "กรุณากรอก Username";
    } elseif (!empty($new_password) && $new_password !== $confirm_password) {
        $error_msg = "รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน";
    } else {
        $checkStmt = $conn->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
        $checkStmt->bind_param("si", $new_username, $target_id);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $error_msg = "Username นี้มีผู้ใช้งานอื่นใช้ไปแล้ว กรุณาตั้งชื่ออื่น";
        } else {
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $updateStmt = $conn->prepare("UPDATE admins SET username = ?, password = ?, userrole = ? WHERE id = ?");
                $updateStmt->bind_param("sssi", $new_username, $hashed_password, $new_role, $target_id);
            } else {
                $updateStmt = $conn->prepare("UPDATE admins SET username = ?, userrole = ? WHERE id = ?");
                $updateStmt->bind_param("ssi", $new_username, $new_role, $target_id);
            }

            if ($updateStmt->execute()) {
                if (isset($_SESSION['admin_id']) && $target_id == $_SESSION['admin_id']) {
                    $_SESSION['admin_username'] = $new_username;
                    $_SESSION['admin_role']     = $new_role;
                }

                header("Location: admin_settings.php");
                exit;
            } else {
                $error_msg = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
            }
        }
    }
}

$stmt = $conn->prepare("SELECT id, username, userrole FROM admins WHERE id = ?");
$stmt->bind_param("i", $target_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

if (!$user_data) {
    header("Location: admin_settings.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Data - VOICESRI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Sarabun', sans-serif;
    }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800 h-screen flex overflow-hidden">

    <div id="sidebar"
        class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-slate-200 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="h-20 flex items-center px-6 gap-3 border-b border-slate-100 bg-emerald-50/50">
            <img src="img/logo.png" class="w-8 h-8 rounded-full border border-emerald-200 shadow-sm" alt="Logo"
                onerror="this.style.display='none'">
            <span class="text-xl font-bold text-slate-800 tracking-tight">VOICE<span
                    class="text-emerald-600">SRI</span></span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="admin_dashboard.php"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-500 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
            </a>
            <a href="admin_tickets.php"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-500 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                <i data-lucide="inbox" class="w-5 h-5"></i> Tickets Inbox
            </a>
            <a href="admin_settings.php"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium bg-emerald-600 text-white shadow-md shadow-emerald-200">
                <i data-lucide="settings" class="w-5 h-5"></i> Settings
            </a>
        </nav>
        <div class="p-4 border-t border-slate-100">
            <a href="logout.php" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-500 hover:text-red-500 transition-colors bg-slate-50 rounded-xl">
                <i data-lucide="log-out" class="w-5 h-5"></i> ออกจากระบบ
            </a>
        </div>
    </div>

    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full relative">
        <header
            class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <a href="admin_settings.php"
                    class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 transition-colors font-medium">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> ย้อนกลับไปตารางผู้ใช้
                </a>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-6 pb-20 flex justify-center items-start pt-10">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 max-w-lg w-full space-y-6">

                <div class="border-b border-slate-100 pb-4">
                    <h1 class="text-xl font-bold text-slate-800">แก้ไขข้อมูลผู้ใช้งาน</h1>
                    <p class="text-xs text-slate-400 mt-1">รหัสบัญชีผู้ใช้ ID: #<?php echo $user_data['id']; ?></p>
                </div>

                <?php if(!empty($error_msg)): ?>
                <div
                    class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm flex items-center gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                    <span><?php echo htmlspecialchars($error_msg); ?></span>
                </div>
                <?php endif; ?>

                <form id="editForm" method="POST" class="space-y-4">
                    <input type="hidden" name="update_user" value="1">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Username <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="input_username" name="username" required
                            value="<?php echo htmlspecialchars($user_data['username']); ?>"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">เปลี่ยนรหัสผ่านใหม่
                            (เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="••••••••••••••"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all pr-12">
                            <button type="button" id="toggle_pass_btn"
                                onclick="togglePassword('password', 'toggle_pass_btn')"
                                class="absolute right-4 top-2.5 text-slate-400 hover:text-emerald-600 transition-colors">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">ยืนยันรหัสผ่านใหม่</label>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password"
                                placeholder="••••••••••••••"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all pr-12">
                            <button type="button" id="toggle_confirm_btn"
                                onclick="togglePassword('confirm_password', 'toggle_confirm_btn')"
                                class="absolute right-4 top-2.5 text-slate-400 hover:text-emerald-600 transition-colors">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">สิทธิ์ผู้ใช้งาน (User
                            Role)</label>

                        <div class="relative">

                            <select id="input_role" name="userrole"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all appearance-none pr-10">
                                <option value="user"
                                    <?php echo ($user_data['userrole'] == 'user') ? 'selected' : ''; ?>>User
                                    (ผู้ใช้ทั่วไป)</option>
                                <option value="admin"
                                    <?php echo ($user_data['userrole'] == 'admin') ? 'selected' : ''; ?>>Admin
                                    (ผู้ดูแลระบบ)</option>
                            </select>

                            <div
                                class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-500">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>

                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="openConfirmModal()"
                            class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm transition-colors shadow-sm shadow-emerald-100">
                            บันทึกการเปลี่ยนแปลง
                        </button>
                        <a href="admin_settings.php"
                            class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-sm transition-colors text-center">
                            ยกเลิก
                        </a>
                    </div>
                </form>

            </div>
        </main>
    </div>

    <div id="confirmModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity px-4">
        <div class="relative bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-xl transform transition-all">
            <div
                class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="help-circle" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">ยืนยันการบันทึกข้อมูล</h3>
            <p class="text-sm text-slate-500 mb-6">คุณต้องการอัปเดตข้อมูลของ <br><span id="show_modal_user"
                    class="font-bold text-emerald-600"></span> ใช่หรือไม่?</p>

            <div class="flex gap-3">
                <button type="button" onclick="closeConfirmModal()"
                    class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition-colors">
                    ยกเลิก
                </button>
                <button type="button" onclick="submitRealForm()"
                    class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition-colors shadow-sm shadow-emerald-200">
                    ยืนยันบันทึก
                </button>
            </div>
        </div>
    </div>

    <script>
    lucide.createIcons();

    function togglePassword(inputId, btnId) {
        const input = document.getElementById(inputId);
        const btn = document.getElementById(btnId);
        if (input.type === "password") {
            input.type = "text";
            btn.innerHTML = '<i data-lucide="eye-off" class="w-5 h-5"></i>';
        } else {
            input.type = "password";
            btn.innerHTML = '<i data-lucide="eye" class="w-5 h-5"></i>';
        }
        lucide.createIcons();
    }

    function openConfirmModal() {
        const form = document.getElementById('editForm');
        const pass = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if (pass !== '' || confirm !== '') {
            if (pass !== confirm) {
                alert("รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง");
                return;
            }
        }

        const currentName = document.getElementById('input_username').value;
        document.getElementById('show_modal_user').innerText = '"' + currentName + '"';

        const modal = document.getElementById('confirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function submitRealForm() {
        document.getElementById('editForm').submit();
    }
    </script>
</body>

</html>