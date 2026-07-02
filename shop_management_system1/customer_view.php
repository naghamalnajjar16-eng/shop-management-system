<?php
require_once 'config.php';
requireLogin();

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
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض العميل - متجر الإدارة</title>
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
            <h1>عرض العميل</h1>
            <p>هنا يمكنك عرض تفاصيل العميل</p>
        </div>

        <div class="content-box">
            <div class="content-header">
                <h2>تفاصيل العميل</h2>
                <div class="actions">
                    <a href="customer_edit.php?id=<?php echo $customer['id']; ?>" class="btn">تعديل</a>
                    <a href="customers.php" class="btn" style="background-color: #666;">رجوع</a>
                </div>
            </div>
            
            <div style="padding: 20px;">
                <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    <div>
                        <h3>المعلومات الأساسية</h3>
                        <table class="data-table">
                            <tr>
                                <th>اسم العميل</th>
                                <td><?php echo htmlspecialchars($customer['name']); ?></td>
                            </tr>
                            <tr>
                                <th>البريد الإلكتروني</th>
                                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            </tr>
                            <tr>
                                <th>رقم الهاتف</th>
                                <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                            </tr>
                            <tr>
                                <th>العنوان</th>
                                <td><?php echo nl2br(htmlspecialchars($customer['address'])); ?></td>
                            </tr>
                        </table>
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