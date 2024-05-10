<?php
session_start();
include "../includes/db.php";
include "../includes/function.php";

$error = [];
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $error = loginAdmin($conn, $_POST);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Login</title>
    <link rel="stylesheet" href="../styles/admin_register.css">
</head>

<body>
    <?php
    include "../navbar.php";
    ?>
    <div class="container">
        <div class="form-container">
            <?php
            if (isset($_GET['errmsg'])) {
                echo "<p style='color: black;
                    background-color: red;
                    border-radius: 15px;
                    padding: 8px;
                    text-align: center;'>" . $_GET['errmsg'] . "</p>";
            }
            ?>
            <h2>Admin Login</h2>
            <form action="" method="POST">
                <label for="login-email">Email:</label>
                <?php
                if (isset($error['err_admin_email'])) {
                    echo "<p style='color: red;'>" . $error['err_admin_email'] . "</p>";
                }
                ?>
                <input type="email" name="admin_email">

                <label for="login-password">Password:</label>
                <?php
                if (isset($error['err_admin_hash'])) {
                    echo "<p style='color: red;'>" . $error['err_admin_hash'] . "</p>";
                }
                ?>
                <input type="password" name="admin_hash">

                <button type="submit">Login</button>
                <p>Don't have an account <a href="admin_register.php">Register</a></p>
            </form>
        </div>
    </div>
</body>

</html>