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
    $error = addCourse($_POST, $conn);
}
$course_row = fetchCourse($conn);
$department_row = fetchDepartment($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link rel="stylesheet" href="../styles/add_department.css">
    <link rel="stylesheet" href="../styles/admin_dashboard.css">
    <link rel="stylesheet" href="../styles/dropdown.css">
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
        <h4>
            <li style="list-style: none;"><a href="admin_dashboard.php">Back to Dashboard</a></li>
        </h4>
        <h2>Add Course</h2>
        <form action="" method="POST">
            <label for="course_name">Course Name:</label>
            <?php
            if (isset($error['err_course_name'])) {
                echo "<p style='color: red;
                    font-weight: bold;
                    text-align: center;'>" . $error['err_course_name'] . "</p>";
            }
            ?>
            <input type="text" name="course_name" placeholder="Enter course name">
            <!-- <h2>Select Department</h2> -->
            <label for="department">Choose a Department:</label>
            <select style="text-align: center;" name="department">
                <option disabled selected>SELECT</option>
                <?php foreach ($department_row as $value) : ?>
                    <option value="<?= $value['department_id'] ?>"><?= $value['department_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Add Course</button>
        </form>
        <h2>Existing Course</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($course_row as $value) : ?>
                    <tr>
                        <td>Course <?= $value['course_id'] ?></td>
                        <td><?= $value['course_name'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>