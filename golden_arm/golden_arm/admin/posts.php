<?php include "includes/admin_header.php"; ?>
    <div id="wrapper">
        <?php include "includes/admin_navigation.php"; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Dishes</h1>
                        <?php 
                            if(isset($_GET['source'])){
                                $source = $_GET['source'];
                            }else{
                                $source = "";
                            }
                            switch($source){
                                case 'add_dish';
                                include "includes/add_post.php";
                                break;

                                case 'edit_dish';
                                include "includes/edit_post.php";
                                break;

                                case 200;
                                echo "nice 200";
                                break;

                                default:
                                include "includes/view_all_posts.php";
                                break;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include "includes/admin_footer.php"; ?>
