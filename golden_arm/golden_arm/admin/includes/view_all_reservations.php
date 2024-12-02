<?php
  require 'libraries/PHPMailer/PHPMailer.php';
  require 'libraries/PHPMailer/SMTP.php';
  require 'libraries/PHPMailer/Exception.php';

  // Use PHPMailer classes
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
?>

<table class="table table-striped table-responsive">
  <thead>
    <tr>
      <th>ID</th>
      <th>Date</th>
      <th>Time</th>
      <th>No of Guests</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Phone Number</th>
      <th>Email ID</th>
      <th>Remarks</th>
      <th>Status</th>
      <th colspan="2">Actions</th>
    </tr>
  </thead>
    <tbody>
        <?php
            $reservations_query = "SELECT * FROM reservations";
            $select_reservations = mysqli_query($connection,$reservations_query);
            confirm($select_reservations);

            while($row = mysqli_fetch_assoc($select_reservations)){
                $id = $row['id'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $guests_count = $row['guests_count'];
                $email_id = $row['email_id'];
                $phone_number = $row['phone_number'];
                $date = $row['date'];
                $time = $row['time'];
                $remarks = $row['remarks'];
                $status = $row['status'];

                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$date}</td>";
                echo "<td>{$time}</td>";
                echo "<td>{$guests_count}</td>";
                echo "<td>{$first_name}</td>";
                echo "<td>{$last_name}</td>";
                echo "<td>{$phone_number}</td>";
                echo "<td>{$email_id}</td>";
                echo "<td>{$remarks}</td>";
                echo "<td>{$status}</td>";

                echo "<td><a href='reservations.php?appeared=$id'>Customer Appeared</a></td>";
                echo "<td><a href='reservations.php?cancel=$id'>Cancel</a></td>";
//                echo "<td><a href='reservations.php?delete=$id'>Delete</a></td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>
<?php
    if(isset($_GET['appeared'])){
        $id = $_GET['appeared'];
        $query = "UPDATE reservations SET status = 'Customer Appeared' WHERE id = {$id}";
        $appeared_query = mysqli_query($connection,$query);
        confirm($appeared_query);
        header("Location: reservations.php");
    }

    if(isset($_GET['cancel'])){
        $id = $_GET['cancel'];
        $query = "UPDATE reservations SET status = 'Cancelled' WHERE id = {$id}";
        $decline_query = mysqli_query($connection,$query);
        confirm($decline_query);

        $reservation_query = "SELECT * FROM reservations WHERE id = {$id}";
        $select_reservation = mysqli_query($connection,$reservation_query);

        while($row = mysqli_fetch_array($select_reservation)){
            $email_id = $row['email_id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $date = $row['date'];
            $time = $row['time'];
        }

        $smtp_host = 'smtp.gmail.com';
        $smtp_port = 587;
        $smtp_username = 'daaniyaal.islaam@gmail.com';
        $smtp_password = 'oicf hjkv klqv xwmv';
        $from_email = 'daaniyaal.islaam@gmail.com';

        $subject = 'Reservation Cancellation Notice';
        $body = "<html>
          <head>
            <title>$subject</title>
            <style>
              body {
                  font-family: Arial, sans-serif;
                  background-color: #f4f4f4;
                  color: #333;
                  line-height: 1.6;
                  margin: 0;
                  padding: 0;
              }
              .container {
                  width: 80%;
                  margin: 20px auto;
                  padding: 20px;
                  border: 1px solid #ddd;
                  border-radius: 10px;
                  background-color: #ffffff;
                  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
              }
              h2 {
                  color: #cc951e;
                  border-bottom: 2px solid #cc951e;
                  padding-bottom: 10px;
                  font-size: 25px;
              }
              .details {
                  margin: 20px 0;
                  padding: 10px;
                  background-color: #f9f9f9;
                  border-left: 5px solid #cc951e;
              }
              .details div {
                  margin-bottom: 10px;
                  padding: 5px;
              }
              .label {
                  font-weight: bold;
                  color: #cc951e;
              }
              p {
                  font-size: 18px;
              }
            </style>
          </head>
          <body>
            <div class='container'>
              <h2>$subject</h2>
              <p>Dear $first_name $last_name,</p>
              <p>We regret to inform you that due to unforeseen circumstances, we are unable to accommodate your reservation for $date at $time.</p>
              <p>We sincerely apologize for any inconvenience this may cause and appreciate your understanding.</p>
              <p>Thank you for your understanding, and we hope to welcome you to our restaurant soon.</p>
              <p>Warm regards,<br>Golden Arm Resturant</p>
            </div>
          </body>
        </html>";

        $mail = new PHPMailer(true);

        try {
            // SMTP server settings
            $mail->isSMTP();
            $mail->isHTML(true);
            $mail->Host = $smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $smtp_username;
            $mail->Password = $smtp_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable TLS encryption
            $mail->Port = $smtp_port;

            // Email details
            $mail->setFrom($from_email, 'Golden Arm');
            $mail->addAddress($email_id);

            $mail->Subject = 'Reservation Cancelled';
            $mail->Body    = $body;

            // Send email
            $mail->send();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        header("Location: reservations.php");
    }

    if(isset($_GET['delete'])){
        $id = $_GET['delete'];
        $query = "DELETE FROM reservations WHERE id = {$id}";
        $delete_query = mysqli_query($connection,$query);
        confirm($delete_query);
        header("Location: reservations.php");
    }
?>