$servername = getenv("DB_HOST") ?: "mysql"; // service name from docker-compose
$username   = getenv("DB_USER") ?: "root";
$password   = getenv("DB_PASS") ?: "secret";
$dbname     = getenv("DB_NAME") ?: "inventory";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
