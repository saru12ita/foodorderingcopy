<?php

@include 'config.php';

$message = [];

if (isset($_POST['add_product'])) {
    $p_name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $p_price = mysqli_real_escape_string($conn, $_POST['p_price']);
    $p_image = mysqli_real_escape_string($conn, $_FILES['p_image']['name']);
    $p_image_tmp_name = $_FILES['p_image']['tmp_name'];
    $p_image_folder = 'uploaded_img/' . $p_image;

    if (empty($p_name) || empty($p_price) || empty($p_image)) {
        $message[] = 'All fields are required';
    } else {
        $insert_query = mysqli_query($conn, "INSERT INTO `products` (name, price, image) VALUES ('$p_name', '$p_price', '$p_image')") or die('Insert query failed: ' . mysqli_error($conn));

        if ($insert_query) {
            move_uploaded_file($p_image_tmp_name, $p_image_folder);
            $message[] = 'Product added successfully';
        } else {
            $message[] = 'Could not add the product';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_query = mysqli_query($conn, "DELETE FROM `products` WHERE id = $delete_id ") or die('Delete query failed: ' . mysqli_error($conn));

    if ($delete_query) {
        $message[] = 'Product has been deleted';
        header('location:admin.php');
        exit();
    } else {
        $message[] = 'Product could not be deleted';
    }
}

if (isset($_POST['update_product'])) {
    $update_p_id = mysqli_real_escape_string($conn, $_POST['update_p_id']);
    $update_p_name = mysqli_real_escape_string($conn, $_POST['update_p_name']);
    $update_p_price = mysqli_real_escape_string($conn, $_POST['update_p_price']);
    $update_p_image = mysqli_real_escape_string($conn, $_FILES['update_p_image']['name']);
    $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];
    $update_p_image_folder = 'uploaded_img/' . $update_p_image;

    if (empty($update_p_name) || empty($update_p_price) || empty($update_p_image)) {
        $message[] = 'All fields are required';
    } else {
        $update_query = mysqli_query($conn, "UPDATE `products` SET name = '$update_p_name', price = '$update_p_price', image = '$update_p_image' WHERE id = '$update_p_id'") or die('Update query failed: ' . mysqli_error($conn));

        if ($update_query) {
            move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);
            $message[] = 'Product updated successfully';
            header('location:admin.php');
            exit();
        } else {
            $message[] = 'Product could not be updated';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin panel</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message"><span>' . $msg . '</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
        }
    }
    ?>

    <?php include 'header.php'; ?>

    <div class="container">

        <section>
            <form action="" method="post" class="add-product-form" enctype="multipart/form-data">
                <h3>add a new product</h3>
                <input type="text" name="p_name" placeholder="enter the product name" class="box" required>
                <input type="number" name="p_price" min="0" placeholder="enter the product price" class="box" required>
                <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" class="box" required>
                <input type="submit" value="add the product" name="add_product" class="btn">
            </form>
        </section>

        <section class="display-product-table">
            <table>
                <thead>
                    <th>product image</th>
                    <th>product name</th>
                    <th>product price</th>
                    <th>action</th>
                </thead>
                <tbody>
    <?php
    $select_products = mysqli_query($conn, "SELECT * FROM `products`");

    if (!$select_products) {
        // Handle the error, e.g., display an error message
        echo "Error: " . mysqli_error($conn);
    } else {
        if (mysqli_num_rows($select_products) > 0) {
            while ($row = mysqli_fetch_assoc($select_products)) {
                ?>
                <tr class="table-bottom">
                <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" alt=""></td>
            <td><?php echo $row['name']; ?></td>
            <td>$<?php echo $row['price']; ?>/-</td>
            <td>
    <a href="cart.php?delete_all" onclick="return confirm('Are you sure you want to delete all?');" class="delete-btn"> 
        <i class="fas fa-trash"></i> delete all 
    </a>
    <a href="cart.php?update_all" onclick="return confirm('Are you sure you want to update all?');" class="update-btn"> 
        <i class="fas fa-sync-alt"></i> update all <!-- Assuming you want to use a different icon for update -->
    </a>
</td>

         </tr>
                <?php
            }
        } else {
            echo "<div class='empty'>No product added</div>";
        }
    }
    ?>
</tbody>

</table>
</section>

<section class="edit-form-container">
    <?php
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $edit_id");

        if (mysqli_num_rows($edit_query) > 0) {
            $fetch_edit = mysqli_fetch_assoc($edit_query);
    ?>
            <form action="" method="post" enctype="multipart/form-data">
                <img src="uploaded_img/<?php echo $fetch_edit['image']; ?>" height="200" alt="">
                <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">
                <input type="text" class="box" required name="update_p_name" value="<?php echo $fetch_edit['name']; ?>">
                <input type="number" min="0" class="box" required name="update_p_price" value="<?php echo $fetch_edit['price']; ?>">
                <input type="file" class="box" required name="update_p_image" accept="image/png, image/jpg, image/jpeg">
                <input type="submit" value="Update Product" name="update_product" class="btn">
                <input type="reset" value="Cancel" id="close-edit" class="option-btn">
            </form>
    <?php
        } else {
            echo "<div class='empty'>No product found for editing</div>";
        }
    }
    ?>
</section>

</div>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
