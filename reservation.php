<?php include "includes/db.php" ?>
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
<?php
  $confirmed_reservations_query = "SELECT * FROM reservations WHERE status = 'Confirmed'";
  $confirmed_reservations = mysqli_query($connection,$confirmed_reservations_query);

  $reservations = [];

  while($row = mysqli_fetch_assoc($confirmed_reservations)) {
      $reservations[] = $row;
  }

  $holidays_query = "SELECT * FROM holidays";
  $select_holidays = mysqli_query($connection,$holidays_query);

  $holidays = [];
  $holiday_from_date = null;
  $holiday_to_date = null;

  while($row = mysqli_fetch_assoc($select_holidays)){
      $holiday_from_date = $row['from_date'];
      $holiday_to_date = $row['to_date'];
      $holidays[] = $row;
  }

  $is_form_submit = false;
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

                  $reservations_check_query = "SELECT SUM(guests_count) as total_guests FROM reservations WHERE status = 'Confirmed' AND date = '$date' AND time = '$time'";

                  $confirmed_reservations = mysqli_query($connection, $reservations_check_query);

                  $row = mysqli_fetch_assoc($confirmed_reservations);
                  $total_guests = $row['total_guests'] ?? 0;  // Default to 0 if null

                  if ($total_guests + $guestCount > 22) {
                      echo "<div class='alert alert-danger alert-dismissible mt-4' role='alert'><strong>Seats unavailable.</strong></div>";

                      $confirmed_reservations_query = "SELECT * FROM reservations WHERE status = 'Confirmed'";
                      $confirmed_reservations = mysqli_query($connection,$confirmed_reservations_query);

                      $reservations = [];

                      while($row = mysqli_fetch_assoc($confirmed_reservations)) {
                          $reservations[] = $row;
                      }
                  } else {
                      // Corrected SQL query
                      $query = "INSERT INTO reservations (date, time, guests_count, first_name, last_name, phone_number, email_id, remarks, status) ";
                      $query .= "VALUES ('{$date}', '{$time}', '{$guestCount}', '{$firstName}', '{$lastName}', '{$phoneNumber}', '{$emailId}', '{$remarks}', 'Confirmed')";

                      $reservation_query = mysqli_query($connection, $query);

                      if (!$reservation_query) {
                          die("QUERY FAILED. " . mysqli_error($connection));
                      } else {
                          $reservation_id = mysqli_insert_id($connection);
                          $smtp_host = 'goldenarm-restaurant.de';
                          $smtp_port = 587;
                          $smtp_username = 'info@goldenarm-restaurant.de';
                          $smtp_password = 'GNGaCZRl6y]0';
                          $from_email = 'info@goldenarm-restaurant.de';

                          $emails_list = [
                              [
                                  'type' => 'admin',
                                  'email' => 'goldenarmresturant@gmail.com',
                                  'subject' => 'Reservation Request# '. $reservation_id
                              ],
                              [
                                  'type' => 'customer',
                                  'email' => $emailId,  // Customer's provided email
                                  'subject' => 'Reservierung bestätigt. Reservierung ID# '. $reservation_id
                              ]
                          ];


                          foreach ($emails_list as $email_data) {
                              $dynamic_subject = $email_data['subject'];
                              $note = null;
                              if($email_data['type'] == 'customer'){
                                  $note = "<p>
                              <strong>Hinweis:</strong> Achten Sie nur darauf, dass Sie pünktlich im Restaurant erscheinen. Wenn Sie nicht innerhalb von 15 Minuten nach Ihrer Reservierungszeit eintreffen, wird Ihre Reservierung automatisch storniert.
                            </p>";
                              }
                              $body = "<html>
                          <head>
                              <title>Reservierung bestätigt</title>
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
                                  <br>
                                  <p>Vielen herzlichen Dank! <br>Goldenarm Restaurant</p>
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
                                  $mail->addAddress($email_data['email']);

                                  $mail->Subject = $email_data['subject'];
                                  $mail->Body    = $body;

                                  // Send email
                                  if (!$mail->send()) {
                                      // Log error instead of returning
                                      error_log('Mail error: ' . $mail->ErrorInfo);
                                  } else {
                                      // Log successful email sending
                                      error_log('Email successfully sent to ' . $email_data['email']);
                                  }
                              } catch (Exception $e) {
                                  // Log both PHPMailer and general exceptions
                                  error_log("Email could not be sent to {$email_data['email']}. Mailer Error: {$mail->ErrorInfo}");
                                  error_log('Caught exception: ' . $e->getMessage());
                              }
                          }
                      }

                      $confirmed_reservations_query = "SELECT * FROM reservations WHERE status = 'Confirmed'";
                      $confirmed_reservations = mysqli_query($connection,$confirmed_reservations_query);

                      $reservations = [];

                      while($row = mysqli_fetch_assoc($confirmed_reservations)) {
                          $reservations[] = $row;
                      }
                      echo "<div class='alert alert-success alert-dismissible mt-4' role='alert'><strong>Reservation confirmed.</strong></div>";
                  }
                }
                ?>
              <form action="reservation.php" method="post" enctype="multipart/form-data" id="reservationForm">
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
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
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
                      <label for="emailId">Email</label>
                      <input type="email" name="emailId" id="emailId" class="form-control" placeholder="Enter you email" required>
                    </div>

                    <div class="col-md-12">
                      <label for="remarks">Remarks</label>
                      <textarea class="form-control" name="remarks" id="remarks" rows="6" placeholder="Enter your remarks"></textarea>
                    </div>

                    <div class="col-md-12 text-center">
<!--                      <div class="loading">Loading</div>-->
<!--                      <div class="error-message"></div>-->
<!--                      <div class="sent-message">Your message has been sent. Thank you!</div>-->


                      <button type="submit" class="btn btn-primary d-none" name="submit_reservation">Form Submit</button>
                      <button class="btn btn-primary" id="submitReservation">Proceed</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <script>
    let isFirstTime = true;
    const totalSeats = 22;
    const confirmedReservations = <?php echo json_encode(isset($reservations) && !empty($reservations) ? $reservations : []); ?>;
    const holidayFromDate = "<?php echo $holiday_from_date; ?>";
    const holidayToDate = "<?php echo $holiday_to_date; ?>";
    const listOfHolidays = <?php echo json_encode(isset($holidays) && !empty($holidays) ? $holidays : []); ?>;
    const maxGuestLimit = 15;
    let finalData = {};
    let isFormSubmit = false;

    // console.log({holidayFromDate, holidayToDate})

    const timeConfig = {
      "Mon": [
        { startHour: 11, startMinute: 30, endHour: 14, endMinute: 30 },
        { startHour: 17, startMinute: 0, endHour: 22, endMinute: 0 }
      ],
      "Wed": [
        { startHour: 11, startMinute: 30, endHour: 14, endMinute: 30 },
        { startHour: 17, startMinute: 0, endHour: 22, endMinute: 0 }
      ],
      "Thur": [
        { startHour: 11, startMinute: 30, endHour: 14, endMinute: 30 },
        { startHour: 17, startMinute: 0, endHour: 22, endMinute: 0 }
      ],
      "Fri": [
        { startHour: 11, startMinute: 30, endHour: 14, endMinute: 30 },
        { startHour: 17, startMinute: 0, endHour: 22, endMinute: 0 }
      ],
      "Sat": [
        { startHour: 11, startMinute: 30, endHour: 23, endMinute: 0 }
      ],
      "Sun": [
        { startHour: 12, startMinute: 0, endHour: 21, endMinute: 0 }
      ]
    };

    const detailedTimeConfig = {
      "Mon": [
        "11:30:00", "11:45:00", "12:00:00", "12:15:00", "12:30:00", "12:45:00",
        "13:00:00", "13:15:00", "13:30:00", "13:45:00", "14:00:00", "14:15:00",
        "14:30:00", "17:00:00", "17:15:00", "17:30:00", "17:45:00", "18:00:00",
        "18:15:00", "18:30:00", "18:45:00", "19:00:00", "19:15:00", "19:30:00",
        "19:45:00", "20:00:00", "20:15:00", "20:30:00", "20:45:00", "21:00:00",
        "21:15:00", "21:30:00", "21:45:00", "22:00:00"
      ],
      "Wed": [
        "11:30:00", "11:45:00", "12:00:00", "12:15:00", "12:30:00", "12:45:00",
        "13:00:00", "13:15:00", "13:30:00", "13:45:00", "14:00:00", "14:15:00",
        "14:30:00", "17:00:00", "17:15:00", "17:30:00", "17:45:00", "18:00:00",
        "18:15:00", "18:30:00", "18:45:00", "19:00:00", "19:15:00", "19:30:00",
        "19:45:00", "20:00:00", "20:15:00", "20:30:00", "20:45:00", "21:00:00",
        "21:15:00", "21:30:00", "21:45:00", "22:00:00"
      ],
      "Thur": [
        "11:30:00", "11:45:00", "12:00:00", "12:15:00", "12:30:00", "12:45:00",
        "13:00:00", "13:15:00", "13:30:00", "13:45:00", "14:00:00", "14:15:00",
        "14:30:00", "17:00:00", "17:15:00", "17:30:00", "17:45:00", "18:00:00",
        "18:15:00", "18:30:00", "18:45:00", "19:00:00", "19:15:00", "19:30:00",
        "19:45:00", "20:00:00", "20:15:00", "20:30:00", "20:45:00", "21:00:00",
        "21:15:00", "21:30:00", "21:45:00", "22:00:00"
      ],
      "Fri": [
        "11:30:00", "11:45:00", "12:00:00", "12:15:00", "12:30:00", "12:45:00",
        "13:00:00", "13:15:00", "13:30:00", "13:45:00", "14:00:00", "14:15:00",
        "14:30:00", "17:00:00", "17:15:00", "17:30:00", "17:45:00", "18:00:00",
        "18:15:00", "18:30:00", "18:45:00", "19:00:00", "19:15:00", "19:30:00",
        "19:45:00", "20:00:00", "20:15:00", "20:30:00", "20:45:00", "21:00:00",
        "21:15:00", "21:30:00", "21:45:00", "22:00:00"
      ],
      "Sat": [
        "11:30:00", "11:45:00", "12:00:00", "12:15:00", "12:30:00", "12:45:00",
        "13:00:00", "13:15:00", "13:30:00", "13:45:00", "14:00:00", "14:15:00",
        "14:30:00", "14:45:00", "15:00:00", "15:15:00", "15:30:00", "15:45:00",
        "16:00:00", "16:15:00", "16:30:00", "16:45:00", "17:00:00", "17:15:00",
        "17:30:00", "17:45:00", "18:00:00", "18:15:00", "18:30:00", "18:45:00",
        "19:00:00", "19:15:00", "19:30:00", "19:45:00", "20:00:00", "20:15:00",
        "20:30:00", "20:45:00", "21:00:00", "21:15:00", "21:30:00", "21:45:00",
        "22:00:00", "22:15:00", "22:30:00", "22:45:00", "23:00:00"
      ],
      "Sun": [
        "12:00:00", "12:15:00", "12:30:00", "12:45:00", "13:00:00", "13:15:00",
        "13:30:00", "13:45:00", "14:00:00", "14:15:00", "14:30:00", "14:45:00",
        "15:00:00", "15:15:00", "15:30:00", "15:45:00", "16:00:00", "16:15:00",
        "16:30:00", "16:45:00", "17:00:00", "17:15:00", "17:30:00", "17:45:00",
        "18:00:00", "18:15:00", "18:30:00", "18:45:00", "19:00:00", "19:15:00",
        "19:30:00", "19:45:00", "20:00:00", "20:15:00", "20:30:00", "20:45:00",
        "21:00:00"
      ]
    }

    getAvailableSeats(confirmedReservations, totalSeats);

    function getDayOfWeek(dateString) {
      const date = new Date(dateString);
      const days = ["Sun", "Mon", "Tue", "Wed", "Thur", "Fri", "Sat"];
      return days[date.getDay()];
    }

    // Utility function to format a time in "HH:MM:SS"
    function formatTime(hour, minute) {
      return `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}:00`;
    }

    function formatDate(dateString) {
      const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
      ];

      const date = new Date(dateString);
      const day = date.getDate();
      const month = months[date.getMonth()];
      const year = date.getFullYear();

      // Add appropriate ordinal suffix to the day
      const ordinalSuffix = (n) => {
        if (n > 3 && n < 21) return 'th'; // exceptions for 11th, 12th, 13th, etc.
        switch (n % 10) {
          case 1: return 'st';
          case 2: return 'nd';
          case 3: return 'rd';
          default: return 'th';
        }
      };

      return `${day}${ordinalSuffix(day)} ${month} ${year}`;
    }

    // Generate available seats and check for full-day booked dates
    function getAvailableSeats(reservations, totalSeats) {

      // Group reservations by date
      const reservationsByDate = {};
      reservations.forEach(({ date, time, guests_count }) => {
        if (!reservationsByDate[date]) {
          reservationsByDate[date] = {};
        }

        // Convert the existing 30-minute reservation to two 15-minute slots
        const [hour, minute] = time.split(':').map(Number);

        const firstSlot = formatTime(hour, minute); // e.g., "11:30:00"
        let secondSlot;

        // If the current reservation is 30-minute based, create two 15-minute slots
        if (minute === 0 || minute === 30) {
          secondSlot = formatTime(hour, minute + 15);
        } else {
          secondSlot = formatTime(hour, minute - 15);
        }

        // Add the guests to both 15-minute slots
        reservationsByDate[date][firstSlot] = (reservationsByDate[date][firstSlot] || 0) + parseInt(guests_count, 10);
        if (secondSlot) {
          reservationsByDate[date][secondSlot] = (reservationsByDate[date][secondSlot] || 0) + parseInt(guests_count, 10);
        }
      });

      // console.log({reservationsByDate})

      let startDate = new Date();

      let dayCount = 0;
      while (dayCount < 60) {
        const nextDate = new Date(startDate);
        nextDate.setDate(startDate.getDate() + 1);
        startDate = nextDate;

        const dayOfWeek = nextDate.getDay();
        if (dayOfWeek === 2) continue;

        const day = nextDate.getDate();
        const year = nextDate.getFullYear();
        const month = String(nextDate.getMonth() + 1).padStart(2, '0');
        const formattedDay = String(day).padStart(2, '0');
        const formattedDate = `${year}-${month}-${formattedDay}`;
        let dayOfWeekShortName = getDayOfWeek(formattedDate);
        let timeSlots = detailedTimeConfig[dayOfWeekShortName];

        finalData[formattedDate] = {}

        if (timeSlots) {
          timeSlots.forEach(slot => {
            const reservedSeats = reservationsByDate[formattedDate]?.[slot] || 0;
            const availableSeats = totalSeats - reservedSeats;

            if (availableSeats <= 0) {
              finalData[formattedDate][slot] = 0
            } else {
              finalData[formattedDate][slot] = availableSeats
            }
          });
        }
        dayCount++;
      }

      //console.log({finalData})
      generateDateDropdown();
    }

    function generateDateDropdown() {
      // console.log(listOfHolidays)
      // const holidays = getHolidayDates(holidayFromDate, holidayToDate);
      const holidays = getHolidayDatesFromArray(listOfHolidays);
      // console.log(holidays)
      // console.log({holidays})
      $('#date').empty();
      const selectElement = document.getElementById('date');

      for (const property in finalData) {
        if(!holidays.includes(property)) {
          if(finalData[property]) {
            const option = document.createElement('option');
            // console.log({property})
            option.value = property;
            option.textContent = formatDate(property);
            selectElement.appendChild(option);
          }
        }
      }

      if (isFirstTime) {
        $('#date').val($('#date').val());
        generateTimeSlotsDropdown($('#date').val());
        isFirstTime = false;
      }
    }

    $('#date').off('change').on('change', () => {
      generateTimeSlotsDropdown($('#date').val());
    })

    function generateTimeSlotsDropdown(dateString) {
      const selectedDateTimeSlots = finalData[dateString];
      let defaultTime = null;


      $('#time').empty();
      const select = document.getElementById('time');
      for (const property in selectedDateTimeSlots) {
        if(selectedDateTimeSlots[property] !== 0) {
          const option = document.createElement('option');
          option.value = property;
          option.textContent = property;
          select.appendChild(option);

          if(!defaultTime) {
            defaultTime = property;
          }
        }
      }

      renderGuestCountDropDown(dateString, defaultTime);
    }

    $('#time').off('change').on('change', () => {
      renderGuestCountDropDown($('#date').val(), $('#time').val());
    })

    function renderGuestCountDropDown(selectedDate, selectedTime) {
      let maxGuests = 0;
      const currentLimit = finalData[selectedDate][selectedTime];

      if(currentLimit > maxGuestLimit) {
        maxGuests = maxGuestLimit;
      } else {
        maxGuests = currentLimit;
      }

      $('#guestCount').empty();
      const guestCount = document.getElementById('guestCount');
      for (let i = 1; i <= maxGuests; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i;
        guestCount.appendChild(option);
      }
    }

    function getHolidayDates(holidayFromDate, holidayToDate) {
      // Check if both dates are provided
      if (!holidayFromDate || !holidayToDate) {
        return [];
      }

      const startDate = new Date(holidayFromDate);
      const endDate = new Date(holidayToDate);
      const dates = [];

      // Iterate from startDate to endDate
      for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
        dates.push(new Date(d).toISOString().split('T')[0]);
      }

      return dates;
    }

    function getHolidayDatesFromArray(holidayArray) {
      const allHolidayDates = [];

      holidayArray.forEach(holiday => {
        const { from_date: holidayFromDate, to_date: holidayToDate } = holiday;

        // Check if both dates are provided
        if (!holidayFromDate || !holidayToDate) {
          return;
        }

        const startDate = new Date(holidayFromDate);
        const endDate = new Date(holidayToDate);

        // Iterate from startDate to endDate and collect dates
        for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
          allHolidayDates.push(new Date(d).toISOString().split('T')[0]);
        }
      });

      return allHolidayDates;
    }


    function formIsValid() {
      // Get the form elements
      const date = $('#date').val();
      const time = $('#time').val();
      const guestCount = $('#guestCount').val();
      const firstName = $('#firstName').val();
      const lastName = $('#lastName').val();
      const phoneNumber = $('#phoneNumber').val();
      const emailId = $('#emailId').val();
      // const remarks = $('#remarks').val();

      // Validate each field
      if (!date) {
        alert("Please select a date.");
        return false;
      }

      if (!time) {
        alert("Please select a time.");
        return false;
      }

      if (!guestCount) {
        alert("Please select the number of guests.");
        return false;
      }

      if (!firstName || firstName.trim() === '') {
        alert("Please enter your first name.");
        return false;
      }

      if (!lastName || lastName.trim() === '') {
        alert("Please enter your last name.");
        return false;
      }

      // Phone number validation (simple check: must be 10 digits)
      const phonePattern = /^[0-9]{11}$/;
      if (!phonePattern.test(phoneNumber)) {
        alert("Please enter a valid 11-digit phone number.");
        return false;
      }

      // Email validation (basic pattern check)
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(emailId)) {
        alert("Please enter a valid email address.");
        return false;
      }

      // if (!remarks || remarks.trim() === '') {
      //   alert("Please enter your remarks.");
      //   return false;
      // }

      // If all validations pass, return true
      return true;
    }

    $('#submitReservation').off('click').on('click', (event) => {
      event.preventDefault();

      if (!formIsValid()) {
        return;
      }

      $('#submitReservation').prop('disabled', true);

      const originalText = $('#submitReservation').html();
      $('#submitReservation').html('<i class="fas fa-spinner fa-spin"></i> Submitting...');
      $('button[name="submit_reservation"]').click();

      // setTimeout(() => {
      //   $('#submitReservation').prop('disabled', false);
      //   $('#submitReservation').html(originalText);
      // }, 5000);
    });
  </script>
<?php include "includes/footer.php" ?>