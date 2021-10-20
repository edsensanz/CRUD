<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image CRUD in PHP |  Upload Image in PHP MySql </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-5">

                <?php include('includes/message.php'); ?>

                <div class="card">
                    <div class="card-header">
                        <h4>
                            Student - Image CRUD in PHP
                            <a href="student-add.php" class="btn btn-primary float-end">Add Student</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Profile</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        require_once 'includes/dbconfig.php';

                                        $students = "SELECT * FROM student";
                                        $result = mysqli_query($con, $students);
                                        if(mysqli_num_rows($result) > 0)
                                        {
                                            foreach($result as $data) 
                                            {
                                                ?>
                                                 <tr>
                                                    <td><?= $data['id']; ?></td>
                                                    <td><?= $data['name']; ?></td>
                                                    <td><?= $data['course']; ?></td>
                                                    <td><img src="uploads/<?=$data['image'];?>" style="width: 70px; height: 70px" alt="Image"></td>
                                                    <td class="w-25">
                                                        <a href="student-edit.php?id=<?=$data['id']?>" class="btn btn-success">Edit</a>
                                                        <button type="button" class="btn btn-danger delete_btn" value="<?=$data['id'];?>">DELETE</bu>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <tr>
                                                <td colspan="6">No Record Found</td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.delete_btn', function () {
                var student_id = $(this).val();

                if (confirm("Are you sure! you want to delete this data ?")) {
                    $.ajax({
                        type: "POST",
                        url: "function.php",
                        data: {
                            student_id: student_id,
                            deleteStudent: true,
                        },
                        success: function (response) {
                            window.location.reload();                            
                        }
                    });
                }
            });
        });
    </script>

</body>
</html>

