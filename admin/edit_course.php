<?php
session_start();
include "../includes/db.php";
include "../includes/function.php";
adminAuth();

if (isset($_POST['logout'])) {
    logout();
}
if (isset($_GET['course_id'])) {
    $editing_course = fetchCourseWithId($conn, $_GET);
}
if (isset($_POST['edit'])) {
    editCourse($conn, $_POST);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Courses</title>
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
        <h2>Edit Courses</h2>
        <form action="" method="POST">
            <label for="course_name">Course Name:</label>
            <input type="text" name="course_name" value="<?= $editing_course['course_name'] ?>">
            <button type="submit" name="edit">Update Course</button>
        </form>
    </div>
</body>

</html>