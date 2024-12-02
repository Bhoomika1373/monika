<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
<?php include "includes/navigation_innerpage.php" ?>
        </div>
    </div>
    <div id="content" class="inner_content">
        <div>
            <h1>Search Result</h1>
                <ul>
                    <?php 
                        if(isset($_POST['submit'])){
                            $search = $_POST['search'];

                            $query = "SELECT * FROM cakes WHERE cake_name LIKE '%$search%'";
                            $search_query = mysqli_query($connection,$query);
                            if(!$search_query){
                                die("SEARCH QUERY FILED" . mysqli_error($connection));
                            }
                            $count = mysqli_num_rows($search_query);
                            if($count == 0){
                                echo "No result Found";
                            }else{
                                while($row = mysqli_fetch_assoc($search_query)){
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
                                    if($cake_status == 'available'){
                            ?>
                    <li>
                        <div>
                            <div class="para_in_post_adjust">
                                <h2><a href="post.php?p_id=<?php echo $cake_id; ?>"><?php echo $cake_name ?></a></h2>
                                <?php echo $cake_content ?>
                                <a href="product.php" class="view">view all</a>
                            </div>
                            <a href="post.php?p_id=<?php echo $cake_id; ?>"><img src="images/<?php echo $cake_image ?>" alt="Image" /></a>
                        </div>
                    </li>
                <?php } } } }?>
            </ul>
        </div>
    </div>
<?php include "includes/footer.php" ?>