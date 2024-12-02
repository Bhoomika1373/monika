<?php
    function confirm($result){
        global $connection;
        if(!$result){
            die("QUERY FAILED ." . mysqli_error($connection));
        }
    }
    
    function insert_categories(){
        global $connection;
        if(isset($_POST['submit'])){
            $cat_title = $_POST['cat_title'];
            if($cat_title == "" || empty($cat_title)){
                echo "<div class='alert alert-danger' style='padding: 2px 15px;' role='alert'>This field should not be empty !!</div>";
            }else{
                $query = "INSERT INTO categories(cat_title) VALUES('{$cat_title}')";
                $create_category = mysqli_query($connection,$query);
                confirm($create_category);
            }
        }
    }

    function find_all_categories(){
        global $connection;
        $query = "SELECT * FROM categories";
        $select_categories_sidebar = mysqli_query($connection,$query);

        while($row = mysqli_fetch_assoc($select_categories_sidebar)){
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];
            echo "<tr>";
            echo "<td>{$cat_id}</td>";
            echo "<td>{$cat_title}</td>";
            echo "<td><a href='categories.php?edit={$cat_id}'><button type='button' class='btn btn-success pull-right'>Update</button></a></td>";
            echo "<td><a href='categories.php?delete={$cat_id}'><button type='button' class='btn btn-danger pull-right'>Delete</button></a></td>";
            echo "</tr>";
        }
    }

    function delete_categories(){
        global $connection;
        if(isset($_GET['delete'])){
            $the_cat_id = $_GET['delete'];
            $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id}";
            $delete_query = mysqli_query($connection,$query); 
            header("Location: categories.php");
        }
    }
?>