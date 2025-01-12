<table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>F.Name</th>
            <th>L.Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $query = "SELECT * FROM users";
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
                echo "<tr>";
                echo "<td>{$user_id}</td>";
                echo "<td>{$username}</td>";
                echo "<td>{$user_firstname}</td>";
                echo "<td>{$user_lastname}</td>";
                echo "<td>{$user_email}</td>";
                echo "<td>{$user_phone}</td>";
                echo "<td>{$user_address}</td>";
                echo "<td>{$user_role}</td>";
                // $query = "SELECT * FROM posts WHERE post_id = $comment_post_id";
                // $select_post_id_query = mysqli_query($connection,$query);
                // confirm($select_post_id_query);
                // while($row = mysqli_fetch_assoc($select_post_id_query)){
                //     $post_id = $row['post_id'];
                //     $post_title = $row['post_title'];
                //     echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
                // }
                // echo "<td><img src='../images/{$post_image}' alt='{$post_image}' style='width:100px;' /></td>";
                echo "<td><a href='users.php?change_to_admin={$user_id}'>Admin</a></td>";
                echo "<td><a href='users.php?change_to_sub={$user_id}'>Subscriber</a></td>";
                echo "<td><a href='users.php?source=edit_user&edit_user={$user_id}'>Edit</a></td>";
                echo "<td><a href='users.php?delete={$user_id}'>Delete</a></td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>
<?php
    if(isset($_GET['change_to_admin'])){
        $the_user_id = $_GET['change_to_admin'];
        $query = "UPDATE users SET user_role = 'admin' WHERE user_id = {$the_user_id}"; 
        $admin_role_query = mysqli_query($connection,$query);
        confirm($admin_role_query);
        header("Location: users.php");
    }

    if(isset($_GET['change_to_sub'])){
        $the_user_id = $_GET['change_to_sub'];
        $query = "UPDATE users SET user_role = 'subscriber' WHERE user_id = {$the_user_id}"; 
        $sub_role_query = mysqli_query($connection,$query);
        confirm($sub_role_query);
        header("Location: users.php");
    }

    if(isset($_GET['delete'])){
        $the_user_id = $_GET['delete'];
        $query = "DELETE FROM users WHERE user_id = {$the_user_id}"; 
        $delete_user_query = mysqli_query($connection,$query);
        confirm($delete_user_query);
        header("Location: users.php");
    }
?>