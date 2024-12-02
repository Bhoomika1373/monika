<?php include "includes/admin_header.php"; ?>
    <div id="wrapper">
        <?php include "includes/admin_navigation.php"; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Categories</h1>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <?php insert_categories(); ?>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="cat-title">Add Category</label>
                                    <input type="text" name="cat_title" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Add Category">
                                </div>
                            </form>
                            <?php 
                                if(isset($_GET['edit'])){
                                    $cat_id = $_GET['edit'];
                                    include "includes/update_categories.php";
                                }
                            ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category Title</th>
                                        <th style="width: 1px;">Update</th>
                                        <th style="width: 83px;">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        find_all_categories();
                                        delete_categories();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include "includes/admin_footer.php"; ?>

