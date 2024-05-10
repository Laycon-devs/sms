<?php
session_start();
include "../includes/db.php";
include "../includes/function.php";

adminAuth();
if (isset($_POST['logout'])) {
    logout();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $error = [];
    $error = addDepartment($_POST, $conn);
}
$department_row = fetchDepartment($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Department</title>
    <link rel="stylesheet" href="../styles/add_department.css">
    <link rel="stylesheet" href="../styles/admin_dashboard.css">
</head>

<body>
    <nav class="navbar">
        <a class="logo" href="#">EduSphere</a>
        <ul class="nav-links">
        <!-- <li><a href="admin_dashboard.php">Dashboard</a></li> -->
            <form action="" method="post">
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
        <?php
        if (isset($_GET['successmsg'])) {
            echo "<p style='color: white;
                    background-color: green;
                    border-radius: 15px;
                    padding: 8px;
                    text-align: center;'>" . $_GET['successmsg'] . "</p>";
        }
        ?>
        <h4><li style="list-style: none;"><a href="admin_dashboard.php">Back to Dashboard</a></li></h4>
        <h2>Add Department</h2>
        <form action="" method="POST">
            <label for="department_name">Department Name:</label>
            <?php
            if (isset($error['err_department_name'])) {
                echo "<p style='color: red;
                    font-weight: bold;
                    text-align: center;'>" . $error['err_department_name'] . "</p>";
            }
            ?>
            <input type="text" name="department_name" placeholder="Enter department name">

            <button type="submit">Add Department</button>
        </form>

        <h2>Existing Departments</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Department Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($department_row as $value) : ?>
                    <tr>
                        <td>DEPT<?= $value['department_id'] ?></td>
                        <td><?= $value['department_name'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>