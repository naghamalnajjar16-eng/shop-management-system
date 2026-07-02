<?php
require_once 'config.php';
requireLogin();

// Handle delete action
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $success = "تم حذف المنتج بنجاح";
    } catch (PDOException $e) {
        $error = "خطأ في حذف المنتج: " . $e->getMessage();
    }
}

// Get all products
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("خطأ في جلب المنتجات: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المنتجات - متجر الإدارة</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">نظام إدارة المتجر</div>
            <nav>
                <ul>
                    <li><a href="index.php">لوحة التحكم</a></li>
                    <li><a href="products.php" class="active">المنتجات</a></li>
                    <li><a href="customers.php">العملاء</a></li>
                    <li><a href="logout.php">تسجيل الخروج</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container main-content">
        <div class="page-header">
            <h1>إدارة المنتجات</h1>
            <p>هنا يمكنك إدارة منتجات المتجر</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="content-header">
            <h2>قائمة المنتجات</h2>
            <a href="product_create.php" class="btn">إضافة منتج جديد</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المنتج</th>
                        <th>الفئة</th>
                        <th>السعر</th>
                        <th>المخزون</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['category']; ?></td>
                                <td><?php echo number_format($product['price'], 2); ?> ر.س</td>
                                <td><?php echo $product['stock']; ?></td>
                                <td class="actions">
                                    <a href="product_view.php?id=<?php echo $product['id']; ?>" class="btn btn-sm">عرض</a>
                                    <a href="product_edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm">تعديل</a>
                                    <a href="products.php?delete=<?php echo $product['id']; ?>" class="btn btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">لا توجد منتجات</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>نظام إدارة المتجر &copy; <?php echo date('Y'); ?></p>
        </div>
    </footer>
</body>
</html>