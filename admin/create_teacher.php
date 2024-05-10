<?php
session_start();
include "../includes/db.php";
include "../includes/function.php";

adminAuth();
if (isset($_POST['logout'])) {
    logout();
}
$error = [];
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $teacher_email_error = checkTeacherEmail($conn, $_POST);
    if (!empty($teacher_email_error)) {
        $error = array_merge($error, $teacher_email_error);
    }
    $teacher_error = createTeacher($conn, $_POST);
    if (!empty($teacher_error)) {
        $error = array_merge($error, $teacher_error);
    }
}
$department_row = fetchDepartment($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Teacher</title>
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
        <h2>Create Teacher Account</h2>
        <form action="" method="POST">
            <label for="lastname">Last Name:</label>
            <?php if (isset($error['err_lastname'])) { ?>
                <p style='color: red;'><?php echo $error['err_lastname']; ?></p>
            <?php } ?>
            <input type="text" name="lastname" placeholder="Enter last name">

            <label for="firstname">First Name:</label>
            <?php if (isset($error['err_firstname'])) { ?>
                <p style='color: red;'><?php echo $error['err_firstname']; ?></p>
            <?php } ?>
            <input type="text" name="firstname" placeholder="Enter first name">

            <label for="email">Email:</label>
            <?php if (isset($error['err_email'])) { ?>
                <p style='color: red;'><?php echo $error['err_email']; ?></p>
            <?php } ?>
            <input type="email" name="email" placeholder="Enter email">

            <label for="phonenumber">Phone Number:</label>
            <?php if (isset($error['err_phonenumber'])) { ?>
                <p style='color: red;'><?php echo $error['err_phonenumber']; ?></p>
            <?php } ?>
            <input type="text" name="phonenumber" placeholder="Enter phone number">

            <label for="phonenumber">Gender:</label>
            <?php if (isset($error['err_gender'])) { ?>
                <p style='color: red;'><?php echo $error['err_gender']; ?></p>
            <?php } ?>
            Male <input value="Male" type="radio" name="gender">
            Female <input value="Female" type="radio" name="gender">

            <label for="password">Password:</label>
            <?php if (isset($error['err_hash'])) { ?>
                <p style='color: red;'><?php echo $error['err_hash']; ?></p>
            <?php } ?>
            <input type="password" name="hash" placeholder="Enter password">

            <label for="confirmpassword">Confirm Password:</label>
            <?php if (isset($error['err_confirm_hash'])) { ?>
                <p style='color: red;'><?php echo $error['err_confirm_hash']; ?></p>
            <?php } ?>
            <input type="password" name="confirm_hash" placeholder="Confirm password">

            <label for="department_id">Department:</label>
            <?php if (isset($error['err_department'])) { ?>
                <p style='color: red;'><?php echo $error['err_department']; ?></p>
            <?php } ?>
            <select style="text-align: center;" name="department">
                <option disabled selected>Select a department</option>
                <?php foreach ($department_row as $value) : ?>
                    <option value="<?= $value['department_id'] ?>"><?= $value['department_name'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Create Account</button>
        </form>
    </div>
</body>

</html>