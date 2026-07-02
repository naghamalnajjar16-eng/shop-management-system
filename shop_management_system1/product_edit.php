<?php
require_once 'config.php';
requireLogin();

$error = '';
$success = '';

// Get product ID from URL
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header("Location: products.php");
    exit();
}

// Get product data
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        header("Location: products.php");
        exit();
    }
} catch (PDOException $e) {
    die("خطأ في جلب بيانات المنتج: " . $e->getMessage());
}

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
            $stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, price = ?, stock = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $category, $price, $stock, $description, $product_id]);
            $success = "تم تحديث المنتج بنجاح";
            
            // Update product data
            $product['name'] = $name;
            $product['category'] = $category;
            $product['price'] = $price;
            $product['stock'] = $stock;
            $product['description'] = $description;
        } catch (PDOException $e) {
            $error = "خطأ في تحديث المنتج: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المنتج - متجر الإدارة</title>
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
            <h1>تعديل المنتج</h1>
            <p>هنا يمكنك تعديل بيانات المنتج</p>
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
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="category">الفئة *</label>
                    <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="price">السعر (ر.س) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="stock">الكمية في المخزون</label>
                    <input type="number" id="stock" name="stock" min="0" value="<?php echo htmlspecialchars($product['stock']); ?>">
                </div>

                <div class="form-group">
                    <label for="description">وصف المنتج</label>
                    <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-block">تحديث المنتج</button>
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