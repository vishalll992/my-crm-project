<style>
.sidebar-container {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    transition: width 0.3s ease;
    z-index: 1000;
}

.sidebar {
    width: 220px;
    background: linear-gradient(to bottom, #1e3c72, #2a5298);
    min-height: 100vh;
    padding: 20px 15px;
    box-sizing: border-box;
    color: white;
    font-family: 'Segoe UI', sans-serif;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    transition: width 0.3s ease;
    overflow-x: hidden;
}

.sidebar.collapsed {
    width: 60px;
}

.sidebar h2 {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 30px;
    text-align: center;
    letter-spacing: 1px;
    color: #ecf0f1;
    transition: opacity 0.3s ease;
}

.sidebar.collapsed h2 {
    opacity: 0;
}

.sidebar a {
    display: flex;
    align-items: center;
    color: #ecf0f1;
    text-decoration: none;
    margin: 12px 0;
    padding: 10px 15px;
    border-radius: 8px;
    transition: 0.3s ease;
    font-size: 15px;
    font-weight: 500;
    white-space: nowrap;
}

.sidebar a span {
    margin-left: 10px;
    transition: opacity 0.3s ease;
}

.sidebar.collapsed a span {
    opacity: 0;
}

.sidebar a:hover {
    background-color: rgba(255, 255, 255, 0.2);
    padding-left: 20px;
}

.sidebar a.logout {
    color: #ff6b6b;
    font-weight: bold;
    margin-top: 30px;
    border-top: 1px solid rgba(255,255,255,0.2);
    padding-top: 15px;
}

.toggle-btn {
    position: absolute;
    top: 10px;
    right: -15px;
    background: #2980b9;
    color: white;
    border: none;
    padding: 6px 10px;
    font-size: 18px;
    cursor: pointer;
    border-radius: 0 5px 5px 0;
    z-index: 1001;
}

.main {
    margin-left: 220px;
    padding: 30px;
    transition: margin-left 0.3s ease;
}

.sidebar.collapsed ~ .main {
    margin-left: 60px;
}
</style>

<div class="sidebar-container">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <div class="sidebar" id="sidebar">
        <h2>📦 Inventory</h2>
        <a href="index.php">🏠 <span>Dashboard</span></a>
        <a href="products.php">📦 <span>Products</span></a>
        <a href="services.php">🛠️ <span>Services</span></a>
        <a href="pickup_delivery.php">🚚 <span>Pickup & Delivery</span></a>
        <a href="leads.php">📬 <span>Leads</span></a>
        <a href="sales.php">💰 <span>Sales</span></a>
        <a href="customers.php">👤 <span>Customers</span></a>
        <a href="orders.php">🧾 <span>Orders</span></a>
        <a href="reports.php">📊 <span>Reports</span></a>
        <a href="employees.php">👥 <span>Employees</span></a>
        <a href="tasks.php">📝 <span>Tasks</span></a>
        <a href="add_product.php">➕ <span>Add Product</span></a>
        <a href="register.php"> <span>Register</span></a>
        <a href="logout.php" class="logout">🚪 <span>Logout</span></a>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
}
</script>

