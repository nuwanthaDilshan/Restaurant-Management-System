<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Update Profile
if (isset($_POST['ChangeProfile'])) {
    $staff_id = $_SESSION['staff_id'];
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $Qry = "UPDATE rpos_staff SET staff_name =?, staff_email =? WHERE staff_id =?";
    $postStmt = $mysqli->prepare($Qry);
    // Bind parameters
    $rc = $postStmt->bind_param('ssi', $staff_name, $staff_email, $staff_id);
    $postStmt->execute();
    // Declare a variable which will be passed to alert function
    if ($postStmt) {
        $success = "Account Updated" && header("refresh:1; url=dashboard.php");
    } else {
        $err = "Please Try Again Or Try Later";
    }
}

// Change Password
if (isset($_POST['changePassword'])) {
    // Initialize error variable
    $error = 0;
    
    // Validate old password
    if (isset($_POST['old_password']) && !empty($_POST['old_password'])) {
        $old_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['old_password']))));
    } else {
        $error = 1;
        $err = "Old Password Cannot Be Empty";
    }
    
    // Validate new password
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['new_password']))));
    } else {
        $error = 1;
        $err = "New Password Cannot Be Empty";
    }
    
    // Validate confirmation password
    if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
        $confirm_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['confirm_password']))));
    } else {
        $error = 1;
        $err = "Confirmation Password Cannot Be Empty";
    }

    // Proceed if there are no errors
    if (!$error) {
        $staff_id = $_SESSION['staff_id'];
        $sql = "SELECT * FROM rpos_staff WHERE staff_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $staff_id);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            // Check if old password matches
            if ($old_password != $row['staff_password']) {
                $err = "Please Enter Correct Old Password";
            } elseif ($new_password != $confirm_password) {
                $err = "Confirmation Password Does Not Match";
            } else {
                $new_password = sha1(md5($_POST['new_password']));
                // Update password in the database
                $query = "UPDATE rpos_staff SET staff_password = ? WHERE staff_id = ?";
                $stmt = $mysqli->prepare($query);
                $rc = $stmt->bind_param('si', $new_password, $staff_id);
                $stmt->execute();
                
                // Check if the update was successful
                if ($stmt) {
                    $success = "Password Changed" && header("refresh:1; url=dashboard.php");
                } else {
                    $err = "Please Try Again Or Try Later";
                }
            }
        }
    }
}
require_once('partials/_head.php');
?>

<body>
    <!-- Sidenav -->
    <?php
// Include the top navigation bar file, which likely contains the HTML and PHP code for the top navigation of the page.
require_once('partials/_topnav.php');

// Retrieve the staff ID from the current session. The session variable 'staff_id' is expected to have been set during login or when the user was authenticated.
$staff_id = $_SESSION['staff_id'];

// Prepare an SQL query to select all details of a staff member from the 'rpos_staff' table where the staff ID matches the logged-in user's ID.
$ret = "SELECT * FROM rpos_staff WHERE staff_id = ?";

// Prepare the SQL statement using the mysqli connection. This helps prevent SQL injection by separating SQL logic from data.
$stmt = $mysqli->prepare($ret);

// Bind the staff ID to the SQL query. The 'i' indicates that the parameter is an integer. This binds the value of `$staff_id` to the `?` placeholder in the SQL query.
$stmt->bind_param('i', $staff_id);

// Execute the prepared SQL statement. This runs the query against the database with the bound parameter.
$stmt->execute();

// Get the result set from the executed query. This retrieves the results of the query, which can be iterated over.
$res = $stmt->get_result();

// Fetch each row as an object in a loop. This allows you to access the staff member's details through object properties (e.g., `$staff->staff_name`, `$staff->staff_email`, etc.).
while ($staff = $res->fetch_object()) {
?>

<!-- At this point, you would typically output HTML or more PHP code inside the loop to display the staff details -->

<?php
// Closing the while loop and PHP block.
?>

        
            <!-- Header -->
            <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 600px; background-image: url(../admin/assets/img/theme/restro00.jpg); background-size: cover; background-position: center top;">
                <!-- Mask -->
                <span class="mask bg-gradient-default opacity-8"></span>
                <!-- Header container -->
                <div class="container-fluid d-flex align-items-center">
                    <div class="row">
                        <div class="col-lg-7 col-md-10">
                            <h1 class="display-2 text-white">Hello <?php echo $staff->staff_name; ?></h1>
                            <p class="text-white mt-0 mb-5">This is your profile page. You can customize your profile as you want And also change password too</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page content -->
            <div class="container-fluid mt--8">
                <div class="row">
                    <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                        <div class="card card-profile shadow">
                            <div class="row justify-content-center">
                                <div class="col-lg-3 order-lg-2">
                                    <div class="card-profile-image">
                                        <a href="#">
                                            <img src="../admin/assets/img/theme/user-a-min.png" class="rounded-circle">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                                <div class="d-flex justify-content-between">
                                </div>
                            </div>
                            <div class="card-body pt-0 pt-md-4">
                                <div class="row">
                                    <div class="col">
                                        <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                            <div>
                                            </div>
                                            <div>
                                            </div>
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3>
                                        <?php echo $staff->staff_name; ?></span>
                                    </h3>
                                    <div class="h5 font-weight-300">
                                        <i class="ni location_pin mr-2"></i><?php echo $staff->staff_email; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 order-xl-1">
                        <div class="card bg-secondary shadow">
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0">My account</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <h6 class="heading-small text-muted mb-4">User information</h6>
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-username">User Name</label>
                                                    <input type="text" name="staff_name" value="<?php echo $staff->staff_name; ?>" id="input-username" class="form-control form-control-alternative">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-email">Email address</label>
                                                    <input type="email" id="input-email" value="<?php echo $staff->staff_email; ?>" name="staff_email" class="form-control form-control-alternative">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="submit" id="input-email" name="ChangeProfile" class="btn btn-success form-control-alternative" value="Submit">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <form method="post">
                                    <h6 class="heading-small text-muted mb-4">Change Password</h6>
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-username">Old Password</label>
                                                    <input type="password" name="old_password" id="input-username" class="form-control form-control-alternative">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-email">New Password</label>
                                                    <input type="password" name="new_password" class="form-control form-control-alternative">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-email">Confirm New Password</label>
                                                    <input type="password" name="confirm_password" class="form-control form-control-alternative">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="submit" id="input-email" name="changePassword" class="btn btn-success form-control-alternative" value="Change Password">
                                                </div>
                                            </div>
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
        }
            ?>
            </div>
    </div>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_sidebar.php');
    ?>
</body>
</html>
