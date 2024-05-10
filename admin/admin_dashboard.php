<?php
session_start();
include "../includes/db.php";
include "../includes/function.php";

adminAuth();
$currentAdmin = fetchAdminData($conn, $_SESSION['admin_id']);

if (isset($_POST['logout'])) {
    logout();
}

$department_row = fetchDepartment($conn);
$course_row = fetchCourse($conn);
$teacher_row = fetchTeacher($conn);
$student_row = fetchStudent($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
<?php
    if (isset($_GET['successmsg'])) {
        echo "<p style='color: green;
                    font-weight: bold;
                    text-align: center;'>" . $_GET['successmsg'] . "</p>";
    }
    ?>
    <div class="dashboard-container">
        <div class="profile-section">
            <img src="../images/admin.png" width="200" alt="Admin Profile Picture" class="profile-pic">
            <h2>Admin Dashboard</h2>
            <p>ID: <strong><?= $currentAdmin['username'] ?><?= $_SESSION['admin_id'] ?></strong></p>
            <p>Welcome back, <strong><?= $currentAdmin['username'] ?></strong>!</p>
            <p>Email: <strong><?= $currentAdmin['email'] ?></strong></p>
            <p>Phone Number: <strong><?= $currentAdmin['phone_number'] ?></strong></p>
        </div>

        <section id="teachers" class="teachers-section">
            <h2>Lecturer</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teacher_row as $value): $department_name = fetchTeacherDepartment($value, $conn); ?>
                        <tr>
                                <td>Lecturer <?=$value['teacher_id']?></td>
                                <td><?=$value['lastname']?> <?=$value['firstname']?></td>
                                <td><?=$value['gender']?></td>
                                <td><?= $department_name['department_name'] ?></td>
                                <td><?=$value['email']?></td>
                                <td><?=$value['phone_number']?></td>
                                <td><a href="edit_teacher.php?teacher_id=<?= $value['teacher_id'] ?>" style="padding: 10px 20px;
    background: green;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;" name="edit">Edit</a></td>
                            </tr>
                            <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="students" class="students-section">
            <h2>Students</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Matric No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($student_row as $value): $department_name = fetchStudentDepartment($value, $conn); ?>
                        <tr>
                                <td><?=$value['student_id']?></td>
                                <td><?=$value['lastname']?> <?=$value['firstname']?></td>
                                <td><?=$value['email']?></td>
                                <td><?= $department_name['department_name'] ?></td>
                                <td><a href="edit_student.php?student_id=<?= $value['student_id'] ?>" style="padding: 10px 20px;
    background: green;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;" name="edit">Edit</a></td>
                            </tr>
                            <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </section>

        <h2>Departments</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Department Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($department_row as $value) : ?>
                    <tr>
                        <td>DEPT<?= $value['department_id'] ?></td>
                        <td><?= $value['department_name'] ?></td>
                        <td>
                                <a href="edit_department.php?department_id=<?= $value['department_id'] ?>" style="padding: 10px 20px;
    background: green;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;" name="edit">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h2>Available Courses</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($course_row as $value) : ?>
                    <tr>
                        <td>Course <?= $value['course_id'] ?></td>
                        <td><?= $value['course_name'] ?></td>
                        <td>
                                <a href="edit_course.php?course_id=<?= $value['course_id'] ?>" style="padding: 10px 20px;
    background: green;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;" name="edit">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <ul>
                <li><a href="create_teacher.php">Create Lecturer</a></li>
                <li><a href="create_student.php">Create Student</a></li>
                <li><a href="add_department.php">Add Department</a></li>
                <li><a href="add_course.php">Add Course</a></li>
            </ul>
        </div>
    </div>
</body>

</html>