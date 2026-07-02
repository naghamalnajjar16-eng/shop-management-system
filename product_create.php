<?php
require_once 'config.php';
requireLogin();

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // Validate inputs
    if (empty($name) || empty($category) || empty($price)) {
        $error = "يرجى ملء جميع الحقول المطلوبة";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $category, $price, $stock, $description]);
            $success = "تم إضافة المنتج بنجاح";
            
            // Clear form fields
            $name = $category = $price = $stock = $description = '';
        } catch (PDOException $e) {
            $error = "خطأ في إضافة المنتج: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة منتج جديد - متجر الإدارة</title>
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
                    <li><a href="customers.php">العملاء</a></li>
                    <li><a href="logout.php">تسجيل الخروج</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container main-content">
        <div class="page-header">
            <h1>إضافة منتج جديد</h1>
            <p>هنا يمكنك إضافة منتج جديد إلى المتجر</p>
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
                    <label for="name">اسم المنتج *</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="category">الفئة *</label>
                    <input type="text" id="category" name="category" value="<?php echo isset($category) ? htmlspecialchars($category) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="price">السعر (ر.س) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="stock">الكمية في المخزون</label>
                    <input type="number" id="stock" name="stock" min="0" value="<?php echo isset($stock) ? htmlspecialchars($stock) : '0'; ?>">
                </div>

                <div class="form-group">
                    <label for="description">وصف المنتج</label>
                    <textarea id="description" name="description" rows="4"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                </div>

                <button type="submit" class="btn btn-block">إضافة المنتج</button>
                <a href="products.php" class="btn btn-block" style="background-color: #666; margin-top: 10px;">رجوع إلى القائمة</a>
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