<?php include "includes/header.php" ?>
<?php include "includes/navigation_other.php" ?>
<?php
  require 'libraries/PHPMailer/PHPMailer.php';
  require 'libraries/PHPMailer/SMTP.php';
  require 'libraries/PHPMailer/Exception.php';

  // Use PHPMailer classes
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
?>
  <div class="container">
    <div class="inner-container">
      <h1>Reservation</h1>
      <div class="inner-separator"></div>

      <section id="reservation" class="reservation section">
        <div class="container">
          <div class="row">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
              <div class="content">
                <img src="assets/img/Reservation.jpg" class="reservation" alt="">
              </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <h3 class="text-center">Request a Table</h3>
              <div class="inner-separator"></div>

              <form action="reservation.php" method="post" enctype="multipart/form-data">
                <div class="reservation-form">
                  <div class="row gy-4">
                    <div class="col-md-6">
                      <label for="date">Date</label>
                      <select class="form-select" aria-label="date" name="date" id="date" required></select>
                    </div>

                    <div class="col-md-6">
                      <label for="time">Time</label>
                      <select class="form-select" aria-label="time" name="time" id="time" required></select>
                    </div>

                    <div class="col-md-12">
                      <label for="guestCount">No of Guests</label>
                      <select class="form-select" aria-label="guestCount" name="guestCount" id="guestCount" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                      </select>
                    </div>

                    <div class="col-md-6">
                      <label for="firstName">First Name</label>
                      <input type="text" name="firstName" id="firstName" class="form-control" placeholder="Enter you first name" required>
                    </div>

                    <div class="col-md-6">
                      <label for="lastName">Last Name</label>
                      <input type="text" name="lastName" id="lastName" class="form-control" placeholder="Enter you last name" required>
                    </div>

                    <div class="col-md-6">
                      <label for="phoneNumber">Phone Number</label>
                      <input type="number" name="phoneNumber" id="phoneNumber" class="form-control" placeholder="Enter you phone number" required>
                    </div>

                    <div class="col-md-6">
                      <label for="emailId">Email ID</label>
                      <input type="email" name="emailId" id="emailId" class="form-control" placeholder="Enter you email id" required>
                    </div>

                    <div class="col-md-12">
                      <label for="remarks">Remarks</label>
                      <textarea class="form-control" name="remarks" id="remarks" rows="6" placeholder="Enter your remarks" required></textarea>
                    </div>

                    <div class="col-md-12 text-center">
<!--                      <div class="loading">Loading</div>-->
<!--                      <div class="error-message"></div>-->
<!--                      <div class="sent-message">Your message has been sent. Thank you!</div>-->

                      <button type="submit" class="btn btn-primary" name="submit_reservation">Proceed</button>
                    </div>
                  </div>
                </div>
              </form>

                <?php
                if (isset($_POST['submit_reservation'])) {
                    $date = $_POST['date'];
                    $time = $_POST['time'];
                    $guestCount = $_POST['guestCount'];
                    $firstName = $_POST['firstName'];
                    $lastName = $_POST['lastName'];
                    $phoneNumber = $_POST['phoneNumber'];
                    $emailId = $_POST['emailId'];
                    $remarks = $_POST['remarks'];

                    // Corrected SQL query
                    $query = "INSERT INTO reservations (date, time, guests_count, first_name, last_name, phone_number, email_id, remarks, status) ";
                    $query .= "VALUES ('{$date}', '{$time}', '{$guestCount}', '{$firstName}', '{$lastName}', '{$phoneNumber}', '{$emailId}', '{$remarks}', 'Confirmed')";

                    $reservation_query = mysqli_query($connection, $query);

                    if (!$reservation_query) {
                        die("QUERY FAILED. " . mysqli_error($connection));
                    } else {
                        $reservation_id = mysqli_insert_id($connection);

                        $smtp_host = 'smtp.gmail.com';
                        $smtp_port = 587;
                        $smtp_username = 'daaniyaal.islaam@gmail.com';
                        $smtp_password = 'oicf hjkv klqv xwmv';
                        $from_email = 'daaniyaal.islaam@gmail.com';

                        $emails_list = [
                            [
                                'type' => 'admin',
                                'email' => 'goldenarmresturant@gmail.com',
                                'subject' => 'Reservation Request# '. $reservation_id
                            ],
                            [
                                'type' => 'customer',
                                'email' => $emailId,  // Customer's provided email
                                'subject' => 'Reservation Confirmed. Reservation ID# '. $reservation_id
                            ]
                        ];


                        foreach ($emails_list as $email_data) {
                          $dynamic_subject = $email_data['subject'];
                          $note = null;
                          if($email_data['type'] == 'customer'){
                              $note = "<p><strong>Note:</strong> Your reservation is all set! Just make sure to arrive at the restaurant on time. If you don't show up within 15 minutes, your reservation will automatically cancel. Thanks a bunch!</p>";
                          }
                          $body = "<html>
                            <head>
                                <title>Reservation Details</title>
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
                                    <h2>$dynamic_subject</h2>
                                    <div class='details'>
                                        <div><span class='label'>Date:</span> $date</div>
                                        <div><span class='label'>Time:</span> $time</div>
                                        <div><span class='label'>Guest Count:</span> $guestCount</div>
                                        <div><span class='label'>Name:</span> $firstName $lastName</div>
                                        <div><span class='label'>Phone Number:</span> $phoneNumber</div>
                                        <div><span class='label'>Email:</span> $emailId</div>
                                        <div><span class='label'>Remarks:</span> $remarks</div>
                                    </div>
                                    $note
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
                              $mail->addAddress($email_data['email']);

                              $mail->Subject = $email_data['subject'];
                              $mail->Body    = $body;

                              // Send email
                              $mail->send();
                          } catch (Exception $e) {
                              echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                          }
                        }
                    }

                    echo "<div class='alert alert-success alert-dismissible mt-4' role='alert'><strong>Reservation confirmed.</strong></div>";
                  }
                ?>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <script>
    // function getRemainingDaysInMonth() {
    //   const today = new Date();
    //   const year = today.getFullYear();
    //   const month = today.getMonth();
    //
    //   // Get the name of the current month
    //   const monthNames = [
    //     "January", "February", "March", "April", "May", "June",
    //     "July", "August", "September", "October", "November", "December"
    //   ];
    //   const monthName = monthNames[month];
    //
    //   // Get last day of the current month
    //   const lastDayOfMonth = new Date(year, month + 1, 0).getDate();
    //
    //   // Array to store remaining days in format 'Month name Day'
    //   const remainingDays = [];
    //
    //   // Loop through remaining days in the month starting from today
    //   for (let day = today.getDate() + 1; day <= lastDayOfMonth; day++) {
    //     remainingDays.push(`${monthName} ${day}`);
    //   }
    //
    //   return remainingDays;
    // }

    function getRemainingDaysInMonth() {
      const today = new Date();

      // Get the names of the months
      const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
      ];

      // Array to store the next 10 days with 'label' and 'value'
      const nextTenDays = [];

      // Loop through the next 10 days
      for (let i = 1; i <= 10; i++) {
        const nextDate = new Date();
        nextDate.setDate(today.getDate() + i); // Increment the date by i days

        const monthName = monthNames[nextDate.getMonth()];
        const day = nextDate.getDate();

        // Format the raw date as "YYYY-MM-DD"
        const year = nextDate.getFullYear();
        const month = String(nextDate.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed, so add 1
        const formattedDay = String(day).padStart(2, '0');

        // Push the object to the array
        nextTenDays.push({
          label: `${monthName} ${day}`,
          value: `${year}-${month}-${formattedDay}`
        });
      }

      return nextTenDays;
    }

    function populateSelect() {
      $('#date').empty();
      const remainingDays = getRemainingDaysInMonth();
      const selectElement = document.getElementById('date');

      // Add each day as an option to the select element
      remainingDays.forEach(day => {
        const option = document.createElement('option');
        option.value = day.value; // value will be the same as the display text
        option.textContent = day.label;
        selectElement.appendChild(option);
      });
    }

    // function generateTimeSlots() {
    //   const timeSlots = [];
    //   const startHour = 9; // 12 AM (midnight)
    //   const endHour = 22; // 11 PM
    //
    //   for (let hour = startHour; hour <= endHour; hour++) {
    //     for (let minutes = 0; minutes < 60; minutes += 30) {
    //       // Convert to 12-hour format
    //       const period = hour < 12 ? 'AM' : 'PM';
    //       const displayHour = hour % 12 === 0 ? 12 : hour % 12;
    //       const displayMinutes = minutes === 0 ? '00' : minutes;
    //
    //       timeSlots.push(`${displayHour}:${displayMinutes} ${period}`);
    //     }
    //   }
    //
    //   return timeSlots;
    // }

    function generateTimeSlots() {
      const timeSlots = [];
      const startHour = 17; // 5:00 PM (24-hour format)
      const endHour = 23;   // 11:00 PM (24-hour format)

      for (let hour = startHour; hour <= endHour; hour++) {
        for (let minutes = 0; minutes < 60; minutes += 30) {
          // Convert to 12-hour format
          const period = hour < 12 ? 'AM' : 'PM';
          const displayHour = hour % 12 === 0 ? 12 : hour % 12;
          const displayMinutes = minutes === 0 ? '00' : minutes;

          timeSlots.push(`${displayHour}:${displayMinutes} ${period}`);
        }
      }

      return timeSlots;
    }

    // Function to render the time slots inside a select element
    function renderTimeSlots() {
      $('#time').empty();
      const select = document.getElementById('time');
      const timeSlots = generateTimeSlots();

      // Create and append each option
      timeSlots.forEach(slot => {
        const option = document.createElement('option');
        option.value = slot;
        option.textContent = slot;
        select.appendChild(option);
      });
    }

    populateSelect();
    renderTimeSlots();

  </script>
<?php include "includes/footer.php" ?>