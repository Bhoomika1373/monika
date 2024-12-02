<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
<?php include "includes/navigation.php" ?>
<?php
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $f_name = $_POST['f_name'];
        $L_name = $_POST['L_name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        if(!empty($username) && !empty($email) && !empty($password)){
            $username = mysqli_real_escape_string($connection, $username);
            $phone = mysqli_real_escape_string($connection, $phone);
            $email = mysqli_real_escape_string($connection, $email);
            $password = mysqli_real_escape_string($connection, $password);
            $f_name = mysqli_real_escape_string($connection, $f_name);
            $L_name = mysqli_real_escape_string($connection, $L_name);
            $address = mysqli_real_escape_string($connection, $address);
            
            $query = "SELECT randSalt FROM users";
            $select_randSalt_query = mysqli_query($connection, $query);
            if(!$select_randSalt_query){
                die("QUERY FAILED" . mysqli_error($connection));
            }else{
                $row = mysqli_fetch_array($select_randSalt_query);
                $salt = $row['randSalt'];
                $password = crypt($password, $salt);
            }

            $query = "INSERT INTO users (username, user_firstname, user_lastname, user_phone, user_email, user_address, user_password, user_role)";
            $query .= "VALUES('{$username}', '{$f_name}', '{$L_name}', '{$phone}', '{$email}', '{$address}', '{$password}', 'subscriber')";
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
		</div>
	</div>
	<div id="content">
		<div>
			<div id="account">
				<div>
					<form action="signup.php" method="post" role="form" id="login-form" autocomplete="off" class="form-group">
						<span>SIGN-UP</span>
						<table>
							<tr>
								<td><label for="username">Username</label></td>
								<td><input name="username" type="text" id="login" /></td>
							</tr>
							<tr>
								<td><label for="f_name">First Name</label></td>
								<td><input name="f_name" type="text" id="login" /></td>
							</tr>
							<tr>
								<td><label for="L_name">Last Name</label></td>
								<td><input name="L_name" type="text" id="login" /></td>
							</tr>
							<tr>
								<td><label for="email">Phone</label></td>
								<td><input name="phone" type="text" id="email" /></td>
							</tr>
							<tr>
								<td><label for="email">E-mail</label></td>
								<td><input name="email" type="email" id="email" /></td>
							</tr>
							<tr>
								<td><label for="address">Address</label></td>
								<td><input name="address" type="text" id="email" /></td>
							</tr>
							<tr>
								<td><label for="password">Password</label></td>
								<td><input name="password" type="password" id="password" /></td>
							</tr>
						</table>
						<input type="submit" value="Sign-up" name="submit" class="submitbtn" />
					</form>
				</div>
			</div>
		</div>
	</div>
<?php include "includes/footer.php" ?>