<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
<?php include "includes/navigation_innerpage.php" ?>
		</div>
	</div>
	<div id="content" class="inner_content">
		<div>
				<ul>
					<?php 
						if(isset($_GET['p_id'])){
	                        $the_cake_id = $_GET['p_id'];
	                    }

	                    $query = "SELECT * FROM cakes WHERE cake_id = $the_cake_id ";
	                    $select_query_for_post = mysqli_query($connection,$query);

	                    while($row = mysqli_fetch_assoc($select_query_for_post)){
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
	                        if($cake_status == 'active'){
	                ?>
	                <h1><?php echo $cake_name ?></h1>
					<li>
						<div>
							<div>
								<h2><a href="post.php?p_id=<?php echo $cake_id; ?>"><?php echo $cake_name ?></a></h2>
								<p><?php echo $cake_content ?></p>
								<a href="product.php" class="view">view all</a>
							</div>
							<a href="post.php?p_id=<?php echo $cake_id; ?>"><img src="images/<?php echo $cake_image ?>" alt="Image" /></a>
						</div>
					</li>
				<?php } }?>
			</ul>
		</div>
	</div>
<?php include "includes/footer.php" ?>