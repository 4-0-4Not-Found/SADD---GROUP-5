<?php
include_once("include/connect.php");
session_start();

if(isset($_POST['uname']) && isset($_POST['pword'])){
    $user_name = $_POST['uname'];
    $pass_word = $_POST['pword'];
   
    $sql_get_user_data = "SELECT * FROM `users` 
                           WHERE `username`='$user_name'
                             AND `password`='$pass_word'";
    $user_result = mysqli_query($conn, $sql_get_user_data);
    
    if (mysqli_num_rows($user_result) > 0) {
        while($row = mysqli_fetch_assoc($user_result)) {
            $_SESSION["username"] = $user_name;
            $_SESSION['user_id'] = $row['user_id']; 
            if($row['user_type'] == 'admin') 
            {
                header("location: admin/");
                die();
            }
            else if ($row['user_type'] == 'user') 
            {
                header("location: homepage.php");
                die();
            }
            else {
                header("location: index.php");
                die();
            }
        }
    } else {
        header("location: index.php?error=404");
        die();
    }
}
else{
    header("location: index.php?msg=notallowed");
    die();
}
?>
