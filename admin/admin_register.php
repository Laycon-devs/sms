<?php
include "../includes/db.php";
include "../includes/function.php";

$error = [];
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $em_error = checkEmail($conn, $_POST);
    if (!empty($em_error)) {
        $error = array_merge($error, $em_error);
    }
    $ad_error = registerAdmin($conn, $_POST);
    if (!empty($ad_error)) {
        $error = array_merge($error, $ad_error);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../styles/admin_register.css">
</head>

<body>
    <?php
    include "../navbar.php";
    ?>
    <div class="container">
        <div class="form-container">
            <h2>Admin Registration</h2>
            <form action="" method="POST">
                <label for="username">Username:</label>
                <?php
                if (isset($error['err_admin_username'])) {
                    echo "<p style='color: red;'>" . $error['err_admin_username'] . "</p>";
                }
                ?>
                <input type="text" id="username" name="admin_username">

                <label for="email">Email:</label>
                <?php
                if (isset($error['err_admin_email'])) {
                    echo "<p style='color: red;'>" . $error['err_admin_email'] . "</p>";
                }
                ?>
                <input type="email" name="admin_email">

                <label for="phone">Phone:</label>
                <?php
                if (isset($error['err_admin_phone'])) {
                    echo "<p style='color: red;'>" . $error['err_admin_phone'] . "</p>";
                }
                ?>
                <input type="number" name="admin_phone">

                <label for="password">Password:</label>
                <?php
                if (isset($error['err_admin_hash'])) {
                    echo "<p style='color: red;'>" . $error['err_admin_hash'] . "</p>";
                }
                ?>
                <input type="password" name="admin_hash">

                <button type="submit" name="submit">Register</button>
                <p>Already have an account <a href="admin_login.php">Login</a></p>
            </form>
        </div>
    </div>
</body>

</html>