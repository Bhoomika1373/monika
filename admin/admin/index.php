<?php include "includes/admin_header.php"; ?>
    <div id="wrapper">
        <?php include "includes/admin_navigation.php"; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Dashboard <small><?php echo $_SESSION['username']; ?></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-birthday-cake fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <?php
                                            $query = "SELECT * FROM dishes";
                                            $select_all_post = mysqli_query($connection, $query);
                                            $post_counts = mysqli_num_rows($select_all_post);
                                            echo "<div class='huge'>{$post_counts}</div>";
                                        ?>
                                        <div>Dishes</div>
                                    </div>
                                </div>
                            </div>
                            <a href="posts.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-shopping-cart fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <?php
                                            $query = "SELECT * FROM pickup_orders";
                                            $select_all_orders = mysqli_query($connection, $query);
                                            $order_counts = mysqli_num_rows($select_all_orders);
                                            echo "<div class='huge'>{$order_counts}</div>";
                                        ?>
                                        <div>Orders</div>
                                    </div>
                                </div>
                            </div>
                            <a href="comments.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-user fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <?php
                                            $query = "SELECT * FROM users";
                                            $select_all_users = mysqli_query($connection, $query);
                                            $user_counts = mysqli_num_rows($select_all_users);
                                            echo "<div class='huge'>{$user_counts}</div>";
                                        ?>
                                        <div>Users</div>
                                    </div>
                                </div>
                            </div>
                            <a href="users.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-bar-chart fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <?php
                                            $query = "SELECT * FROM categories";
                                            $select_all_categories = mysqli_query($connection, $query);
                                            $category_counts = mysqli_num_rows($select_all_categories);
                                            echo "<div class='huge'>{$category_counts}</div>";
                                        ?>
                                        <div>Categories</div>
                                    </div>
                                </div>
                            </div>
                            <a href="categories.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>                <?php
                    $query = "SELECT * FROM dishes WHERE dish_status = 'available'";
                    $select_all_available_cake = mysqli_query($connection, $query);
                    $available_dish_counts = mysqli_num_rows($select_all_available_cake);

                    $query = "SELECT * FROM dishes WHERE dish_status = 'unavailable'";
                    $select_all_unavailable_cake = mysqli_query($connection, $query);
                    $unavailable_dish_counts = mysqli_num_rows($select_all_unavailable_cake);

                    $query = "SELECT * FROM pickup_orders WHERE status = 'Pending'";
                    $select_all_unapproved_orders = mysqli_query($connection, $query);
                    $unapproved_orders_counts = mysqli_num_rows($select_all_unapproved_orders);

                    $query = "SELECT * FROM users WHERE user_role = 'subscriber'";
                    $select_all_subscriber = mysqli_query($connection, $query);
                    $subscriber_counts = mysqli_num_rows($select_all_subscriber);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Area Chart</h3>
                            </div>
                            <div class="panel-body">
                                <script type="text/javascript">
                                    google.charts.load('current', {'packages':['bar']});
                                    google.charts.setOnLoadCallback(drawChart);
                                    function drawChart() {
                                        var data = google.visualization.arrayToDataTable([
                                            ['Data', 'Count'],
                                            <?php
                                                $element_text = ['All dishes', 'Declined', 'Available', 'Orders', 'Pending Orders',
                                                                    'Users', 'Subscriber', 'Categories'];
                                                $element_count = [$post_counts, $unavailable_dish_counts, $available_dish_counts, $order_counts, $unapproved_orders_counts, $user_counts, $subscriber_counts, $category_counts];
                                                for($i = 0; $i < 8; $i++){
                                                    echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
                                                }
                                            ?>
                                        ]);
                                        var options = {
                                            chart: {
                                                title: '',
                                                subtitle: '',
                                            }
                                        };
                                        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
                                        chart.draw(data, options);
                                    }
                                </script>
                                <div id="columnchart_material" style="width: 'auto'; height: 500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div><?php include "includes/admin_footer.php"; ?>

