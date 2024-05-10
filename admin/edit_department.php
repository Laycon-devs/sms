<?php
session_start();
include "../includes/db.php";
include "../includes/function.php";
adminAuth();

if (isset($_POST['logout'])) {
    logout();
}
if (isset($_GET['department_id'])) {
    $editing_department = fetchDepartmentWithId($conn, $_GET);
}
if (isset($_POST['edit'])) {
    editDepartment($conn, $_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Department</title>
    <link rel="stylesheet" href="../styles/edit_department.css">
    <link rel="stylesheet" href="../styles/admin_dashboard.css">
</head>
<body>
    <nav class="navbar">
        <a class="logo" href="#">EduSphere</a>
        <ul class="nav-links">
            <!-- <li><a href="admin_dashboard.php">Dashboard</a></li> -->
            <form action="" method="POST">
                <button style="padding: 10px 20px;
    background: red;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;" name="logout">Logout</button>
            </form>
        </ul>
    </nav>
    <div class="container">
        <h2>Edit Department</h2>
        <form action="" method="POST">
            <label for="department_name">Department Name:</label>
            <input type="text" name="department_name" value="<?= $editing_department['department_name'] ?>">
            <button type="submit" name="edit">Update Department</button>
        </form>
    </div>
</body>
</html>