<?php
require 'config/database.php';

//edit button clicked
if (isset($_POST['submit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $previous_thumbnail_name = filter_var($_POST['previous_thumbnail_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    //set is featured 0 if uncheck
    $is_featured = $is_featured == 1 ?: 0;

    //check & validation of input
    if (!$title || !$category_id || !$body) {
        $_SESSION['edit-post'] = "Couldn't update post. Invalid Operation.";
    } else {
        //delete available thumbnail(old), if new provided
        if ($thumbnail['name']) {
            $previous_thumbnail_path = '../images/' . $previous_thumbnail_name;
            if ($previous_thumbnail_path) {
                unlink($previous_thumbnail_path);
            }

            //work on new thumbnail
            //renaming image
            $time = time();
            $thumbnail_name = $time . $thumbnail['name'];
            $thumbnail_tmp_name = $thumbnail['tmp_name'];
            $thumbnail_path = '../images/' . $thumbnail_name;

            //making sure file is image
            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = explode('.', $thumbnail_name);
            $extension = end($extension);
            if (in_array($extension, $allowed_files)) {
                //image sizing 3MB
                if ($thumbnail['size'] <= 3000000) {
                    //upload img
                    move_uploaded_file($thumbnail_tmp_name, $thumbnail_path);
                } else {
                    $_SESSION['edit-post'] = "File should be less than equal to 3mb";
                }
            } else {
                $_SESSION['edit-post'] = "File should be png, jpg or jpeg!";
            }
        }
    }

    //checking errors
    if(isset($_SESSION['edit-post'])){
        //redirect
        header('location: '.ROOT_URL.'admin/');
        die();
    }else{
        //set if_featured of all other post to 0, if this is one
        if($is_featured==1){
            $zero_all_if_query="UPDATE posts SET is_featured=0";
            $zero_all_if_result=mysqli_query($connection,$zero_all_if_query);
        }

        //set thumbnail ti db
        $thumbnail_to_insert = $thumbnail_name ?? $previous_thumbnail_name;

        $query="UPDATE posts SET title='$title', body='$body',thumbnail='$thumbnail_to_insert',category_id=$category_id,is_featured=$is_featured WHERE id=$id LiMIT 1";
        $result=mysqli_query($connection,$query);
    }

    if(!mysqli_errno($connection)){
        $_SESSION['edit-post-success']="Post updated successfully";
    }


}
header('location: '.ROOT_URL.'admin/');
die();
