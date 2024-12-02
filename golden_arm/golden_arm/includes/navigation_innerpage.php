      <nav class="navbar navbar-inverse navbar-fixed-top navbarNew" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./index.php">
                    <img src="./images/logo.gif">
                </a>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav pull-right">
                        <li><a href='signup.php'>My Account |</a></li>
                        <li><a href='admin'>Help |</a></li>
                        <li>
                            <?php
                                if(isset($_SESSION['username'])){
                                    echo "<a href='includes/logout.php'>Sign out</a>";
                                }else{
                                    echo "<a href='signin.php'>Sign in</a>";
                                }
                             ?>
                        </li>
                        <li>
                            <div class="dropdown" style="margin-top: 8px;padding: 0 4px;">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="min-width: 110px;text-align: right;">
                                    <i class="fa fa-user" value=""></i>
                                    <?php
                                        if(isset($_SESSION['username'])){
                                            echo $_SESSION['username'];
                                        }else{
                                            echo "User Profile";
                                        }
                                     ?>
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="pull-right searchbar">
                    <form action="search.php" method="post">
                        <div class="input-group">
                            <input name="search" type="text" class="form-control">
                            <span class="input-group-btn">
                                <button name="submit" class="btn btn-default" type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
             <ul class="header_links">
                <li class="current"><a href="index.php">Home</a></li>
                <li><a href="product.php">The Pastry shop</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            <div class="section">
                <a href="index.php"><img src="images/wedding-cupcakes-small.jpg" alt="Image"/></a>
            </div>
        </div>
    </nav>