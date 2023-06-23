<?php
require 'config/database.php';

//submit click
if(isset($_POST['submit'])){
    $author_id =$_SESSION['user-id']; //loged in already
    $title=filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body=filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id=filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured=filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail=$_FILES['thumbnail'];

    //set_featured to 0 if unchecked
    $is_featured = $is_featured ==1 ?:0;

    //validation
    if(!$title){
        $_SESSION['add-post']="Enter post title";
    } elseif(!$category_id){
        $_SESSION['add-post']="Select post category";
    } elseif(!$body){
        $_SESSION['add-post']="Post cannot be empty!";
    } elseif(!$thumbnail['name']){
        $_SESSION['add-post']="Choose post thumbnail";
    } else{
        //WORK ON THUMBNAIL
        //renaming image
        $time=time();
        $thumbnail_name=$time.$thumbnail['name'];
        $thumbnail_tmp_name=$thumbnail['tmp_name'];
        $thumbnail_path='../images/'.$thumbnail_name;

        //making sure file is image
        $allowed_files=['png','jpg','jpeg'];
        $extension=explode('.',$thumbnail_name);
        $extension =end($extension);
        if(in_array($extension,$allowed_files)){
            //image sizing 3MB
            if($thumbnail['size'] <=3000000){
                //upload img
                move_uploaded_file($thumbnail_tmp_name,$thumbnail_path);
            } else{
                $_SESSION['add-post']="File should be less than equal to 3mb";
            }
        } else{
            $_SESSION['add-post']="File should be png, jpg or jpeg!";
        }
    }

    //checking errors
    if(isset($_SESSION['add-post'])){
        $_SESSION['add-post-data']=$_POST;
        header('location: '.ROOT_URL.'admin/add-post.php');
        die();
    }else{
        //set if_featured of all other post to 0, if this is one
        if($is_featured==1){
            $zero_all_if_query="UPDATE posts SET is_featured=0";
            $zero_all_if_result=mysqli_query($connection,$zero_all_if_query);
        }

        //now inserting post to table
        $query="INSERT INTO posts (title,body,thumbnail,category_id,author_id,is_featured) VALUES ('$title','$body','$thumbnail_name','$category_id','$author_id','$is_featured')";
        $result=mysqli_query($connection,$query);

        if(!mysqli_errno($connection)){
            $_SESSION['add-post-success']="New Post added successfully!";
            header('location: '.ROOT_URL.'admin/');
            die();
        }
    }
}

header('location: '.ROOT_URL.'admin/add-post.php');
die();