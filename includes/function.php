<?php
function registerAdmin($dbconn, $post)
{
    $error = [];
    if (empty($post['admin_username'])) {
        $error['err_admin_username'] = "Please enter username";
    }
    if (empty($post['admin_email'])) {
        $error['err_admin_email'] = "Please enter email";
    }
    if (empty($post['admin_phone'])) {
        $error['err_admin_phone'] = "Please enter phone number";
    } elseif (!is_numeric($post['admin_phone'])) {
        $error['err_admin_phone'] = "Please enter a valid phone number";
    }
    if (empty($post['admin_hash'])) {
        $error['err_admin_hash'] = "Please enter Password";
    } elseif (strlen($post['admin_hash']) <= 5) {
        $error['err_admin_hash'] = "Password must be more than 6 charcaters";
    }
    if (!empty($error)) {
        return $error;
    }
    // =======
    if (empty($error)) {
        $em_error = checkEmail($dbconn, $post);
        if (!empty($em_error)) {
            return $em_error;
        }
        $TRIMMEDPOST = array_map("trim", $post);
        $encrypted_hash = password_hash($post['admin_hash'], PASSWORD_BCRYPT);
        $statement = $dbconn->prepare("INSERT INTO admin VALUES(NULL, :au, :ae, :ap, :ah, NOW(), NOW())");
        $data = [
            ":au" => $TRIMMEDPOST['admin_username'],
            ":ae" => $TRIMMEDPOST['admin_email'],
            ":ap" => $TRIMMEDPOST['admin_phone'],
            ":ah" => $encrypted_hash,
        ];
        $statement->execute($data);
        header("location:admin_login.php?successmsg=Registration successful, proceed to login");
        die();
    }
}

function checkEmail($dbconn, $post)
{
    $error = [];
    $TRIMMEDPOST = array_map("trim", $post);
    $check_email = $dbconn->prepare("SELECT * FROM admin WHERE email = :ae");
    $check_email->bindParam(":ae", $TRIMMEDPOST['admin_email']);
    $check_email->execute();
    if ($check_email->rowCount() > 0) {
        $error['err_admin_email'] = "Email already exists";
    }
    if (!empty($error)) {
        return $error;
    }
}

function loginAdmin($dbconn, $post)
{
    $error = [];
    if (empty($post['admin_email'])) {
        $error['err_admin_email'] = "Please enter email";
    }
    if (empty($post['admin_hash'])) {
        $error['err_admin_hash'] = "Please enter Password";
    }
    if (!empty($error)) {
        return $error;
    }
    $statement = $dbconn->prepare("SELECT * FROM admin WHERE email = :ae");
    $statement->bindparam(":ae", $post['admin_email']);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_BOTH);
    if ($statement->rowCount() > 0 && password_verify($post['admin_hash'], $row['password_hash'])) {
        $_SESSION['admin_id'] = $row['admin_id'];
        header("location: admin_dashboard.php");
        die();
    } else {
        header("location: admin_login.php?errmsg=Incorrect login credentials.");
        die();
    }
}

function fetchAdminData($dbconn, $session_id)
{
    $statement = $dbconn->prepare("SELECT * FROM admin WHERE admin_id = :ad");
    $statement->bindparam(":ad", $session_id);
    $statement->execute();
    $currentAdmin = $statement->fetch(PDO::FETCH_BOTH);
    return $currentAdmin;
}

function logout()
{
    session_start();
    $_SESSION['admin_id'];
    unset($_SESSION['admin_id']);
    session_destroy();
    header("location: admin_login.php");
}
function adminAuth()
{
    if (!isset($_SESSION['admin_id'])) {
        header("location: admin_login.php");
        die();
    }
}

// =======Department functions
function addDepartment($post, $dbconn)
{
    $TRIMMEDPOST = array_map("trim", $post);
    $error = [];
    if (empty($TRIMMEDPOST['department_name'])) {
        $error['err_department_name'] = "Enter a Department to add please";
    }
    if (!empty($error)) {
        return $error;
    }
    if (empty($error)) {
        $statement = $dbconn->prepare("INSERT INTO department VALUES(NULL, :dn, NOW(), NOW())");
        $statement->bindparam(":dn", $TRIMMEDPOST['department_name']);
        $statement->execute();
        header("location: add_department.php?successmsg=Department Added Sucessufully");
        die();
    }
}

function fetchDepartment($dbconn)
{
    $statement = $dbconn->prepare("SELECT * FROM department");
    $statement->execute();
    $department_row = [];
    while ($row = $statement->fetch(PDO::FETCH_BOTH)) {
        $department_row[] = $row;
    }
    return $department_row;
}

function editDepartment($dbconn, $post)
{
    if (!empty($post['department_name'])) {
        $statement = $dbconn->prepare("UPDATE department SET department_name=:dn WHERE department_id = :di");
        $statement->bindParam(":dn", $post['department_name']);
        $statement->bindParam(":di", $_GET['department_id']);
        $statement->execute();
        header("location: admin_dashboard.php?successmsg=Department updated successfully");
        die();
    }
}

function fetchDepartmentWithId($dbconn, $get)
{
    $statement = $dbconn->prepare("SELECT * FROM department WHERE department_id = :di");
    $statement->bindParam(":di", $get['department_id']);
    $statement->execute();
    $editing_department = $statement->fetch(PDO::FETCH_BOTH);
    return $editing_department;
}
// =======Course functions
function addCourse($post, $dbconn)
{
    $TRIMMEDPOST = array_map("trim", $post);
    $error = [];
    if (empty($TRIMMEDPOST['course_name']) || empty($TRIMMEDPOST['department'])) {
        $error['err_course_name'] = "Fill appropriately";
    }
    if (!empty($error)) {
        return $error;
    }
    if (empty($error)) {
        $statement = $dbconn->prepare("INSERT INTO course VALUES(NULL, :cn, :dept, NOW(), NOW())");
        $statement->bindparam(":cn", $TRIMMEDPOST['course_name']);
        $statement->bindparam(":dept", $TRIMMEDPOST['department']);
        $statement->execute();
        header("location: add_course.php?successmsg=Course added successufully");
        die();
    }
}

function fetchCourse($dbconn)
{
    $statement = $dbconn->prepare("SELECT * FROM course");
    $statement->execute();
    $course_row = [];
    while ($row = $statement->fetch(PDO::FETCH_BOTH)) {
        $course_row[] = $row;
    }
    return $course_row;
}

function editCourse($dbconn, $post)
{
    if (!empty($post['course_name'])) {
        $statement = $dbconn->prepare("UPDATE course SET course_name=:cn WHERE course_id = :ci");
        $statement->bindParam(":cn", $post['course_name']);
        $statement->bindParam(":ci", $_GET['course_id']);
        $statement->execute();
        header("location: admin_dashboard.php?successmsg=Course updated successfully");
        die();
    }
}

function fetchCourseWithId($dbconn, $get)
{
    $statement = $dbconn->prepare("SELECT * FROM course WHERE course_id = :ci");
    $statement->bindParam(":ci", $get['course_id']);
    $statement->execute();
    $editing_course = $statement->fetch(PDO::FETCH_BOTH);
    return $editing_course;
}

// ====Create Teachers functions======
function createTeacher($dbconn, $post){
    $error = [];
    if (empty($post['lastname'])) {
        $error['err_lastname'] = "Enter your lastname";
    }
    if (empty($post['firstname'])) {
        $error['err_firstname'] = "Enter your firstname";
    }
    if (empty($post['email'])) {
        $error['err_email'] = "Enter your email";
    }
    if (empty($post['phonenumber'])) {
        $error['err_phonenumber'] = "Enter your phonenumber";
    }elseif (!is_numeric($post['phonenumber'])) {
        $error['err_phonenumber'] = "Enter a valid phonenumber";
    }
    if (empty($post['gender'])) {
        $error['err_gender'] = "Enter your gender";
    }
    if (empty($post['hash'])) {
        $error['err_hash'] = "Enter your password";
    }elseif (strlen($post['hash'] <= 5)) {
        $error['err_hash'] = "Password must be more than 6 charcaters";
    }
    if (empty($post['confirm_hash'])) {
        $error['err_confirm_hash'] = "Enter your password";
    }elseif ($post['confirm_hash'] !== $post['hash']) {
        $error['err_confirm_hash'] = "Password must match";
    }
    if (empty($post['department'])) {
        $error['err_department'] = "You must assign a department to this teacher";
    }
    if (!empty($error)) {
        return $error;
    }
    if (empty($error)) {
        $teacher_email_error = checkTeacherEmail($dbconn, $post);
        if (!empty($teacher_email_error)) {
            return $teacher_email_error;
        }
    }
    $TRIMMEDPOST = array_map("trim", $post);
        $encrypted_hash = password_hash($post['hash'], PASSWORD_BCRYPT);
        $statement = $dbconn->prepare("INSERT INTO teacher VALUES(NULL, :tl, :tf, :te, :tp, :tg, :th, :td, NOW(), NOW())");
        $data = [
            ":tl" => $TRIMMEDPOST['lastname'],
            ":tf" => $TRIMMEDPOST['firstname'],
            ":te" => $TRIMMEDPOST['email'],
            ":tp" => $TRIMMEDPOST['phonenumber'],
            ":tg" => $TRIMMEDPOST['gender'],
            ":th" => $encrypted_hash,
            ":td" => $TRIMMEDPOST['department'],
        ];
        $statement->execute($data);
        header("location:admin_dashboard.php?successmsg=Staff registration successful, kindly proceed to login");
        die();
}
// ====check teacher email exist
function checkTeacherEmail($dbconn, $post)
{
    $error = [];
    $TRIMMEDPOST = array_map("trim", $post);
    $check_email = $dbconn->prepare("SELECT * FROM teacher WHERE email = :te");
    $check_email->bindParam(":te", $TRIMMEDPOST['email']);
    $check_email->execute();
    if ($check_email->rowCount() > 0) {
        $error['err_email'] = "You're trying to use another person email";
    }
    if (!empty($error)) {
        return $error;
    }
}

function fetchTeacher($dbconn)
{
    $statement = $dbconn->prepare("SELECT * FROM teacher");
    $statement->execute();
    $teacher_row = [];
    while ($row = $statement->fetch(PDO::FETCH_BOTH)) {
        $teacher_row[] = $row;
    }
    return $teacher_row;
}
function fetchTeacherDepartment($valuee, $dbconn){
    $statement = $dbconn->prepare("SELECT * FROM department WHERE department_id = :di");
    $statement->bindParam(":di", $valuee['department_id']);
    $statement->execute();
    $department_name = $statement->fetch(PDO::FETCH_BOTH);
    return $department_name;
}
function fetchTeacherWithId($dbconn, $get)
{
    $statement = $dbconn->prepare("SELECT * FROM teacher WHERE teacher_id = :ti");
    $statement->bindParam(":ti", $get['teacher_id']);
    $statement->execute();
    $editing_teacher = $statement->fetch(PDO::FETCH_BOTH);
    return $editing_teacher;
}
function editTeacher($dbconn, $post, $get){
    $error = [];
    if (empty($post['lastname'])) {
        $error['err_lastname'] = "Enter your lastname";
    }
    if (empty($post['firstname'])) {
        $error['err_firstname'] = "Enter your firstname";
    }
    if (empty($post['phonenumber'])) {
        $error['err_phonenumber'] = "Enter your phonenumber";
    }elseif (!is_numeric($post['phonenumber'])) {
        $error['err_phonenumber'] = "Enter a valid phonenumber";
    }
    if (empty($post['gender'])) {
        $error['err_gender'] = "Enter your gender";
    }
    if (empty($post['department'])) {
        $error['err_department'] = "Select department to change";
    }
    if (!empty($error)) {
        return $error;
    }
    if (empty($error)) {
        $TRIMMEDPOST = array_map("trim", $post);
        $statement = $dbconn->prepare("UPDATE teacher SET lastname=:ln, firstname=:fn, phone_number=:pn, gender=:gn, department_id=:di WHERE teacher_id = :tid");
        $data = [
            ":ln" => $TRIMMEDPOST['lastname'],
            ":fn" => $TRIMMEDPOST['firstname'],
            ":pn" => $TRIMMEDPOST['phonenumber'],
            ":gn" => $TRIMMEDPOST['gender'],
            ":di" => $TRIMMEDPOST['department'],
            ":tid" => $get['teacher_id'],
        ];
        $statement->execute($data);
        header("location: admin_dashboard.php?successmsg=Staff updated successfully");
        die();
    }
}
// ====Create Student functions======
function createStudent($dbconn, $post){
    $error = [];
    if (empty($post['lastname'])) {
        $error['err_lastname'] = "Enter your lastname";
    }
    if (empty($post['firstname'])) {
        $error['err_firstname'] = "Enter your firstname";
    }
    if (empty($post['email'])) {
        $error['err_email'] = "Enter your email";
    }
    if (empty($post['hash'])) {
        $error['err_hash'] = "Enter your password";
    }elseif (strlen($post['hash'] <= 5)) {
        $error['err_hash'] = "Password must be more than 6 charcaters";
    }
    if (empty($post['confirm_hash'])) {
        $error['err_confirm_hash'] = "Enter your password";
    }elseif ($post['confirm_hash'] !== $post['hash']) {
        $error['err_confirm_hash'] = "Password must match";
    }
    if (empty($post['department'])) {
        $error['err_department'] = "You must assign a department to this teacher";
    }
    if (!empty($error)) {
        return $error;
    }
    if (empty($error)) {
        $student_email_error = checkStudentEmail($dbconn, $post);
        if (!empty($student_email_error)) {
            return $student_email_error;
        }
    }
    $TRIMMEDPOST = array_map("trim", $post);
    $matric = rand(1000, 9000);
        $encrypted_hash = password_hash($post['hash'], PASSWORD_BCRYPT);
        $statement = $dbconn->prepare("INSERT INTO student VALUES($matric, :tl, :tf, :te, :th, :td, NOW(), NOW())");
        $data = [
            ":tl" => $TRIMMEDPOST['lastname'],
            ":tf" => $TRIMMEDPOST['firstname'],
            ":te" => $TRIMMEDPOST['email'],
            ":th" => $encrypted_hash,
            ":td" => $TRIMMEDPOST['department'],
        ];
        $statement->execute($data);
        header("location:admin_dashboard.php?successmsg=Student registration successful, kindly proceed to login");
        die();
}
// ====checking student email
function checkStudentEmail($dbconn, $post)
{
    $error = [];
    $TRIMMEDPOST = array_map("trim", $post);
    $check_email = $dbconn->prepare("SELECT * FROM student WHERE email = :se");
    $check_email->bindParam(":se", $TRIMMEDPOST['email']);
    $check_email->execute();
    if ($check_email->rowCount() > 0) {
        $error['err_email'] = "You're trying to use another student email";
    }
    if (!empty($error)) {
        return $error;
    }
}

function fetchStudent($dbconn)
{
    $statement = $dbconn->prepare("SELECT * FROM student");
    $statement->execute();
    $student_row = [];
    while ($row = $statement->fetch(PDO::FETCH_BOTH)) {
        $student_row[] = $row;
    }
    return $student_row;
}
function fetchStudentDepartment($valuee, $dbconn){
    $statement = $dbconn->prepare("SELECT * FROM department WHERE department_id = :di");
    $statement->bindParam(":di", $valuee['department_id']);
    $statement->execute();
    $department_name = $statement->fetch(PDO::FETCH_BOTH);
    return $department_name;
}
function fetchStudentWithId($dbconn, $get)
{
    $statement = $dbconn->prepare("SELECT * FROM student WHERE student_id = :ti");
    $statement->bindParam(":ti", $get['student_id']);
    $statement->execute();
    $editing_student = $statement->fetch(PDO::FETCH_BOTH);
    return $editing_student;
}
function editStudent($dbconn, $post, $get){
    $error = [];
    if (empty($post['lastname'])) {
        $error['err_lastname'] = "Enter your lastname";
    }
    if (empty($post['firstname'])) {
        $error['err_firstname'] = "Enter your firstname";
    }
    if (empty($post['department'])) {
        $error['err_department'] = "Select department to change";
    }
    if (!empty($error)) {
        return $error;
    }
    if (empty($error)) {
        $TRIMMEDPOST = array_map("trim", $post);
        $statement = $dbconn->prepare("UPDATE student SET lastname=:ln, firstname=:fn, department_id=:di WHERE student_id = :sid");
        $data = [
            ":ln" => $TRIMMEDPOST['lastname'],
            ":fn" => $TRIMMEDPOST['firstname'],
            ":di" => $TRIMMEDPOST['department'],
            ":sid" => $get['student_id'],
        ];
        $statement->execute($data);
        header("location: admin_dashboard.php?successmsg=Student updated successfully");
        die();
    }
}

// Staff login functions
function loginStaff($dbconn, $post)
{
    $error = [];
    if (empty($post['teacher_email'])) {
        $error['err_teacher_email'] = "Please enter email";
    }
    if (empty($post['teacher_hash'])) {
        $error['err_teacher_hash'] = "Please enter Password";
    }
    if (!empty($error)) {
        return $error;
    }
    $statement = $dbconn->prepare("SELECT * FROM teacher WHERE email = :te");
    $statement->bindparam(":te", $post['teacher_email']);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_BOTH);
    if ($statement->rowCount() > 0 && password_verify($post['teacher_hash'], $row['password_hash'])) {
        $_SESSION['teacher_id'] = $row['teacher_id'];
        header("location: staff_dashboard.php");
        die();
    } else {
        header("location: staff_login.php?errmsg=Incorrect login credentials.");
        die();
    }
}

function fetchTeacherData($dbconn, $session_id)
{
    $statement = $dbconn->prepare("SELECT * FROM teacher WHERE teacher_id = :ti");
    $statement->bindparam(":ti", $session_id);
    $statement->execute();
    $currentStaff = $statement->fetch(PDO::FETCH_BOTH);
    return $currentStaff;
}

function staffLogout()
{
    session_start();
    $_SESSION['teacher_id'];
    unset($_SESSION['teacher_id']);
    session_destroy();
    header("location: staff_login.php");
    die();
}
function staffAuth()
{
    if (!isset($_SESSION['teacher_id'])) {
        header("location: staff_login.php");
        die();
    }
}

function fetchStaffDepartment($dbconn, $department_id){
    $statement = $dbconn->prepare("SELECT department_name FROM department WHERE department_id = :did");
    $statement->bindparam(":did", $department_id);
    $statement->execute();
    $department_name = $statement->fetch(PDO::FETCH_BOTH);
    return $department_name;
}

function fetchStaffCourse($dbconn, $department_id){
    $statement = $dbconn->prepare("SELECT * FROM course WHERE department_id = :did");
    $statement->bindparam(":did", $department_id);
    $statement->execute();
    $course_name = [];
    while ($row = $statement->fetch(PDO::FETCH_BOTH)) {
        $course_name[] = $row;
    };
    return $course_name;
}

// Student login functions
function loginStudent($dbconn, $post)
{
    $error = [];
    if (empty($post['student_email'])) {
        $error['err_student_email'] = "Please enter email";
    }
    if (empty($post['student_hash'])) {
        $error['err_student_hash'] = "Please enter Password";
    }
    if (!empty($error)) {
        return $error;
    }
    $statement = $dbconn->prepare("SELECT * FROM student WHERE email = :se");
    $statement->bindparam(":se", $post['student_email']);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_BOTH);
    if ($statement->rowCount() > 0 && password_verify($post['student_hash'], $row['password_hash'])) {
        $_SESSION['student_id'] = $row['student_id'];
        header("location: student_dashboard.php");
        die();
    } else {
        header("location: student_login.php?errmsg=Incorrect login credentials.");
        die();
    }
}

function fetchStudentData($dbconn, $session_id)
{
    $statement = $dbconn->prepare("SELECT * FROM student WHERE student_id = :si");
    $statement->bindparam(":si", $session_id);
    $statement->execute();
    $currentStudent = $statement->fetch(PDO::FETCH_BOTH);
    return $currentStudent;
}

function studentLogout()
{
    session_start();
    $_SESSION['student_id'];
    unset($_SESSION['student_id']);
    session_destroy();
    header("location: student_login.php");
    die();
}
function studentAuth()
{
    if (!isset($_SESSION['student_id'])) {
        header("location: student_login.php");
        die();
    }
}

function fetchPersonalStudentDepartment($dbconn, $department_id){
    $statement = $dbconn->prepare("SELECT department_name FROM department WHERE department_id = :did");
    $statement->bindparam(":did", $department_id);
    $statement->execute();
    $department_name = $statement->fetch(PDO::FETCH_BOTH);
    return $department_name;
}

function fetchStudentCourse($dbconn, $department_id){
    $statement = $dbconn->prepare("SELECT * FROM course WHERE department_id = :did");
    $statement->bindparam(":did", $department_id);
    $statement->execute();
    $course_name = [];
    while ($row = $statement->fetch(PDO::FETCH_BOTH)) {
        $course_name[] = $row;
    };
    return $course_name;
}