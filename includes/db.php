<?php
define("SERVERNAME", "localhost");
define("DBNAME", "sms");
define("DBUSERNAME", "root");
define("DBPASSWORD", "");

try {
    $conn = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, DBUSERNAME, DBPASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "CONNECTED";
} catch (PDOException $e) {
    echo "DISCONNECTED" . $e->getMessage();
}
?>