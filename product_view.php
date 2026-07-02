<?php
require_once 'config.php';
requireLogin();

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
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض المنتج - متجر الإدارة</title>
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
            <h1>عرض المنتج</h1>
            <p>هنا يمكنك عرض تفاصيل المنتج</p>
        </div>

        <div class="content-box">
            <div class="content-header">
                <h2>تفاصيل المنتج</h2>
                <div class="actions">
                    <a href="product_edit.php?id=<?php echo $product['id']; ?>" class="btn">تعديل</a>
                    <a href="products.php" class="btn" style="background-color: #666;">رجوع</a>
                </div>
            </div>
            
            <div style="padding: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <h3>المعلومات الأساسية</h3>
                        <table class="data-table">
                            <tr>
                                <th>اسم المنتج</th>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                            </tr>
                            <tr>
                                <th>الفئة</th>
                                <td><?php echo htmlspecialchars($product['category']); ?></td>
                            </tr>
                            <tr>
                                <th>السعر</th>
                                <td><?php echo number_format($product['price'], 2); ?> ر.س</td>
                            </tr>
                            <tr>
                                <th>الكمية في المخزون</th>
                                <td><?php echo $product['stock']; ?></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div>
                        <h3>وصف المنتج</h3>
                        <div style="background: #1a1a1a; padding: 15px; border-radius: 5px; min-height: 150px;">
                            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>نظام إدارة المتجر &copy; <?php echo date('Y'); ?></p>
        </div>
    </footer>
</body>
</html>