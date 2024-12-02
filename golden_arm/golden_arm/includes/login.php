<?php include "db.php" ?>
<?php session_start(); ?>
<?php
	if(isset($_POST['login'])){
		$username = $_POST['username'];
		$password = $_POST['password'];

		$username = mysqli_real_escape_string($connection, $username);
		$password = mysqli_real_escape_string($connection, $password);

		$query = "SELECT * FROM users WHERE username = '{$username}'";
		$select_user_query = mysqli_query($connection,$query);

		if(!$select_user_query){
			die("QUERY FAILED". mysqli_error($connection));
		}

		while($row = mysqli_fetch_array($select_user_query)){
			$db_user_id = $row['user_id'];
			$db_username = $row['username'];
			$db_user_firstname = $row['user_firstname'];
			$db_user_lastname = $row['user_lastname'];
			$db_user_password = $row['user_password'];
			$db_user_email = $row['user_email'];
			$db_user_phone = $row['user_phone'];
			$db_user_address = $row['user_address'];
			$db_user_role = $row['user_role'];
		}
		$password = crypt($password, $db_user_password);
		if($username !== $db_username && $password !== $db_user_password){
			// echo "<script>alert('Incorrect username or password')</script>";
			header("Location: ../index.php");
		}else if($username == $db_username && $password == $db_user_password && $db_user_role == 'admin'){
			$_SESSION['username'] = $db_username;
			$_SESSION['user_firstname'] = $db_user_firstname;
			$_SESSION['user_lastname'] = $db_user_lastname;
			//$_SESSION['user_password'] = $db_user_password;
			$_SESSION['user_role'] = $db_user_role;
			
			header("Location: ../admin");
		}else if($username == $db_username && $password == $db_user_password && $db_user_role == 'subscriber'){
			$_SESSION['user_id'] = $db_user_id;
			$_SESSION['username'] = $db_username;
			$_SESSION['user_firstname'] = $db_user_firstname;
			$_SESSION['user_lastname'] = $db_user_lastname;
			$_SESSION['user_email'] = $db_user_email;
			$_SESSION['user_phone'] = $db_user_phone;
			$_SESSION['user_address'] = $db_user_address;
			//$_SESSION['user_password'] = $db_user_password;
			$_SESSION['user_role'] = $db_user_role;

			header("Location: ../product.php");
		}else{
			header("Location: ../home");
		}
	}
?>