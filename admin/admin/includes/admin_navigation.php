<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Goldenarm Admin Portal</a>
    </div>
    <ul class="nav navbar-right top-nav">
        <li><a href="../index.php">Homepage</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-user"></i> <?php echo $_SESSION['username']; ?> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="profile.php"><i class="fa fa-fw fa-user"></i> Profile</a></li>
                <li class="divider"></li>
                <li><a href="../includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
            </ul>
        </li>
    </ul>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li class="active"><a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#post">
                    <i class="fa fa-fw fa-arrows-v"></i> Dishes
                    <i class="fa fa-fw fa-caret-down"></i>
                </a>
                <ul id="post" class="collapse">
                    <li><a href="posts.php">View All Dishes</a></li>
                    <li><a href="posts.php?source=add_dish">Add New Dish</a></li>
                </ul>
            </li>
            <li><a href="./categories.php"><i class="fa fa-fw fa-wrench"></i> Categories</a></li>
            <li><a href="comments.php"><i class="fa fa-fw fa-file"></i> Orders</a></li>
          <li><a href="reservations.php"><i class="fa fa-fw fa-file"></i> Reservations</a></li>
            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#demo">
                    <i class="fa fa-fw fa-arrows-v"></i> Users
                    <i class="fa fa-fw fa-caret-down"></i>
                </a>
                <ul id="demo" class="collapse">
                    <li><a href="users.php">View All Users</a></li>
                    <li><a href="users.php?source=add_user">Add user</a></li>
                </ul>
            </li>
            <li><a href="profile.php"><i class="fa fa-fw fa-dashboard"></i> Profile</a></li>
        </ul>
    </div>
</nav>
