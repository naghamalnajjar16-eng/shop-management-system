<?php
require_once 'config.php';
requireLogin();

$error = '';
$success = '';

// Get customer ID from URL
$customer_id = $_GET['id'] ?? null;

if (!$customer_id) {
    header("Location: customers.php");
    exit();
}

// Get customer data
try {
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch();
    
    if (!$customer) {
        header("Location: customers.php");
        exit();
    }
} catch (PDOException $e) {
    die("خطأ في جلب بيانات العميل: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    
    // Validate inputs
    if (empty($name)) {
        $error = "يرجى إدخال اسم العميل";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $address, $customer_id]);
            $success = "تم تحديث العميل بنجاح";
            
            // Update customer data
            $customer['name'] = $name;
            $customer['email'] = $email;
            $customer['phone'] = $phone;
            $customer['address'] = $address;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "البريد الإلكتروني مسجل مسبقاً";
            } else {
                $error = "خطأ في تحديث العميل: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل العميل - متجر الإدارة</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">نظام إدارة المتجر</div>
            <nav>
                <ul>
                    <li><a href="index.php">لوحة التحكم</a></li>
                    <li><a href="products.php">المنتجات</a></li>
                    <li><a href="customers.php" class="active">العملاء</a></li>
                    <li><a href="logout.php">تسجيل الخروج</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container main-content">
        <div class="page-header">
            <h1>تعديل العميل</h1>
            <p>هنا يمكنك تعديل بيانات العميل</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">اسم العميل *</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>">
                </div>

                <div class="form-group">
                    <label for="phone">رقم الهاتف</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>">
                </div>

                <div class="form-group">
                    <label for="address">العنوان</label>
                    <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($customer['address']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-block">تحديث العميل</button>
                <a href="customers.php" class="btn btn-block" style="background-color: #666; margin-top: 10px;">رجوع إلى القائمة</a>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>نظام إدارة المتجر &copy; <?php echo date('Y'); ?></p>
        </div>
    </footer>
</body>
</html>