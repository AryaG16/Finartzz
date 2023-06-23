<?php
require 'config/database.php';

//sub click data in
    if(isset($_POST['submit'])){
        //get updated form data
        $id=filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $username=filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $firstname=filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastname=filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $is_admin=filter_var($_POST['userrole'], FILTER_SANITIZE_NUMBER_INT);

        //validating inputs
        if(!$firstname || !$lastname){
            $_SESSION['edit-user']="Invalid Operation!";
        } else{
            //update user
            $query="UPDATE users SET firstname='$firstname', lastname='$lastname', admin=$is_admin WHERE id=$id LIMIT 1";
            $result=mysqli_query($connection,$query);

            if(mysqli_errno($connection)){
                $_SESSION['edit-user']="Failed to update user!";
            } else{
                $_SESSION['edit-user-success']="User $username updated successfully!";
            }
        }
    }
    header('location: '.ROOT_URL.'admin/manage-users.php');
    die();

?>