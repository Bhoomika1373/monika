<?php
  require 'libraries/PHPMailer/PHPMailer.php';
  require 'libraries/PHPMailer/SMTP.php';
  require 'libraries/PHPMailer/Exception.php';

  // Use PHPMailer classes
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  $current_date = date('Y-m-d');

  if(isset($_POST['reservation_filter'])){
      $filter_date = $_POST['filter_date'];

      header("Location: reservations.php?date_filter=$filter_date");
  }

  if(isset($_POST['today_filter'])){
      header("Location: reservations.php?date_filter=$current_date");
  }

  if(isset($_POST['all_filter'])){
      header("Location: reservations.php");
  }

  if(isset($_GET['date_filter'])){
      $date_filter = $_GET['date_filter'];
      $query = "SELECT * FROM reservations WHERE DATE(date) = '{$date_filter}'";
      $select_reservations = mysqli_query($connection,$query);
      confirm($select_reservations);
  } else {
      $query = "SELECT * FROM reservations";
      $select_reservations = mysqli_query($connection,$query);
      confirm($select_reservations);
  }

  if (isset($_POST['add_holidays'])) {
      $holiday_from_date = $_POST['from_date'];
      $holiday_to_date = $_POST['to_date'];

      $insert_query = "INSERT INTO holidays (from_date, to_date) VALUES ('{$holiday_from_date}', '{$holiday_to_date}')";
      $result = mysqli_query($connection, $insert_query);
  }

  if (isset($_POST['update_holidays'])) {
      $holiday_id = $_POST['id'];
      $holiday_from_date = $_POST['from_date'];
      $holiday_to_date = $_POST['to_date'];

      $update_query = "UPDATE holidays SET from_date = '{$holiday_from_date}', to_date = '{$holiday_to_date}' WHERE id = '{$holiday_id}'";
      $result = mysqli_query($connection, $update_query);

      // Check if a holiday record with these dates already exists
//      $check_query = "SELECT * FROM holidays WHERE id = '{$holiday_id}'";
//      $check_result = mysqli_query($connection, $check_query);

//      if (mysqli_num_rows($check_result) > 0) {
//          $update_query = "UPDATE holidays SET from_date = '{$holiday_from_date}', to_date = '{$holiday_to_date}' WHERE id = '{$holiday_id}'";
//          $result = mysqli_query($connection, $update_query);
//      } else {
//          $insert_query = "INSERT INTO holidays (from_date, to_date) VALUES ('{$holiday_from_date}', '{$holiday_to_date}')";
//          $result = mysqli_query($connection, $insert_query);
//      }
  }

  if (isset($_POST['delete_holidays'])) {
      $holiday_id = $_POST['id'];
      $query = "DELETE FROM holidays WHERE id = {$holiday_id}";
      $delete_query = mysqli_query($connection,$query);
  }

  $holidays_query = "SELECT * FROM holidays";
  $select_holidays = mysqli_query($connection,$holidays_query);
?>

<div class="row" style="background: #e6e6e6;border-radius: 12px;">
    <div class="col-lg-12">
      <h3>Holidays Marking <button id="addHolidays" class="btn btn-dark">Add</button></h3>
    </div>
      <?php
      while($row = mysqli_fetch_assoc($select_holidays)){
        $holiday_id = $row['id'];
        $holiday_from_date = $row['from_date'];
        $holiday_to_date = $row['to_date'];

        echo '
          <form action="" method="post" enctype="multipart/form-data" class="col-lg-12">
            <div class="row">
              <div class="col-md-5">
                <div class="form-group">
                  <label for="filter_date">From Date</label>
                  <input type="date" name="from_date" class="form-control" value="' . $holiday_from_date . '" />
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for="filter_date">To Date</label>
                  <input type="date" name="to_date" class="form-control" value="' . $holiday_to_date . '" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <input type="text" name="id" style="display: none;" value="' . $holiday_id . '" />
                  <input type="submit" name="delete_holidays" value="Delete" class="btn btn-danger" style="margin: 24px 5px 0 0; width: 45%; display: inline-block;">
                  <input type="submit" name="update_holidays" value="Update" class="btn btn-success" style="margin: 24px 0 0 0; width: 45%; display: inline-block;">
                </div>
              </div>
            </div>
          </form>
        ';
      }
      ?>

  <form action="" method="post" enctype="multipart/form-data" class="col-lg-12" id="addHolidaysForm">
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <label for="filter_date">From Date</label>
          <input type="date" name="from_date" class="form-control" />
        </div>
      </div>
      <div class="col-md-5">
        <div class="form-group">
          <label for="filter_date">To Date</label>
          <input type="date" name="to_date" class="form-control" />
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <input type="submit" name="add_holidays" value="Add" class="btn btn-success" style="margin: 24px 0 0 0; width: 100%; display: inline-block;">
        </div>
      </div>
    </div>
  </form>
</div>

  <br>

<div class="row">
  <form action="" method="post" enctype="multipart/form-data" class="col-lg-10">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="filter_date">Filter By Date</label>
          <input type="date" name="filter_date" class="form-control" value="<?php echo isset($date_filter) ? $date_filter : ""; ?>" />
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <input type="submit" name="reservation_filter" value="Filter" class="btn btn-success" style="margin: 24px 5px 0 0; width: 30%; display: inline-block;">
          <input type="submit" name="today_filter" value="Today's reservations" class="btn btn-info" style="margin: 24px 5px 0 0; width: 30%; display: inline-block;">
          <input type="submit" name="all_filter" value="All reservations" class="btn btn-dark" style="margin: 24px 0 0 0; width: 30%; display: inline-block;">
        </div>
      </div>
    </div>
  </form>
</div>

  <div class="table-responsive">
  <table class="table table-striped">
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
  </div>
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

        $smtp_host = 'goldenarm-restaurant.de';
        $smtp_port = 587;
        $smtp_username = 'info@goldenarm-restaurant.de';
        $smtp_password = 'GNGaCZRl6y]0';
        $from_email = 'info@goldenarm-restaurant.de';

        $subject = 'Stornierung der Reservierung';
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
              <p>Lieber $first_name $last_name,</p>
              <p>wir bedauern, Ihnen mitteilen zu müssen, dass wir Ihre Reservierung für den $date um $time Uhr aufgrund unvorhergesehener Umstände nicht berücksichtigen können.</p>
              <p>Wir entschuldigen uns aufrichtig für die Unannehmlichkeiten, die Ihnen dadurch entstehen, und danken Ihnen für Ihr Verständnis.</p>
              <p>Wir danken Ihnen für Ihr Verständnis und hoffen, Sie bald in unserem Restaurant begrüßen zu dürfen.</p>
              <p>Vielen herzlichen Dank!<br>Goldenarm Resturant</p>
            </div>
          </body>
        </html>";

        $mail = new PHPMailer();

        try {
            // SMTP server settings
            $mail->isSMTP();
            $mail->isHTML(true);
            $mail->Username = $smtp_username;
            $mail->Password = $smtp_password;
            $mail->Host = $smtp_host;
            $mail->Port = $smtp_port;
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;

            // Email details
            $mail->setFrom($from_email, 'Goldenarm');
            $mail->addAddress($email_id);

            $mail->Subject = 'Reservation Cancelled';
            $mail->Body    = $body;

            // Send email
            if (!$mail->send()) {
                // Log error instead of returning
                error_log('Mail error: ' . $mail->ErrorInfo);
            } else {
                // Log successful email sending
                error_log('Email successfully sent to ' . $email_id);
            }
        } catch (Exception $e) {
            // Log both PHPMailer and general exceptions
            error_log("Email could not be sent to {$email_id}. Mailer Error: {$mail->ErrorInfo}");
            error_log('Caught exception: ' . $e->getMessage());
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

<script>
  $('#addHolidaysForm').hide();

  $('#addHolidays').off('click').on('click', function (){
    $('#addHolidaysForm').show();
  })
</script>
