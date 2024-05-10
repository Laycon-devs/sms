<?php
session_start();
include "./includes/db.php";
include "./includes/function.php";

studentAuth();
$currentStudent = fetchStudentData($conn, $_SESSION['student_id']);
if (isset($_POST['logout'])) {
    studentLogout();
}
$department_name = fetchPersonalStudentDepartment($conn, $currentStudent['department_id']);
$course_name = fetchStudentCourse($conn, $currentStudent['department_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$currentStudent['lastname']?> || Dashboard</title>
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
        <h3 style="color: green;">Welcome back, Student <?= $currentStudent['lastname'] ?> <?= $currentStudent['firstname'] ?></h3>
        <div class="bio">
            <img src="./images/admin.png" width="200px" alt="">
            <h2><?= $currentStudent['lastname'] ?> <?= $currentStudent['firstname'] ?></h2>
            <br>
            <p>Matric No: <?= $currentStudent['student_id'] ?></p>
            <p>Email: <?= $currentStudent['email'] ?></p>
            <p>Department: <?= $department_name['department_name'] ?></p>
        </div>

        <h3>My Registered Courses</h3>
        <ul class="courses">
            <?php foreach ($course_name as $value) : ?>
                <li><?= $value['course_name'] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>