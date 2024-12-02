<?php
    if(isset($_POST['create_user'])){
        //$user_id = $_POST['user_id'];
        $user_firstname = $_POST['user_firstname'];
        $user_lastname = $_POST['user_lastname'];
        $user_role = $_POST['user_role'];
        $username = $_POST['username'];
        $user_email = $_POST['user_email'];
        $user_phone = $_POST['user_phone'];
        $user_address = $_POST['user_address'];
        $user_password = $_POST['user_password'];

        // $post_image = $_FILES['post_image']['name'];
        // $post_image_temp = $_FILES['post_image']['tmp_name'];
        // move_uploaded_file($post_image_temp, "../images/$post_image");

        $query = "SELECT randSalt FROM users";
        $select_randSalt_query = mysqli_query($connection,$query);
        confirm($select_randSalt_query);
        
        $row = mysqli_fetch_array($select_randSalt_query);
        $salt = $row['randSalt'];
        $hashed_password = crypt($user_password, $salt);

        $query = "INSERT INTO users(user_firstname,user_lastname,user_role,username,user_email,user_phone,user_address,user_password)";
        $query .= "VALUE('{$user_firstname}','{$user_lastname}','{$user_role}','{$username}','{$user_email}','{$user_phone}','{$user_address}',
                    '{$hashed_password}')";
        $add_user_query = mysqli_query($connection,$query);

        confirm($add_user_query);

        echo "User Created: " . " " . "<a href='users.php'>View users</a>";
    }
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="user_firstname">First Name</label>
        <input type="text" name="user_firstname" class="form-control">
    </div>
    <div class="form-group">
        <label for="user_lastname">Last Name</label>
        <input type="text" name="user_lastname" class="form-control">
    </div>
    <div class="form-group">
        <select class="form-control" name="user_role" id="">
            <option value="subscriber">Select Option</option>
            <option value="admin">Admin</option>
            <option value="subscriber">Subscriber</option>
        </select>
    </div>
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" class="form-control">
    </div>
    <div class="form-group">
        <label for="user_email">Email</label>
        <input type="email" name="user_email" class="form-control">
    </div>
    <div class="form-group">
        <label for="user_phone">Phone</label>
        <input type="text" name="user_phone" class="form-control">
    </div>
    <div class="form-group">
        <label for="user_address">Address</label>
        <input type="text" name="user_address" class="form-control">
    </div>
    <div class="form-group">
        <label for="user_password">Password</label>
        <input type="password" name="user_password" class="form-control">
    </div>
    <div class="form-group">
        <input type="submit" name="create_user" value="Add User" class="btn btn-success">
    </div>
</form>