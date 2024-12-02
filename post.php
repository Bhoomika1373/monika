<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
<?php ob_start(); ?>
<?php session_start(); ?>
<?php
    if(!isset($_SESSION['user_role'])){
        header("Location: signin.php");
    }
?>
<?php include "includes/navigation_innerpage.php" ?>
        </div>
    </div>
    <div id="content" class="inner_content">
        <div class="row" style="margin: 0px">
            <!-- Blog Entries Column -->
            <div class="col-lg-12 col-md-12 col-sm-12 post_div" style="margin: 0px">
                <?php 
                    $order_name = null;
                    if(isset($_GET['p_id'])){
                        $the_cake_id = $_GET['p_id'];
                    }
                    $query = "SELECT * FROM cakes WHERE cake_id = $the_cake_id ";
                    $select_query_for_cakes = mysqli_query($connection,$query);

                    while($row = mysqli_fetch_assoc($select_query_for_cakes)){
                        $cake_id = $row['cake_id'];
                        $cake_name = $row['cake_name'];
                        $cake_weight = $row['cake_weight'];
                        $cake_category_id = $row['cake_category_id'];
                        $cake_price = $row['cake_price'];
                        $cake_status = $row['cake_status'];
                        $cake_image = $row['cake_image'];
                        $cake_content = $row['cake_content'];
                        $cake_tags = $row['cake_tags'];
                        $cake_order_count = $row['cake_order_count'];
                        $cake_date = $row['cake_date'];
                ?>
                <h2><a href="#"><?php echo $cake_name ?></a></h2>
                <p class="lead">
                    Status <a href="index.php"><?php echo $cake_status ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> <?php echo $cake_date ?></p>
                <p><span class="glyphicon glyphicon-glass"></span> <?php echo $cake_weight ?></p>
                <hr>
                <img class="img-responsive" src="images/<?php echo $cake_image ?>" alt="" style="width: 100% !important;">
                <hr>
                <p><?php echo $cake_content ?></p>
            <br />
            <br />
                <?php } ?>
                <?php
                    if(isset($_POST['create_order'])){
                        $the_cake_id = $_GET['p_id'];
                        $order_content = $_POST['order_content'];

                        $customer_id = $_SESSION['user_id'];
                        $customer_username = $_SESSION['username'];
                        $customer_firstname = $_SESSION['user_firstname'];
                        $customer_lastname = $_SESSION['user_lastname'];
                        $customer_email = $_SESSION['user_email'];
                        $customer_phone = $_SESSION['user_phone'];
                        $customer_address = $_SESSION['user_address'];                      
                        $customer_role = $_SESSION['user_role'];

                        if(!empty($order_content)){
                            $query = "INSERT INTO orders (order_user_id, order_cake_id, order_name, order_email, order_phone, order_address,  order_content, order_status, order_date)";
                            $query .= "VALUES ($customer_id, $the_cake_id, '{$customer_username}', '{$customer_email}', '{$customer_phone}', '{$customer_address}', '{$order_content}', 'unapproved',now())";

                            $create_order_query = mysqli_query($connection, $query);
                            echo "<script>alert('You order is placed !!!')</script>";
                            if(!$create_order_query){
                                die("QUERY FAILED ." . mysqli_error($connection));
                            }

                            $query = "UPDATE cakes SET cake_order_count = cake_order_count + 1 WHERE cake_id = $the_cake_id";
                            $order_count_query = mysqli_query($connection, $query);
                            if(!$order_count_query){
                                die("QUERY FAILED ." . mysqli_error($connection));
                            }
                        }else{
                            echo "<script>alert('Field cannot be empty')</script>";
                        }
                    }
                ?>
                <?php
                    $query = "SELECT * FROM orders WHERE order_cake_id = {$the_cake_id}";
                    $select_approved_order_query = mysqli_query($connection,$query);
                    if(!$select_approved_order_query){
                        die("QUERY FAILED ". mysqli_error($connection));
                    }
                    while ($row = mysqli_fetch_assoc($select_approved_order_query)) {
                        $order_name = $row['order_name'];
                        $order_address = $row['order_address'];
                        $order_phone = $row['order_phone'];
                        $order_email = $row['order_email'];
                        $order_date = $row['order_date'];
                        $order_content = $row['order_content'];
                        $order_status = $row['order_status'];
                    }
                    $customer_username = $_SESSION['username'];
                    if($order_name == $customer_username && $order_status == 'Approved'){
                        echo "<script>alert('Your last order for this cake is approved !! Have a nice day ..')</script>";
                    }
                ?>
                <style type="text/css">
                    #content div {
                        width: 100% !important;
                    }
                </style>
                <div class="well" style="width: 100%;">
                    <h4>Order Now:</h4>
                    <form action="" method="post" role="form">
                        <div class="form-group">
                            <textarea class="form-control" rows="3" name="order_content" placeholder="Enter your description here ..."></textarea>
                        </div>
                        <button type="submit" name="create_order" class="btn btn-primary">Submit</button>
                    </form>
                </div>
               </div>
               </div>
        </div>
    <hr>
<?php include "includes/footer.php" ?>