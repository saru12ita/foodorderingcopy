<?php
    session_start();

    // Redirect to dashboard if admin is already logged in
    if (isset($_SESSION['aid'])) {
        header('location: admindash.php');
        exit;
    }

    include('../dbcon.php'); // Database connection

    if (isset($_POST['signup'])) {
        $uname = $_POST['uname'];
        $pass = $_POST['pass'];

        // Insert new admin into database
        $query = "INSERT INTO `admin` (`username`, `password`) VALUES ('$uname', '$pass')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect to admin login page after successful signup
            header('location: ../index.php');
            exit;
        } else {
            echo "<script>alert('Failed to register admin');</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Signup</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
</head>
<body>

    <div class=" bg-dark pt-3 pb-3">
        <a href="../Home.php"><button type="button" class="btn btn-success ml-3" style="float:right;">HOME</button></a>
        <a href="loggin.php"><button type="button" class="btn btn-danger mr-3" style="float:left;"><< Back</button></a>
        <h1 class="text-center text-light">Online FOODS</h1>
    </div>

    <div class="mt-5 bg-info container text-center text-white">
        <h1>ADMIN SIGNUP</h1>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-light p-4">
                    <form method="post">
                        <div class="form-group">
                            <label for="signup_uname">New Username</label>
                            <input type="text" class="form-control" id="signup_uname" name="uname" required>
                        </div>
                        <div class="form-group">
                            <label for="signup_pass">New Password</label>
                            <input type="password" class="form-control" id="signup_pass" name="pass" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-block" name="signup">Signup</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="bootstrap/jss/jquery.min.js"></script>
    <script src="bootstrap/jss/popper.min.js"></script>
    <script src="bootstrap/jss/bootstrap.min.js"></script>
</body>
</html>
