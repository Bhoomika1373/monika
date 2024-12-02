<?php include "includes/admin_header.php"; ?>
<?php
    if(isset($_SESSION['username'])){
        $username =  $_SESSION['username'];
        $query = "SELECT * FROM users WHERE username = '{$username}' ";
        $select_user_profile = mysqli_query($connection, $query);

        while($row = mysqli_fetch_array($select_user_profile)){
            $user_id = $row['user_id'];
            $username = $row['username'];
            $user_password = $row['user_password'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_role = $row['user_role'];
        }
    }
    if(isset($_POST['update_profile'])){
        //$user_id = $_POST['user_id'];
        $user_firstname = $_POST['user_firstname'];
        $user_lastname = $_POST['user_lastname'];
        $user_role = $_POST['user_role'];
        $username = $_POST['username'];
        $user_email = $_POST['user_email'];
        $user_password = $_POST['user_password'];

        // $post_image = $_FILES['post_image']['name'];
        // $post_image_temp = $_FILES['post_image']['tmp_name'];
        // move_uploaded_file($post_image_temp, "../images/$post_image");

        $query = "UPDATE users SET ";
        $query .= "user_firstname = '{$user_firstname}', ";
        $query .= "user_lastname = '{$user_lastname}', ";
        $query .= "user_role = '{$user_role}', ";
        $query .= "username = '{$username}', ";
        $query .= "user_email = '{$user_email}', ";
        $query .= "user_password = '{$user_password}' ";
        $query .= "WHERE username = '{$username}' ";

        $update_user_query = mysqli_query($connection,$query);
        confirm($update_user_query);
    }
?>
<div id="wrapper">
    <?php include "includes/admin_navigation.php"; ?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Users</h1>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="user_firstname">First Name</label>
                            <input type="text" value="<?php echo $user_firstname ; ?>" name="user_firstname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="user_lastname">Last Name</label>
                            <input type="text" value="<?php echo $user_lastname ; ?>" name="user_lastname" class="form-control">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="user_role" id="">
                                <option value="subscriber"><?php echo $user_role; ?></option>
                                <?php
                                    if($user_role == 'admin'){
                                        echo "<option value='subscriber'>subscriber</option>";
                                    }else{
                                        echo "<option value='admin'>admin</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" value="<?php echo $username ; ?>" name="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="user_email">Email</label>
                            <input type="email" value="<?php echo $user_email ; ?>" name="user_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="user_password">Password</label>
                            <input type="text" value="<?php echo $user_password ; ?>" name="user_password" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="update_profile" value="Update Profile" class="btn btn-success">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "includes/admin_footer.php"; ?>

