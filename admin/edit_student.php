<?php
session_start();
include "../includes/db.php";
include "../includes/function.php";

adminAuth();
if (isset($_POST['logout'])) {
    logout();
}
if (isset($_GET['student_id'])) {
    $editing_student = fetchStudentWithId($conn, $_GET);
}
$error = [];
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $error = editStudent($conn, $_POST, $_GET);
}
$department_row = fetchDepartment($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="../styles/create_teacher.css">
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
        <h2>Update Student Account</h2>
        <form action="" method="POST">
            <label for="lastname">Last Name:</label>
            <?php if (isset($error['err_lastname'])) { ?>
                <p style='color: red;'><?php echo $error['err_lastname']; ?></p>
            <?php } ?>
            <input value="<?= $editing_student['lastname']?>" type="text" name="lastname" placeholder="Enter last name">

            <label for="firstname">First Name:</label>
            <?php if (isset($error['err_firstname'])) { ?>
                <p style='color: red;'><?php echo $error['err_firstname']; ?></p>
            <?php } ?>
            <input value="<?= $editing_student['firstname']?>" type="text" name="firstname" placeholder="Enter first name">

            <label for="department_id">Department:</label>
            <?php if (isset($error['err_department'])) { ?>
                <p style='color: red;'><?php echo $error['err_department']; ?></p>
            <?php } ?>
            <select style="text-align: center;" name="department">
                <option disabled selected>Update department</option>
                <?php foreach ($department_row as $value) : ?>
                    <option value="<?= $value['department_id'] ?>"><?= $value['department_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Update Account</button>
        </form>
    </div>
</body>
</html>