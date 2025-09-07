<?php
// Load environment variables directly from Docker
$dbHost = $_ENV['DB_HOST'];
$dbUser = $_ENV['DB_USER'];
$dbPass = $_ENV['DB_PASS'];
$dbName = $_ENV['DB_NAME'];
$linkedinUrl = $_ENV['LINKEDIN_URL'];

// Connect to MySQL
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get referrer
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct';

// Get country & city using ip-api
$country = 'Unknown';
$city = 'Unknown';

if (!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
    $geoData = @file_get_contents("http://ip-api.com/json/" . $_SERVER['REMOTE_ADDR'] . "?fields=country,city,status");
    if ($geoData !== false) {
        $geo = json_decode($geoData, true);
        if (isset($geo['status']) && $geo['status'] === 'success') {
            $country = $geo['country'] ?? 'Unknown';
            $city = $geo['city'] ?? 'Unknown';
        }
    }
}

// Insert data into database
$stmt = $conn->prepare("INSERT INTO linkedin_redirects (referrer_url, country, city) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $referrer, $country, $city);
$stmt->execute();
$stmt->close();
$conn->close();

// Redirect to LinkedIn
header("Location: $linkedinUrl");
exit();
?>
