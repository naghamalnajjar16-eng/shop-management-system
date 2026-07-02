<?php
require_once 'config.php';
requireLogin();

// Get counts for dashboard
try {
    $products_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $categories_count = $pdo->query("SELECT COUNT(DISTINCT category) FROM products")->fetchColumn();
    $customers_count = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
    
    // Since we no longer have orders table, we'll set these to 0
    $orders_count = 0;
    $pending_orders = 0;
    $completed_orders = 0;
    
    // Get recent products instead of orders
    $recent_products = $pdo->query("
        SELECT name, category, price, stock 
        FROM products 
        ORDER BY id DESC 
        LIMIT 5
    ")->fetchAll();
    
    // Get low stock products
    $low_stock_products = $pdo->query("
        SELECT name, stock 
        FROM products 
        WHERE stock < 10 
        ORDER BY stock ASC 
        LIMIT 5
    ")->fetchAll();
    
    // Since we don't have orders, we'll create empty arrays for charts
    $chart_labels = [];
    $chart_orders = [];
    $chart_sales = [];
    
} catch (PDOException $e) {
    die("خطأ في جلب البيانات: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - متجر الإدارة</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">نظام إدارة المتجر</div>
            <nav>
                <ul>
                    <li><a href="index.php" class="active">لوحة التحكم</a></li>
                    <li><a href="products.php">المنتجات</a></li>
                    <li><a href="customers.php">العملاء</a></li>
                    <li><a href="logout.php">تسجيل الخروج</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container main-content">
        <div class="page-header">
            <h1>لوحة التحكم الرئيسية</h1>
            <p>مرحباً بعودتك، <?php echo $_SESSION['admin_username']; ?>! إليك نظرة عامة على متجرك.</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-icon">
                    <i>📦</i>
                </div>
                <div class="card-content">
                    <h3>المنتجات</h3>
                    <p><?php echo $products_count; ?></p>
                    <a href="products.php" class="btn">إدارة المنتجات</a>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-icon">
                    <i>📁</i>
                </div>
                <div class="card-content">
                    <h3>الفئات</h3>
                    <p><?php echo $categories_count; ?></p>
                    <a href="products.php" class="btn">عرض الفئات</a>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-icon">
                    <i>👥</i>
                </div>
                <div class="card-content">
                    <h3>العملاء</h3>
                    <p><?php echo $customers_count; ?></p>
                    <a href="customers.php" class="btn">إدارة العملاء</a>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-icon">
                    <i>📊</i>
                </div>
                <div class="card-content">
                    <h3>التقارير</h3>
                    <p>-</p>
                    <a href="#" class="btn">عرض التقارير</a>
                </div>
            </div>
        </div>
        
        <!-- Recent Products and Low Stock Section -->
        <div class="content-section">
            <div class="content-box">
                <div class="content-header">
                    <h2>أحدث المنتجات</h2>
                    <a href="products.php" class="btn">عرض الكل</a>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>اسم المنتج</th>
                                <th>الفئة</th>
                                <th>السعر</th>
                                <th>المخزون</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recent_products) > 0): ?>
                                <?php foreach ($recent_products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['name']; ?></td>
                                        <td><?php echo $product['category']; ?></td>
                                        <td><?php echo number_format($product['price'], 2); ?> ر.س</td>
                                        <td><?php echo $product['stock']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">لا توجد منتجات</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="content-box">
                <div class="content-header">
                    <h2>منتجات قاربت على النفاد</h2>
                    <a href="products.php" class="btn">عرض الكل</a>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>اسم المنتج</th>
                                <th>الكمية المتاحة</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($low_stock_products) > 0): ?>
                                <?php foreach ($low_stock_products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['name']; ?></td>
                                        <td><?php echo $product['stock']; ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $product['stock'] == 0 ? 'status-cancelled' : 'status-pending'; ?>">
                                                <?php echo $product['stock'] == 0 ? 'نفذت الكمية' : 'كمية قليلة'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" style="text-align: center;">لا توجد منتجات قاربت على النفاد</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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