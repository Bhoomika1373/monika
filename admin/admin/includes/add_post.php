<?php
    if(isset($_POST['create_post'])){
        $dish_name = $_POST['dish_name'];
        $dish_category_id = $_POST['dish_category'];
        $dish_price = $_POST['dish_price'];

        $dish_image = $_FILES['dish_image']['name'];
        $dish_image_temp = $_FILES['dish_image']['tmp_name'];

//        $dish_tags = $_POST['dish_tags'];
        $dish_content = $_POST['dish_content'];
        $dish_status = $_POST['dish_status'];
        $dish_date = date('d-m-y');
        //$post_comment_count = 4;

        move_uploaded_file($dish_image_temp, "../images/$dish_image");

        $query = "INSERT INTO dishes(dish_category_id,dish_name,dish_price,dish_content,dish_date,dish_status,dish_image)";
        $query .= "VALUE({$dish_category_id},'{$dish_name}','{$dish_price}',
                    '{$dish_content}',now(),'{$dish_status}','{$dish_image}')";
        $add_posts_query = mysqli_query($connection,$query);

        confirm($add_posts_query);
        $the_dish_id = mysqli_insert_id($connection);
        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <strong> Post Added </strong>" . " " . "<a href='../indivitual_product.php?p_id={$the_dish_id}'>View posts</a>
              </div>";
    }
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="dish_name">Name</label>
        <input type="text" name="dish_name" class="form-control">
    </div>
    <div class="form-group">
        <label for="dish_price">Price</label>
        <input type="text" name="dish_price" class="form-control">
    </div>
    <div class="form-group">
      <label for="dish_category">Category</label>
      <select class="form-control" name="dish_category" id="">
          <?php
              $query = "SELECT * FROM categories";
              $select_categories = mysqli_query($connection,$query);
              confirm($select_categories);
              while($row = mysqli_fetch_assoc($select_categories)){
                  $cat_id = $row['cat_id'];
                  $cat_title = $row['cat_title'];
                  echo "<option value='$cat_id'>{$cat_title}</option>";
              }
          ?>
      </select>
    </div>
    <div class="form-group">
        <label for="dish_status">Status</label>
        <select class="form-control" name="dish_status">
            <option value="unavailable">Select Option</option>
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
        </select>
    </div>
    <div class="form-group">
        <label for="post_image">Image</label>
        <input type="file" name="dish_image" class="form-control">
    </div>
<!--    <div class="form-group">-->
<!--        <label for="post_tags">Tags</label>-->
<!--        <input type="text" name="dish_tags" class="form-control">-->
<!--    </div>-->
    <div class="form-group">
      <label for="post_content">Content</label>
      <input type="text" name="dish_content" class="form-control">
<!--      <textarea class="form-control" name="dish_content" rows="3" placeholder="Enter your content here ... "></textarea>-->
    </div>
    <div class="form-group">
        <input type="submit" name="create_post" value="Publish Post" class="btn btn-success">
    </div>
</form>