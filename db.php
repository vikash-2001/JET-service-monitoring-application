<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get statistics
$total_stock = $conn->query("SELECT SUM(quantity) as total FROM inward_stock")->fetch_assoc()['total'];
$total_outward = $conn->query("SELECT SUM(quantity) as total FROM outward_stock")->fetch_assoc()['total'];
$total_billing = $conn->query("SELECT SUM(quantity * price) as total FROM outward_stock")->fetch_assoc()['total'];
$total_inward = $conn->query("SELECT COUNT(*) as total FROM inward_stock")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>JET - Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
        .sidebar { background: #2c3e50; color: white; width: 250px; height: 100vh; position: fixed; padding: 20px; }
        .sidebar h2 { color: #3498db; margin-bottom: 30px; text-align: center; }
        .sidebar ul { list-style: none; }
        .sidebar li { margin-bottom: 10px; }
        .sidebar a { color: #bdc3c7; text-decoration: none; padding: 10px; display: block; border-radius: 5px; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; color: white; }
        .main-content { margin-left: 250px; padding: 20px; }
        .top-bar { background: white; padding: 15px 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; display: flex; justify-content: between; align-items: center; }
        .search-bar { flex: 1; margin-right: 20px; }
        .search-bar input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; }
        .user-profile { display: flex; align-items: center; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .card h3 { color: #7f8c8d; font-size: 14px; margin-bottom: 10px; }
        .card .number { font-size: 32px; font-weight: bold; color: #2c3e50; }
        .card.total-stock { border-top: 4px solid #3498db; }
        .card.total-outward { border-top: 4px solid #e74c3c; }
        .card.total-billing { border-top: 4px solid #2ecc71; }
        .card.total-inward { border-top: 4px solid #f39c12; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>JET Inventory</h2>
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="inward.php">Inward Stock</a></li>
            <li><a href="outward.php">Outward Stock</a></li>
            <li><a href="billing.php">Billing</a></li>
            <li><a href="store.php">Store Details</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <div class="user-profile">
                Welcome, <?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['role']; ?>)
            </div>
        </div>

        <div class="cards">
            <div class="card total-stock">
                <h3>Total Stock</h3>
                <div class="number"><?php echo $total_stock ?: '0'; ?></div>
            </div>
            <div class="card total-outward">
                <h3>Total Outward</h3>
                <div class="number"><?php echo $total_outward ?: '0'; ?></div>
            </div>
            <div class="card total-billing">
                <h3>Total Billing Amount</h3>
                <div class="number">₹<?php echo number_format($total_billing ?: '0', 2); ?></div>
            </div>
            <div class="card total-inward">
                <h3>Total Inward</h3>
                <div class="number"><?php echo $total_inward ?: '0'; ?></div>
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3>Recent Activity</h3>
            <p>Welcome to Jeyam Electro Tech Inventory Management System</p>
        </div>
    </div>
</body>
</html>