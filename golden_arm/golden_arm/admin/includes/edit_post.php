<?php
    if(isset($_GET['p_id'])){
        $the_dish_id =  $_GET['p_id'];
    }

    $query = "SELECT * FROM dishes WHERE dish_id = $the_dish_id";
    $select_posts_by_id = mysqli_query($connection,$query);

    while($row = mysqli_fetch_assoc($select_posts_by_id)){
        $dish_name = $row['dish_name'];
        $dish_category = $row['dish_category_id'];
        $dish_weight = $row['dish_weight'];
        $dish_price = $row['dish_price'];
        $dish_image = $row['dish_image'];
        $dish_tags = $row['dish_tags'];
        $dish_content = $row['dish_content'];
        $dish_status = $row['dish_status'];
        $dish_date = $row['dish_date'];
        $dish_order_count = $row['dish_order_count'];
    }
    if(isset($_POST['update_post'])){
        $dish_name = $_POST['dish_name'];
        $dish_category_id = $_POST['dish_category'];
        $dish_weight = $_POST['dish_weight'];
        $dish_price = $_POST['dish_price'];
        $dish_status = $_POST['dish_status'];

        $dish_image = $_FILES['dish_image']['name'];
        $dish_image_temp = $_FILES['dish_image']['tmp_name'];

        $dish_tags = $_POST['dish_tags'];
        $dish_content = $_POST['dish_content'];
        $dish_date = date('d-m-y');

        move_uploaded_file($dish_image_temp, "../images/$dish_image");
        if(empty($dish_image)){
            $query = "SELECT * FROM dishes WHERE dish_id = $the_dish_id";
            $select_posts_by_id = mysqli_query($connection,$query);
            while($row = mysqli_fetch_assoc($select_posts_by_id)){
                $dish_image = $row['dish_image'];
            }
        }

        $query = "UPDATE dishes SET ";
        $query .= "dish_name = '{$dish_name}', ";
        $query .= "dish_category_id = {$dish_category_id}, ";
        $query .= "dish_weight = '{$dish_weight}', ";
        $query .= "post_date = now(), ";
        $query .= "dish_price = '{$dish_price}', ";
        $query .= "dish_status = '{$dish_status}', ";
        $query .= "dish_image = '{$dish_image}', ";
        $query .= "dish_tags = '{$dish_tags}' ";
        $query .= "dish_content = '{$dish_content}' ";
        $query .= "WHERE dish_id = {$the_dish_id} ";

        $update_posts_query = mysqli_query($connection,$query);
        confirm($update_posts_query);

        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <strong> Post Updated </strong>" . " " . "<a href='../post.php?p_id={$the_dish_id}'>View posts</a>
              </div>";
    }
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="post_title">Cake Name</label>
        <input value="<?php echo $dish_name; ?>" type="text" name="dish_name" class="form-control">
    </div>
    <div class="form-group">
        <select class="form-control" name="dish_category_id" id="">
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
        <select class="form-control" name="dish_weight" id="">
            <option value="pound_1">1 Pound</option>
            <option value="pound_2">2 Pound</option>
            <option value="pound_3">3 Pound</option>
            <option value="pound_4">4 Pound</option>
        </select>
    </div>
    <div class="form-group">
        <label for="post_author">Cake Price</label>
        <input value="<?php echo $dish_price; ?>" type="text" name="dish_price" class="form-control">
    </div>
    <div class="form-group">
        <label for="dish_status">Status</label>
        <!-- <input value="<?php $post_status; ?>" type="text" name="post_status" class="form-control"> -->
        <select class="form-control" name="dish_status" id="">
            <option value="<?php echo $dish_status; ?>"><?php echo $dish_status; ?></option>
            <?php 
                if($dish_status == 'available'){
                    echo "<option value='unavailable'>Unavailable</option>";
                }else{
                    echo "<option value='available'>Available</option>";
                }
            ?>
        </select>
        </select>
    </div>
    <div class="form-group">
        <label for="post_image">Image</label>
        <img src="../images/<?php echo $dish_image; ?>" width="100px">
        <input type="file" name="dish_image" class="form-control">
    </div>
    <div class="form-group">
        <label for="post_tags">Tags</label>
        <input value="<?php echo $dish_tags; ?>" type="text" name="dish_tags" class="form-control">
    </div>
    <div class="form-group">
        <label for="post_content">Content</label>
        <textarea class="form-control" name="dish_content" rows="3" placeholder="Enter your content here ... "><?php echo $dish_content; ?></textarea>
    </div>
    <div class="form-group">
        <input type="submit" name="create_post" value="Publish Post" class="btn btn-success">
    </div>
</form>