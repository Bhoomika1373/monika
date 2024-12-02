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
  $categories_query = "SELECT * FROM categories";
  $select_all_categories = mysqli_query($connection,$categories_query);

  $categories = [];

  while($row = mysqli_fetch_assoc($select_all_categories)) {
      $categories[] = $row;
  }

  $dishes_query = 'SELECT * FROM dishes WHERE dish_status = "available"';
  $select_all_dishes = mysqli_query($connection,$dishes_query);

  $dishes = [];

  while($row = mysqli_fetch_assoc($select_all_dishes)) {
      $dishes[] = $row;
  }
?>

  <div class="secondary-menu">
    <div class="container" style="overflow: auto;">
      <ul id="categoriesList"></ul>
    </div>
  </div>
  <div class="container">
    <div class="inner-container" style="margin-bottom: 14rem;">
      <h1 id="menu-heading">Loading...</h1>
      <div class="inner-separator"></div>

      <div id="dishesList" class="row mt-5"></div>
    </div>
  </div>

  <div class="modal fade modal-lg" id="addToCart" tabindex="-1" aria-labelledby="addToCart" aria-hidden="true">
      <?php
        if(isset($_POST['order_proceed_button'])){
          $firstName = $_POST['firstName'];
          $lastName = $_POST['lastName'];
          $phoneNumber = $_POST['phoneNumber'];
          $emailId = $_POST['emailId'];
          $orderDetails = $_POST['orderDetails'];
          $comments = $_POST['comments'];

          $query = "INSERT INTO pickup_orders (first_name, last_name, phone_number, email_id, order_details, comments, status, date_time)";
          $query .= "VALUES ('{$firstName}', '{$lastName}', '{$phoneNumber}', '{$emailId}', '{$orderDetails}', '{$comments}', 'Pending' ,now())";

          $create_order_query = mysqli_query($connection, $query);
          if(!$create_order_query){
            die("QUERY FAILED ." . mysqli_error($connection));
          } else {
              $order_id = mysqli_insert_id($connection);

              $smtp_host = 'smtp.gmail.com';
              $smtp_port = 587;
              $smtp_username = 'daaniyaal.islaam@gmail.com';
              $smtp_password = 'oicf hjkv klqv xwmv';
              $from_email = 'daaniyaal.islaam@gmail.com';
              $subject = 'Order Request# '. $order_id;

              $emails_list = [
                  [
                      'type' => 'admin',
                      'email' => 'goldenarmresturant@gmail.com'
                  ],
                  [
                      'type' => 'customer',
                      'email' => $emailId
                  ]
              ];

              $orderDetails = json_decode($orderDetails, true);
              $items = $orderDetails['items'];
              $totalPrice = $orderDetails['totalPrice'];// Email subject

              $itemDetails = '';
              foreach ($items as $item) {
                $itemDetails .= "
                  <div style='margin-bottom: 15px;'>
                    <strong>{$item['dish_name']}</strong> - \${$item['dish_price']} x {$item['quantity']} = \${$item['total']}<br>
                    <small>{$item['dish_content']}</small>
                  </div>";
              }

              $admin_text = "
                <p>Dear Admin,</p>
                <p>An order has been placed from $firstName $lastName, please check admin portal and take action as per the requirment. Order details are mentioned below: </p>
                <div style='border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;'>
                    $itemDetails
                    <strong>Total Price: \$$totalPrice</strong>
                </div>
                <p>Regards,<br>Golden Arm Restaurant</p>
              ";

              $customer_text = "
                <p>Dear $firstName $lastName,</p>
                <p>Thank you for your order! Here are the details:</p>
                <div style='border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;'>
                    $itemDetails
                    <strong>Total Price: \$$totalPrice</strong>
                </div>
                <p>Your order request has been placed successfully, you will receive confirmation shortly.</p>
                <p>Thank you for choosing our restaurant!</p>
                <p>Warm Regards,<br>Golden Arm Restaurant</p>
              ";

              foreach ($emails_list as $email_data) {
                  $mail = new PHPMailer(true);
                  if($email_data['type'] == 'customer'){
                      $note = $customer_text;
                  } else {
                      $note = $admin_text;
                  }

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
                            $note
                        </div>
                    </body>
                  </html>";

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

                      $mail->Subject = $subject;
                      $mail->Body    = $body;

                      // Send email
                      $mail->send();
                  } catch (Exception $e) {
                      echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                  }
              }
          }

          echo "<script>localStorage.clear()</script>";
          echo "<script>window.location = '/golden_arm/home'</script>";
        }
      ?>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Cart</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <th></th>
              </tr>
              </thead>
              <tbody></tbody>
            </table>
          </main>

          <div id="checkout">
            <form action="" method="post" enctype="multipart/form-data">
              <div class="reservation-form">
                <div class="row gy-4">
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
                    <label for="comments">Comments</label>
                    <textarea class="form-control" name="comments" id="comments" rows="6" placeholder="Enter your comments" required></textarea>
                  </div>

                  <textarea class="form-control" name="orderDetails" id="orderDetails" rows="6" hidden></textarea>
                  <button type="submit" class="btn btn-primary d-none" name="order_proceed_button">Proceed</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer">
          <h4 id="totalPrice" class="total-price"><strong>Total: </strong>€<span>0</span></h4>
          <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-md btn-primary" style="padding: 7px 20px;" id="checkout-button" onclick="checkout()">Checkout</button>
          <button type="submit" class="btn btn-md btn-primary" style="padding: 7px 20px;" id="order-proceed-button" onclick="onClickProceed()">Proceed</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const currentPage = window.location.pathname.split('/').reverse()[0];
    let selectedCategory = null;
    let categories = <?php echo json_encode($categories); ?>;
    let dishes = <?php echo json_encode($dishes); ?>;
    let selectForCart = null;

    const markSelectedCategory = (category) => {
      if(!selectedCategory) {
        selectedCategory = categories[0]
      }

      if(category.cat_id === selectedCategory.cat_id) {
        $('#menu-heading').text(category.cat_title)

        return 'active';
      }

      return '';
    }

    const onClickCategory = (category) => {
      selectedCategory = category;

      renderCategories();
    }

    const renderCategories = () => {
      $('#categoriesList').empty();
      $.each(categories, function(index, category) {
        const categoryClass = markSelectedCategory(category);
        const $li = $('<li>')
          .addClass(categoryClass)
          .text(category.cat_title)
          .on('click', function() {
            onClickCategory(category);
          });

        $('#categoriesList').append($li);
      });

      renderDishes();
    }

    const cartHandler = (dish, action) => {
      $('#checkout').hide();
      $('.cart-container').show();
      $('#checkout-button').show();
      $('#order-proceed-button').hide();

      let cartParsed = []
      let totalPrice = 0
      if(action === 'add') {
        selectForCart = dish;

        let cart = localStorage.getItem('cart') || '{ "items": [], "totalPrice": 0 }';
        cartParsed = JSON.parse(cart).items


        let updated_cart = []
        let isExistingItem = false
        cartParsed.forEach(item => {
          if(item.dish_id == dish.dish_id) {
            updated_cart.push({
              ...dish,
              quantity: item.quantity + 1,
              total: dish.dish_price * (item.quantity + 1)
            })
            isExistingItem = true
          } else {
            updated_cart.push(item)
          }
        })

        if(!isExistingItem) {
          updated_cart.push({
            ...dish,
            quantity: 1,
            total: dish.dish_price
          })
        }

        cartParsed = updated_cart

        totalPrice = cartParsed.reduce((sum, item) => {
          return sum + (parseFloat(item.dish_price) * item.quantity);
        }, 0);

        localStorage.setItem('cart', JSON.stringify({ "items": cartParsed, "totalPrice": totalPrice }))
      } else if(action === 'update') {
        let cart = localStorage.getItem('cart') || '{ "items": [], "totalPrice": 0 }';
        cartParsed = JSON.parse(cart).items

        let updated_cart = []
        cartParsed.forEach(item => {
          if(item.dish_id == dish.dish_id) {
            if(dish.quantity !== 0) {
              updated_cart.push({
                ...item,
                ...dish,
                total: item.dish_price * dish.quantity
              })
            }
          } else {
            updated_cart.push(item)
          }
        })

        cartParsed = updated_cart

        totalPrice = cartParsed.reduce((sum, item) => {
          return sum + (parseFloat(item.dish_price) * item.quantity);
        }, 0);

        localStorage.setItem('cart', JSON.stringify({ "items": cartParsed, "totalPrice": totalPrice }))
      } else if(action === 'delete') {
        let cart = localStorage.getItem('cart') || '{ "items": [], "totalPrice": 0 }';
        cartParsed = JSON.parse(cart).items

        let updated_cart = []
        cartParsed.forEach(item => {
          if(item.dish_id != dish.dish_id) {
            updated_cart.push(item)
          }
        })

        cartParsed = updated_cart

        totalPrice = cartParsed.reduce((sum, item) => {
          return sum + (parseFloat(item.dish_price) * item.quantity);
        }, 0);

        localStorage.setItem('cart', JSON.stringify({ "items": cartParsed, "totalPrice": totalPrice }))
      }

      $('.cart-container tbody').empty()

      cartParsed.forEach(item => {
        $('.cart-container tbody').append(`
          <tr>
            <td>
              <img src="images/${item.dish_image}" alt="${item.dish_name}" class="product-image">
              ${item.dish_name}
            </td>
            <td>€${item.dish_price}</td>
            <td>
              <div class="quantity-selector">
                <button id="decrement-${item.dish_id}" onclick="cartHandler({dish_id: '${item.dish_id}', quantity: ${item.quantity - 1}}, 'update')">-</button>
                <input type="text" value="${item.quantity}">
                <button id="increment-${item.dish_id}" onclick="cartHandler({dish_id: '${item.dish_id}', quantity: ${item.quantity + 1}}, 'update')">+</button>
              </div>
            </td>
            <td>€${item.total}</td>
            <td><button id="remove-${item.dish_id}" class="remove-item" onclick="cartHandler({dish_id: '${item.dish_id}'}, 'delete')">X</button></td>
          </tr>
        `);
      })

      $('#totalPrice span').text(totalPrice);

      if(cartParsed.length) {
        $('#cart-counter').show().text(cartParsed.length);
      } else {
        $('#cart-counter').hide().text(0);
        $('#checkout-button').hide();
      }

      if(action === 'add') {
        $('#addToCart').modal('show');
      }
    };

    const costAddButton = (dish) => {
      let content = null;

      if (currentPage === 'menu') {
        content = `<p class="card-text"><b>€${dish.dish_price}</b></p>`;
      } else {
        content = `
          <p class="card-text"><b>€${dish.dish_price}</b></p>
          <button
            type="button"
            class="btn btn-dark"
            id="addToCartButton-${dish.dish_id}">
            Add +
          </button>
        `;
      }
      return content;
    };

    const renderDishes = () => {
      $('#dishesList').empty();
      let notDishFound = true;
      $.each(dishes, function(index, dish) {
        if(dish.dish_category_id === selectedCategory.cat_id) {
          notDishFound = false;
          $('#dishesList').append(`
            <div class="col-md-6 col-sm-12 dish-container">
              <div class="card menu-item mb-3">
                <div class="row g-0 h-100">
                  <div class="col-md-4 col-sm-12">
                    <div class="image-container">
                      <img src="images/${dish.dish_image}" class="rounded-start" alt="">
                    </div>
                  </div>
                  <div class="col-md-8 col-sm-12">
                    <div class="card-body">
                      <h5 class="card-title">${dish.dish_name}</h5>
                      <div class="inner-separator"></div>
                      <p class="card-text">${dish.dish_content}</p>
                      <div class="cost">
                        ${costAddButton(dish)}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `);

          if (currentPage === 'pickup') {
            $(`#addToCartButton-${dish.dish_id}`).off('click').on('click', () => cartHandler(dish, 'add'));
          }
        }
      });

      if(notDishFound) {
        $('#dishesList').append('<h2>No dish found...</h2>')
      }
    };

    const checkout = () => {
      $('.cart-container').hide();
      $('#checkout').show();
      $('#checkout-button').hide();
      $('#order-proceed-button').show();

      $('#orderDetails').html(localStorage.getItem('cart'))
    }

    const onClickProceed = () => {
      $('button[name="order_proceed_button"]').click();
    }

    renderCategories();
  </script>
<?php include "includes/footer.php" ?>