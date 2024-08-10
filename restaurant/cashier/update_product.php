<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

if (isset($_POST['UpdateProduct'])) {
    // Prevent Posting Blank Values
    if (empty($_POST["prod_code"]) || empty($_POST["prod_name"]) || empty($_POST['prod_desc']) || empty($_POST['prod_price'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $update = $_GET['update'];
        $prod_code  = $_POST['prod_code'];
        $prod_name = $_POST['prod_name'];
        $prod_desc = $_POST['prod_desc'];
        $prod_price = $_POST['prod_price'];

        // Handle Product Image Upload
        $prod_img = $_FILES['prod_img']['name'];
        if (!empty($prod_img)) {
            $target_dir = "../admin/assets/img/products/";
            $target_file = $target_dir . basename($prod_img);
            move_uploaded_file($_FILES["prod_img"]["tmp_name"], $target_file);
        } else {
            // If no new image is uploaded, retain the old image
            $prod_img = $_POST['current_img'];
        }

        // Update Product Information in Database
        $postQuery = "UPDATE rpos_products SET prod_code = ?, prod_name = ?, prod_img = ?, prod_desc = ?, prod_price = ? WHERE prod_id = ?";
        $postStmt = $mysqli->prepare($postQuery);
        // Bind parameters
        $rc = $postStmt->bind_param('sssssi', $prod_code, $prod_name, $prod_img, $prod_desc, $prod_price, $update);
        $postStmt->execute();

        // Provide Feedback
        if ($postStmt) {
            $success = "Product Updated";
            header("refresh:1; url=products.php");
        } else {
            $err = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/_head.php');
?>

<body>
    <!-- Sidenav -->
    <?php
    require_once('partials/_sidebar.php');
    ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php
        require_once('partials/_topnav.php');
        $update = $_GET['update'];
        $ret = "SELECT * FROM rpos_products WHERE prod_id = ?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $update);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($prod = $res->fetch_object()) {
        ?>
            <!-- Header -->
            <div style="background-image: url(../assets/img/theme/hotel-1191718_1920.jpg); background-size: cover; background-position: center center;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
                <div class="container-fluid">
                    <div class="header-body"></div>
                </div>
            </div>
            <!-- Page content -->
            <div class="container-fluid mt--8">
                <!-- Table -->
                <div class="row">
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <h3>Please Fill All Fields</h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label>Product Name</label>
                                            <input type="text" value="<?php echo $prod->prod_name; ?>" name="prod_name" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Product Code</label>
                                            <input type="text" name="prod_code" value="<?php echo $prod->prod_code; ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label>Product Image</label>
                                            <input type="file" name="prod_img" class="btn btn-outline-success form-control">
                                            <input type="hidden" name="current_img" value="<?php echo $prod->prod_img; ?>">
                                            <br>
                                            <!-- Display current image -->
                                            <?php if ($prod->prod_img) { ?>
                                                <img src="../admin/assets/img/products/<?php echo $prod->prod_img; ?>" height="60" width="60" class="img-thumbnail">
                                            <?php } else { ?>
                                                <img src="../admin/assets/img/products/default.jpg" height="60" width="60" class="img-thumbnail">
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Product Price</label>
                                            <input type="text" name="prod_price" class="form-control" value="<?php echo $prod->prod_price; ?>" required>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label>Product Description</label>
                                            <textarea rows="5" name="prod_desc" class="form-control" required><?php echo $prod->prod_desc; ?></textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <input type="submit" name="UpdateProduct" value="Update Product" class="btn btn-success">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <?php
                require_once('partials/_footer.php');
                ?>
            </div>
        <?php
        }
        $stmt->close();
        ?>
    </div>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
</body>

</html>
