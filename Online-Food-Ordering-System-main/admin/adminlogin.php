<?php
    session_start();

    // Redirect to dashboard if admin is already logged in
    if (isset($_SESSION['aid'])) {
        header('location: admindash.php');
        exit;
    }

    include('../dbcon.php'); // Database connection

    if (isset($_POST['login'])) {
        $uname = $_POST['uname'];
        $pass = $_POST['pass'];

        // Fetch admin from database
        $query = "SELECT * FROM `admin` WHERE `username` = '$uname' AND `password` = '$pass'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['aid'] = $row['id'];
            header('location: admindash.php');
            exit;
        } else {
            echo "<script>alert('Username and Password not match');</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
</head>
<body>

    <div class=" bg-dark pt-3 pb-3">
        <a href="../index.php"><button type="button" class="btn btn-success ml-3" style="float:right;">HOME</button></a>
        <a href="adminsignup.php"><button type="button" class="btn btn-danger mr-3" style="float:left;"><< Back</button></a>
        <h1 class="text-center text-light">Online FOODS</h1>
    </div>

    <div class="mt-5 bg-info container text-center text-white">
        <h1>ADMIN LOGIN</h1>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-light p-4">
                    <form method="post">
                        <div class="form-group">
                            <label for="login_uname">Username</label>
                            <input type="text" class="form-control" id="login_uname" name="uname" required>
                        </div>
                        <div class="form-group">
                            <label for="login_pass">Password</label>
                            <input type="password" class="form-control" id="login_pass" name="pass" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
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
