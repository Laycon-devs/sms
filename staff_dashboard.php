<?php
session_start();
include "./includes/db.php";
include "./includes/function.php";

staffAuth();
$currentStaff = fetchTeacherData($conn, $_SESSION['teacher_id']);
if (isset($_POST['logout'])) {
    staffLogout();
}
$department_name = fetchStaffDepartment($conn, $currentStaff['department_id']);
$course_name = fetchStaffCourse($conn, $currentStaff['department_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$currentStaff['lastname']?> || Dashboard</title>
    <link rel="stylesheet" href="./styles/staff.css">
    <link rel="stylesheet" href="./styles/admin_dashboard.css">
</head>

<body>
    <nav class="navbar">
        <a class="logo" href="./index.php">EduSphere</a>
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
        <h3 style="color: green;">Welcome back, Staff <?= $currentStaff['lastname'] ?> <?= $currentStaff['firstname'] ?></h3>
        <div class="bio">
            <img src="./images/admin.png" width="200px" alt="">
            <h2><?php if ($currentStaff['gender'] === "Male") {
                    echo "Mr";
                } else {
                    echo "Mrs";
                } ?> <?= $currentStaff['lastname'] ?> <?= $currentStaff['firstname'] ?></h2>
            <br>
            <p>Gender: <?= $currentStaff['gender'] ?></p>
            <p>Email: <?= $currentStaff['email'] ?></p>
            <p>Phone Number: <?= $currentStaff['phone_number'] ?></p>
            <p>Department: <?= $department_name['department_name'] ?></p>
        </div>

        <h3>Courses in <?= $department_name['department_name'] ?></h3>
        <ul class="courses">
            <?php foreach ($course_name as $value) : ?>
                <li><?= $value['course_name'] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>