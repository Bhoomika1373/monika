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
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Comments</th>
            <th>Date</th>
            <th>Order Details</th>
            <th colspan="2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $query = "SELECT * FROM pickup_orders";
            $select_orders = mysqli_query($connection,$query);
            confirm($select_orders);

            while($row = mysqli_fetch_assoc($select_orders)){
                $id = $row['id'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $email_id = $row['email_id'];
                $phone_number = $row['phone_number'];
                $date_time = $row['date_time'];
                $comments = $row['comments'];
                $status = $row['status'];
                $order_details = $row['order_details'];

                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$first_name}</td>";
                echo "<td>{$last_name}</td>";
                echo "<td>{$email_id}</td>";
                echo "<td>{$phone_number}</td>";
                echo "<td>{$status}</td>";
                echo "<td>{$comments}</td>";
                echo "<td>{$date_time}</td>";
                echo "<td onclick='onClickViewOrder(this)'><button type='button'>View Details</button><span style='display: none;'>{$order_details}</span></td>";
                echo "<td><a href='comments.php?approve=$id'>Approve</a></td>";
                echo "<td><a href='comments.php?decline=$id'>Decline</a></td>";
//                echo "<td><a href='comments.php?delete=$id'>Delete</a></td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>
<div class="modal fade" id="orderDetails" tabindex="-1" aria-labelledby="addToCart" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order No# span</h5>
      </div>
      <div class="modal-body">
        <main class="cart-container">
          <table>
            <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Total</th>
            </tr>
            </thead>
            <tbody></tbody>
          </table>
        </main>
      </div>
      <div class="modal-footer">
        <h4 id="totalPrice" class="total-price"><strong>Total: </strong>€<span>0</span></h4>
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  const onClickViewOrder = (_this) => {
    const rawOrderDetails = _this.getElementsByTagName('span')[0].innerText;
    const parsedOrderDetails = JSON.parse(rawOrderDetails);

    $('.cart-container tbody').empty()

    parsedOrderDetails.items.forEach(item => {
      $('.cart-container tbody').append(`
          <tr>
            <td>
              <img src="../images/${item.dish_image}" alt="${item.dish_name}" class="product-image">
              ${item.dish_name}
            </td>
            <td>€${item.dish_price}</td>
            <td>${item.quantity}</td>
            <td>€${item.total}</td>
          </tr>
        `);
    })

    $('#totalPrice span').text(parsedOrderDetails.totalPrice);
    $('#orderDetails').modal('show');
  }
</script>

<?php
    if(isset($_GET['approve'])){
        $the_order_id = $_GET['approve'];
        $query = "UPDATE pickup_orders SET status = 'Approved' WHERE id = {$the_order_id}";
        $approve_query = mysqli_query($connection,$query);
        confirm($approve_query);

        $order_query = "SELECT * FROM pickup_orders WHERE id = {$the_order_id}";
        $select_order = mysqli_query($connection,$order_query);

        while($row = mysqli_fetch_array($select_order)){
            $email_id = $row['email_id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
        }

        $smtp_host = 'smtp.gmail.com';
        $smtp_port = 587;
        $smtp_username = 'daaniyaal.islaam@gmail.com';
        $smtp_password = 'oicf hjkv klqv xwmv';
        $from_email = 'daaniyaal.islaam@gmail.com';

        $subject = 'Order Approved: Order# ' . $the_order_id;
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
                <p>We are pleased to inform you that your order has been approved!</p>
                <p>Please note that you need to pick up your order within 30 minutes at the restaurant location mentioned on our website.</p>
                <p>Thank you for choosing us. We appreciate your trust and look forward to serving you.</p>
                <p>If you have any questions or need further assistance, please do not hesitate to reach out.</p>
                <p>Warm regards,<br>Golden Arm Restaurant</p>
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

            $mail->Subject = 'Order Approved: Order# ' . $the_order_id;
            $mail->Body    = $body;

            // Send email
            $mail->send();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        header("Location: comments.php");
    }

    if(isset($_GET['decline'])){
        $the_order_id = $_GET['decline'];
        $query = "UPDATE pickup_orders SET status = 'Declined' WHERE id = {$the_order_id}";
        $unapprove_query = mysqli_query($connection,$query);
        confirm($unapprove_query);

        $order_query = "SELECT * FROM pickup_orders WHERE id = {$the_order_id}";
        $select_order = mysqli_query($connection,$order_query);

        while($row = mysqli_fetch_array($select_order)){
            $email_id = $row['email_id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
        }

        $smtp_host = 'smtp.gmail.com';
        $smtp_port = 587;
        $smtp_username = 'daaniyaal.islaam@gmail.com';
        $smtp_password = 'oicf hjkv klqv xwmv';
        $from_email = 'daaniyaal.islaam@gmail.com';

        $subject = 'Order Cancellation Notice: Order# ' . $the_order_id;
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
              <p>We regret to inform you that due to unforeseen circumstances, we are unable to complete your order.</p>
              <p>We sincerely apologize for any inconvenience this may cause and appreciate your understanding.</p>
              <p>Thank you for your understanding.</p>
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

            $mail->Subject = 'Order Cancelled: Order# ' . $the_order_id;
            $mail->Body    = $body;

            // Send email
            $mail->send();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        header("Location: comments.php");
    }

    if(isset($_GET['delete'])){
        $the_order_id = $_GET['delete'];
        $query = "DELETE FROM pickup_orders WHERE id = {$the_order_id}";
        $delete_query = mysqli_query($connection,$query);
        confirm($delete_query);
        header("Location: comments.php");
    }
?>