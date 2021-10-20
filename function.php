<?php
session_start();
require_once 'includes/dbconfig.php';

if(isset($_POST['updateStudent']))
{
    // Student Id
    $stud_id = $_POST['student_id'];

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $course = mysqli_real_escape_string($con, $_POST['course']);
    $image = $_FILES['image']['name'];

    // Image Temporary Directory
    $tmp_dir = $_FILES['image']['tmp_name'];

    // Input field is empty or not
    if(empty($name) || empty($course))
    {
        $_SESSION['status'] = "All Fields are mandetory!";
        header('Location: student-edit.php?id='.$stud_id);
        exit(0);
    }
    else
    {
        $studentChecking = "SELECT id,image FROM student WHERE id='$stud_id'";
        $student_result = mysqli_query($con, $studentChecking);

        if(mysqli_num_rows($student_result) > 0)
        {
            $studentData = mysqli_fetch_array($student_result);

            if($image != NULL)  //Creating New File
            {
                // Allowed Extensions
                $allowed_extension = array('png','jpg','jpeg');
                // Getting Extension from Image
                $image_extension = pathinfo($image, PATHINFO_EXTENSION);

                // Creating new Image_Name / filename
                $filename = time().'.'.$image_extension;

                // Checking Image is Valid or not
                if(!in_array($image_extension, $allowed_extension))
                {
                    $_SESSION['status'] = "You are allowed with only jpg, png, jpeg Image";
                    header('Location: student-edit.php?id='.$stud_id);
                    exit(0);
                }
                
                // Giving New Filename
                $update_filename = $filename;
            }
            else
            {
                //Updating with Old Image data.
                $update_filename = $studentData['image'];
            }

            $queryUpdateStudent = "UPDATE student SET name='$name', course='$course', image='$update_filename' WHERE id='$stud_id' ";
            $queryUpdateStudent_result = mysqli_query($con, $queryUpdateStudent);

            if($queryUpdateStudent_result)
            {
                if($image != NULL)
                {
                    // Uploading New Image
                    move_uploaded_file($tmp_dir, 'uploads/'.$filename);

                    //IF File or Image Exists in Folder 
                    if(file_exists('uploads/'.$studentData['image'])){
                        // Removing/Deleting the Old Image
                        unlink("uploads/".$studentData['image']);
                    }
                }

                $_SESSION['status'] = "Student Updated Successfully";
                header('Location: student-edit.php?id='.$stud_id);
                exit(0);
            }
            else
            {
                $_SESSION['status'] = "Student Not Updated";
                header('Location: student-edit.php?id='.$stud_id);
                exit(0);
            }
        }
        else
        {
            $_SESSION['status'] = "No Such ID / Student Found";
            header('Location: student-edit.php?id='.$stud_id);
            exit(0);
        }
    }
}

if(isset($_POST['saveStudent']))
{
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $course = mysqli_real_escape_string($con, $_POST['course']);
    $image = $_FILES['image']['name'];

    // Image Temporary Directory
    $tmp_dir = $_FILES['image']['tmp_name'];

    // Input field is empty or not
    if(empty($name) || empty($course) || empty($image))
    {
        $_SESSION['status'] = "All Fields are mandetory!";
        header('Location: student-add.php');
        exit(0);
    }
    else
    {
        // Allowed Extensions
        $allowed_extension = array('png','jpg','jpeg');
        // Getting Extension from Image
        $image_extension = pathinfo($image, PATHINFO_EXTENSION);

        // Creating new Image_Name / filename
        $filename = time().'.'.$image_extension;

        // Checking Image is Valid or not
        if(!in_array($image_extension, $allowed_extension))
        {
            $_SESSION['status'] = "You are allowed with only jpg, png, jpeg Image";
            header('Location: student-add.php');
            exit(0);
        }
        else
        {
            // Insert Query
            $query = "INSERT INTO student (name,course,image) VALUES ('$name','$course','$filename')";
            $query_run = mysqli_query($con, $query);
            if($query_run)
            {
                // Upload Image to uploads folder - After Inserting data
                move_uploaded_file($tmp_dir, 'uploads/'.$filename);

                $_SESSION['status'] = "Student Added Successfully";
                header('Location: student-add.php');
                exit(0);
            }
            else
            {
                $_SESSION['status'] = "Something went wrong";
                header('Location: student-add.php');
                exit(0);
            }
        }
    }

}

if(isset($_POST['deleteStudent']))
{
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);

    $deleteChecking = "SELECT * FROM student WHERE id='$student_id'";
    $CheckingDeleteResult = mysqli_query($con, $deleteChecking);

    // If record exists more than 0
    if(mysqli_num_rows($CheckingDeleteResult) > 0)
    {
        $deleteStudentCheckData = mysqli_fetch_array($CheckingDeleteResult);

        // Delete Query to Delete data
        $deleteStudentquery = "DELETE FROM student WHERE id='$student_id' ";
        $deleteStudentquery_run = mysqli_query($con, $deleteStudentquery);

        if($deleteStudentquery_run)
        {
            // Checking File exsists and removing the file/image
            if(file_exists('uploads/'.$deleteStudentCheckData['image'])){
                unlink("uploads/".$deleteStudentCheckData['image']);
            }

            $_SESSION['status'] = "Student Deleted Successfully";
            exit(0);
        }
        else
        {
            $_SESSION['status'] = "Something Went Wrong";
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "No Such ID / Student Found";
        exit(0);
    }
}

?>

