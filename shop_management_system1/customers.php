<?php
require_once 'config.php';
requireLogin();

// Handle delete action
if (isset($_GET['delete'])) {
    $customer_id = $_GET['delete'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->execute([$customer_id]);
        $success = "تم حذف العميل بنجاح";
    } catch (PDOException $e) {
        $error = "خطأ في حذف العميل: " . $e->getMessage();
    }
}

// Get all customers
try {
    $stmt = $pdo->query("SELECT * FROM customers ORDER BY id DESC");
    $customers = $stmt->fetchAll();
} catch (PDOException $e) {
    die("خطأ في جلب العملاء: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة العملاء - متجر الإدارة</title>
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
            <h1>إدارة العملاء</h1>
            <p>هنا يمكنك إدارة عملاء المتجر</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="content-header">
            <h2>قائمة العملاء</h2>
            <a href="customer_create.php" class="btn">إضافة عميل جديد</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم العميل</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>العنوان</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($customers) > 0): ?>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?php echo $customer['id']; ?></td>
                                <td><?php echo $customer['name']; ?></td>
                                <td><?php echo $customer['email']; ?></td>
                                <td><?php echo $customer['phone']; ?></td>
                                <td><?php echo $customer['address']; ?></td>
                                <td class="actions">
                                    <a href="customer_view.php?id=<?php echo $customer['id']; ?>" class="btn btn-sm">عرض</a>
                                    <a href="customer_edit.php?id=<?php echo $customer['id']; ?>" class="btn btn-sm">تعديل</a>
                                    <a href="customers.php?delete=<?php echo $customer['id']; ?>" class="btn btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا العميل؟')">حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">لا توجد عملاء</td>
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