<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
<?php
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(!empty($username) && !empty($email) && !empty($password)){
            $username = mysqli_real_escape_string($connection, $username);
            $email = mysqli_real_escape_string($connection, $email);
            $password = mysqli_real_escape_string($connection, $password);
            
            $query = "SELECT randSalt FROM users";
            $select_randSalt_query = mysqli_query($connection, $query);
            if(!$select_randSalt_query){
                die("QUERY FAILED" . mysqli_error($connection));
            }else{
                $row = mysqli_fetch_array($select_randSalt_query);
                $salt = $row['randSalt'];
                $password = crypt($password, $salt);
            }

            $query = "INSERT INTO users (username, user_email, user_password, user_role)";
            $query .= "VALUES('{$username}', '{$email}', '{$password}', 'subscriber')";
            $register_user_query = mysqli_query($connection, $query);
            if(!$register_user_query){
                die("QUERY FAILED" . mysqli_error($connection));
            }
                echo "<script>alert('Registered Sucessfully')</script>";
        }else{
            echo "<script>alert('Field cannot be empty')</script>";
        }
    }
?>
<?php include "includes/navigation.php" ?>
    <div class="container">
        <div class="row registration_form">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h1 class="page-header">Registration</h1>
                    <form action="registration.php" method="post" role="form" id="login-form" autocomplete="off" class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Username">
                        <input type="email" class="form-control" name="email" placeholder="Username@example.com">
                        <input type="password" class="form-control" name="password" placeholder="********">
                        <input type="submit" class="btn btn-success" name="submit">
                    </form>
               </div>
        </div>
    <hr>
<?php include "includes/footer.php" ?>