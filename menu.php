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

  <div id="secondary-menu" class="secondary-menu">
    <div class="container hide-scrollbar" style="overflow: auto;">
<!--      <ul id="categoriesList" class="hide-scrollbar" translate="no"></ul>-->

      <div class="menu-wrapper" translate="no">
        <ul class="menu-item"></ul>
        <span class="pointer left-pointer dis">
          <i class="bi bi-arrow-left"></i>
        </span>
        <span class="pointer right-pointer">
          <i class="bi bi-arrow-right"></i>
        </span>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="inner-container" style="margin-bottom: 14rem;">
      <h1 id="menu-heading" translate="no">Loading...</h1>
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
              $smtp_host = 'goldenarm-restaurant.de';
              $smtp_port = 587;
              $smtp_username = 'info@goldenarm-restaurant.de';
              $smtp_password = 'GNGaCZRl6y]0';
              $from_email = 'info@goldenarm-restaurant.de';
              $subject = 'Bestellung Anfrage# '. $order_id;

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
                <p>Regards,<br>Goldenarm Restaurant</p>
              ";

              $customer_text = "
                <p>Lieber $firstName $lastName,</p>
                <p>vielen Dank für Ihre Bestellung! Hier sind die Details:</p>
                <div style='border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;'>
                    $itemDetails
                    <strong>Total Price: \$$totalPrice</strong>
                </div>
                <p>Ihre Bestellung wurde erfolgreich aufgegeben, Sie werden in Kürze eine Bestätigung erhalten.</p>
                <p>Vielen Dank, dass Sie unser Restaurant gewählt haben!</p>
                <p>Vielen herzlichen Dank!<br>Goldenarm Restaurant</p>
              ";

              foreach ($emails_list as $email_data) {
                  $mail = new PHPMailer();
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

                      $mail->Subject = $subject;
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

          echo "<script>localStorage.clear()</script>";
          echo "<script>window.location = '/home'</script>";
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
          <h4 id="totalPrice" class="total-price"><strong>Total: </strong><span>€0</span></h4>
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
    let categories = <?php echo json_encode(isset($categories) && !empty($categories) ? $categories : []); ?>;
    let dishes = <?php echo json_encode(isset($dishes) && !empty($dishes) ? $dishes : []); ?>;
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
      $('.menu-wrapper .menu-item').empty();

      $.each(categories, function(index, category) {
        const categoryClass = markSelectedCategory(category);
        const $li = $('<li>')
          .addClass(categoryClass)
          .addClass('sub-menu-item')
          .text(category.cat_title)
          .on('click', function() {
            onClickCategory(category);
          });

        $('#categoriesList').append($li);
      });

      $.each(categories, function(index, category) {
        const categoryClass = markSelectedCategory(category);
        const $li = $('<li>')
          .addClass(categoryClass)
          .addClass('sub-menu-item')
          .text(category.cat_title)
          .on('click', function() {
            onClickCategory(category);
          });

        $('.menu-wrapper .menu-item').append($li);
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
              total: parseFloat(parseFloat((dish.dish_price * (item.quantity + 1)) || 0).toFixed(2))
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

        totalPrice = parseFloat(parseFloat(totalPrice || 0).toFixed(2));

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
                total: parseFloat(parseFloat((item.dish_price * dish.quantity) || 0).toFixed(2))
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

        totalPrice = parseFloat(parseFloat(totalPrice || 0).toFixed(2));

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

        totalPrice = parseFloat(parseFloat(totalPrice || 0).toFixed(2));

        localStorage.setItem('cart', JSON.stringify({ "items": cartParsed, "totalPrice": totalPrice }))
      }

      $('.cart-container tbody').empty()

      cartParsed.forEach(item => {
        $('.cart-container tbody').append(`
          <tr>
            <td>
              <img src="${item.dish_image ? `images/${item.dish_image}` : 'assets/img/placeholder-square.jpg'}" alt="${item.dish_name}" class="product-image">
              <span translate="no">${item.dish_name}</span>
            </td>
            <td>€${item.dish_price}</td>
            <td>
              <div class="quantity-selector">
                <button id="decrement-${item.dish_id}" onclick="cartHandler({dish_id: '${item.dish_id}', quantity: ${item.quantity - 1}}, 'update')">-</button>
                <input type="text" value="${item.quantity}">
                <button id="increment-${item.dish_id}" onclick="cartHandler({dish_id: '${item.dish_id}', quantity: ${item.quantity + 1}}, 'update')">+</button>
              </div>
            </td>
            <td>€${parseFloat(parseFloat(item.total || 0).toFixed(2))}</td>
            <td><button id="remove-${item.dish_id}" class="remove-item" onclick="cartHandler({dish_id: '${item.dish_id}'}, 'delete')">X</button></td>
          </tr>
        `);
      })

      $('#totalPrice span').text(`€${totalPrice}`);

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
                      <img src="${dish.dish_image ? `images/${dish.dish_image}` : 'assets/img/placeholder-square.jpg'}" class="rounded-start" alt="">
                    </div>
                  </div>
                  <div class="col-md-8 col-sm-12">
                    <div class="card-body">
                      <h5 translate="no" class="card-title" title="${dish.dish_name}">${dish.dish_name}</h5>
                      <div class="inner-separator"></div>
                      <p class="card-text" title="${dish.dish_content}">${dish.dish_content}</p>
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


    var lp, rp, mItems, menu, sc, pos;
    lp = $(".left-pointer");
    rp = $(".right-pointer");
    mItems = $(".menu-item");
    // var tItemsWidth = 0;
    // mItems.find("a").each(function(){
    //   tItemsWidth += $(this).outerWidth(true);
    // });
    // $(".menu-item a").click(function(){
    //   $(".menu-item a").removeclass('active');
    //   $(this).addclass('active');
    // });

    lp.click(function(){
      sc = mItems.width() - 60;
      pos = mItems.scrollLeft() - sc;
      mItems.animate({'scrollLeft': pos}, 'slow');
    });
    rp.click(function(){
      sc = mItems.width() - 60;
      pos = mItems.scrollLeft() + sc;
      mItems.animate({'scrollLeft': pos}, 'slow');
    });
    var scrollLeftPrev = 0;
    mItems.scroll(function(){
      var newScrollLeft = mItems.scrollLeft(),width=mItems.width(),
        scrollWidth=mItems.get(0).scrollWidth;
      var offset=8;
      console.log(scrollWidth - newScrollLeft - width);
      if (scrollWidth - newScrollLeft - width < offset) {
        console.log('right end');
        $(".right-pointer").addClass("dis");
      }else{
        $(".right-pointer").removeClass("dis");
      }
      if( $(this).scrollLeft() == 0){
        $(".left-pointer").addClass("dis");
      }else{
        $(".left-pointer").removeClass("dis");
      }
      scrollLeftPrev = newScrollLeft;
    });

    const slider1 = document.querySelector('.menu-item');
    let isDown = false;
    let startX;
    let scrollLeft;
    slider1.addEventListener('mousedown', (e) => {
      isDown = true;
      slider1.classList.add('active');
      startX = e.pageX - slider1.offsetLeft;
      scrollLeft = slider1.scrollLeft;
    });
    slider1.addEventListener('mouseleave', () => {
      isDown = false;
      slider1.classList.remove('active');
    });
    slider1.addEventListener('mouseup', () => {
      isDown = false;
      slider1.classList.remove('active');
    });
    slider1.addEventListener('mousemove', (e) => {
      if(!isDown) return;
      e.preventDefault();
      const x = e.pageX - slider1.offsetLeft;
      const walk = (x - startX) * 3; //scroll-fast
      slider1.scrollLeft = scrollLeft - walk;
    });

  </script>
<?php include "includes/footer.php" ?>