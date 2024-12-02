<?php
    if(isset($_GET['edit_user'])){
        $the_user_id =  $_GET['edit_user'];
        $query = "SELECT * FROM users WHERE user_id = $the_user_id";
        $select_users = mysqli_query($connection,$query);
        confirm($select_users);

        while($row = mysqli_fetch_assoc($select_users)){
            $user_id = $row['user_id'];
            $username = $row['username'];
            $user_password = $row['user_password'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_phone = $row['user_phone'];
            $user_address = $row['user_address'];
            $user_role = $row['user_role'];
        }
    }

    if(isset($_POST['edit_user'])){
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

        $query = "UPDATE users SET ";
        $query .= "user_firstname = '{$user_firstname}', ";
        $query .= "user_lastname = '{$user_lastname}', ";
        $query .= "user_role = '{$user_role}', ";
        $query .= "username = '{$username}', ";
        $query .= "user_email = '{$user_email}', ";
        $query .= "user_phone = '{$user_phone}', ";
        $query .= "user_address = '{$user_address}', ";
        $query .= "user_password = '{$hashed_password}' ";
        $query .= "WHERE user_id = $the_user_id ";

        $update_user_query = mysqli_query($connection,$query);
        confirm($update_user_query);
    }
?>
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
            <option value="<?php echo $user_role; ?>"><?php echo $user_role; ?></option>
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
        <label for="user_phone">Phone</label>
        <input type="text" value="<?php echo $user_phone ; ?>" name="user_phone" class="form-control">
    </div>
    <div class="form-group">
        <label for="user_address">Address</label>
        <input type="text" value="<?php echo $user_address ; ?>" name="user_address" class="form-control">
    </div>
    <div class="form-group">
        <label for="user_password">Password</label>
        <input type="text" value="<?php echo $user_password ; ?>" name="user_password" class="form-control">
    </div>
    <div class="form-group">
        <input type="submit" name="edit_user" value="Edit User" class="btn btn-success">
    </div>
</form>