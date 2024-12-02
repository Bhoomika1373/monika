<?php
    if(isset($_POST['checkBoxArray'])){
        foreach($_POST['checkBoxArray'] as $postValueId){
            $bulk_options = $_POST['bulk_options'];
            switch($bulk_options){
                case 'available':
                    $query = "UPDATE dishes SET dish_status = '{$bulk_options}' WHERE dish_id = {$postValueId} ";
                    $update_bulk_published = mysqli_query($connection,$query);
                    confirm($update_bulk_published);    
                    break;

                case 'unavailable':
                    $query = "UPDATE dishes SET dish_status = '{$bulk_options}' WHERE dish_id = {$postValueId} ";
                    $update_bulk_draft = mysqli_query($connection,$query);
                    confirm($update_bulk_draft);    
                    break;

                case 'delete':
                    $query = "DELETE FROM dishes WHERE dish_id = {$postValueId} ";
                    $delete_bulk_post = mysqli_query($connection,$query);
                    confirm($delete_bulk_post);    
                    header("Location: posts.php");
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }
?>
<form method="post" action="">
    <div class="row">
        <div id="bulkOptionsContainer" class="col-xs-6 form-group">
            <select class="form-control" name="bulk_options" id="">
                <option value="">Select Option</option>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
                <option value="delete">Delete</option>
            </select>
        </div>
        <div class="col-xs-6 form-group">
            <input class="btn btn-success" type="submit" name="submit" value="Apply"></input>
            <a href="dishes?source=add_dish" class="btn btn-primary">Add New</a>
        </div>
        </div>
    <table class="table table-striped table-responsive">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAllBoxes" name=""></th>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Images</th>
                <th>Content</th>
                <th>Tags</th>
                <th>Orders</th>
                <th>Date</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $query = "SELECT * FROM dishes";
                $select_posts = mysqli_query($connection,$query);

                while($row = mysqli_fetch_assoc($select_posts)){
                    $dish_id = $row['dish_id'];
                    $dish_name = $row['dish_name'];
                    $dish_category_id = $row['dish_category_id'];
                    $dish_price = $row['dish_price'];
                    $dish_status = $row['dish_status'];
                    $dish_image = $row['dish_image'];
                    $dish_content = $row['dish_content'];
                    $dish_tags = $row['dish_tags'];
                    $dish_order_count = $row['dish_order_count'];
                    $dish_date = $row['dish_date'];
                    echo "<tr>";
            ?>
                    <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $dish_id; ?>'></td>
            <?php
                    echo "<td>{$dish_id}</td>";
                    echo "<td>{$dish_name}</td>";
                    $query = "SELECT * FROM categories WHERE cat_id = {$dish_category_id}";
                    $select_categories = mysqli_query($connection,$query);
                    while($row = mysqli_fetch_assoc($select_categories)){
                        $cat_id = $row['cat_id'];
                        $cat_title = $row['cat_title'];
                        echo "<td>{$cat_title}</td>";
                    }
                    echo "<td>{$dish_status}</td>";
                    echo "<td><img src='../images/{$dish_image}' alt='{$dish_image}' style='width:100px;' /></td>";
                    echo "<td style='max-height: 83px;overflow-y: scroll;'>{$dish_content}</td>";
                    echo "<td>{$dish_tags}</td>";
                    echo "<td>{$dish_order_count}</td>";
                    echo "<td>{$dish_date}</td>";
                    echo "<td><a href='../post.php?p_id={$dish_id}'>View</a></td>";
                    echo "<td><a href='posts.php?source=edit_post&p_id={$dish_id}'>Edit</a></td>";
                    echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete?');\" href='posts.php?delete={$dish_id}'>Delete</a></td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</form>
<?php
    if(isset($_GET['delete'])){
        $the_dish_id = $_GET['delete'];
        $query = "DELETE FROM dishes WHERE dish_id = {$the_dish_id}"; 
        $delete_query = mysqli_query($connection,$query);
        confirm($delete_query);
        header('Location: posts.php');
    }
?>