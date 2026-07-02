<?php
require_once 'config.php';

// Redirect if already logged in
redirectIfLoggedIn();

// Process login form
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM admin WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            // Use plain text password comparison
            if ($admin && comparePlainPassword($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: index.php");
                exit();
            } else {
                $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
            }
        } catch (PDOException $e) {
            $error = "خطأ في النظام: يرجى المحاولة مرة أخرى لاحقاً";
        }
    } else {
        $error = "يرجى إدخال اسم المستخدم وكلمة المرور";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة المتجر</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-container">
    <div class="login-box">
        <div class="form-container">
            <h2>تسجيل الدخول <br> نظام إدارة المتجر</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">اسم المستخدم</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-block">تسجيل الدخول</button>
            </form>
            
            <div style="text-align: center; margin-top: 20px; color: #888;">
                <p>بيانات الدخول الافتراضية: admin / admin123</p>
            </div>
        </div>
    </div>
</body>
</html>