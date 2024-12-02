<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
<?php include "includes/navigation_innerpage.php" ?>
		</div>
	</div>
	<div id="content" class="inner_content">
		<div>
			<h1>Contact us</h1>
			<div id="visitshop">
				<div>
					<p><span>Sweets from the heart</span> Treat your loveones</p>
					 <a href="" class="visit">visit the shop</a>
				</div>
			</div>
			<?php
			    if(isset($_POST['submit_form'])){
			        $username = $_POST['username'];
			        $email = $_POST['email'];
			        $subject = $_POST['subject'];
			        $message = $_POST['message'];

			        $query = "INSERT INTO feedback(username,email,subject,message)";
			        $query .= "VALUE('{$username}','{$email}','{$subject}','{$message}')";
			        $feedback_query = mysqli_query($connection,$query);

                    if(!$feedback_query){
                        die("QUERY FAILED ." . mysqli_error($connection));
                    }
			        $the_cake_id = mysqli_insert_id($connection);
			        echo "<div class='alert alert-success alert-dismissible' role='alert'>
			                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong> Feedback send !!! </strong></div>";
			    }
			?>
			<form action="contact.php" method="post" enctype="multipart/form-data">
				<p>If you need assistance feel free to e-mail us. <span>Mauris dictum congque porta. Duis dapibus tellus id dolor fringilla et viverra nibh semper. Praesent sit amet lacus tortor.</span></p>
				<input type="text" maxlength="30" name="username" class="textcontact" placeholder="Name" />
				<input type="email" maxlength="30" Address" name="email" class="textcontact" placeholder="Email Address" />
				<input type="text" maxlength="30" name="subject" class="textcontact" placeholder="Subject" />
				<textarea name="message" id="message" cols="30" rows="10" placeholder="Enter your message here ..."></textarea>
				<input type="submit" value="" name="submit_form" class="submit" />
			</form>
		</div>
	</div>
<?php include "includes/footer.php" ?>