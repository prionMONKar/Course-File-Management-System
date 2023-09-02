<?php
include 'connection.php';

session_start();

if ($_SESSION['user_role'] == "Super Admin") {
    $teacher_id = $_REQUEST['tId'];

    // Delete the department from the department table
    $deleteDepartmentQuery = "DELETE FROM departments WHERE id=$teacher_id";
    if (mysqli_query($conn, $deleteDepartmentQuery)) {
        // Delete the department admin from the users table
        $deleteUserQuery = "DELETE FROM users WHERE id=$teacher_id";
        if (mysqli_query($conn, $deleteUserQuery)) {
            $currentFile = $_SERVER['PHP_SELF'];
            header("Location: $currentFile");
            exit();
        } else {
            echo "Error deleting department admin: " . mysqli_error($conn);
        }
    } else {
        echo "Error deleting department: " . mysqli_error($conn);
    }
} elseif ($_SESSION['user_role'] == "Dept_Admin") {
    $department_id = $_SESSION['department_id'];

    // Delete sessions, sections, and courses as a department admin
    $deleteSessionsQuery = "DELETE FROM sessions WHERE department_id=$department_id";
    $deleteSectionsQuery = "DELETE FROM sections WHERE department_id=$department_id";
    $deleteCoursesQuery = "DELETE FROM courses WHERE department_id=$department_id";

    if (mysqli_query($conn, $deleteSessionsQuery) && mysqli_query($conn, $deleteSectionsQuery) && mysqli_query($conn, $deleteCoursesQuery)) {
        // Logout the department admin by destroying the session
        session_destroy();
        $currentFile = $_SERVER['PHP_SELF'];
        header("Location: $currentFile");
        exit();
    } else {
        echo "Error deleting sessions, sections, or courses: " . mysqli_error($conn);
    }
}
?>
