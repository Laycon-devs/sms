<?php
session_start();
include "./includes/db.php";
include "./includes/function.php";

$error = [];
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $error = loginStaff($conn, $_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - Login</title>
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/admin_register.css">
</head>
<body>
        <!-- Navigation bar -->
        <nav class="navbar">
       <a class="logo" href="./index.php">EduSphere</a>
        <ul class="nav-links">
            <!-- <li><a href="admin_register.php">Admin</a></li> -->
            <li><a href="staff_login.php">Staff</a></li>
            <li><a href="student_login.php">Student</a></li>
        </ul>
    </nav>
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
            <h2>Staff Login</h2>
            <form action="" method="POST">
                <label for="login-email">Email:</label>
                <?php
                if (isset($error['err_teacher_email'])) {
                    echo "<p style='color: red;'>" . $error['err_teacher_email'] . "</p>";
                }
                ?>
                <input type="email" name="teacher_email">

                <label for="login-password">Password:</label>
                <?php
                if (isset($error['err_teacher_hash'])) {
                    echo "<p style='color: red;'>" . $error['err_teacher_hash'] . "</p>";
                }
                ?>
                <input type="password" name="teacher_hash">

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>