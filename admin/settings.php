<?php
require '../php-includes/connect.php';
require 'php-includes/check-login.php';
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
$query = "SELECT * FROM admin WHERE email= ? limit 1";
$stmt = $db->prepare($query);
$stmt->execute(array($_SESSION['email']));
$rows = $stmt->fetch(PDO::FETCH_ASSOC);
if ($stmt->rowCount()>0) {
    $names=$rows['names'];
    $email=$rows['email'];
    $address=$rows['address'];
    $phone=$rows['phone'];
}
if(isset($_POST['update'])){
$uaddress=$_POST['address'];
$uphone=$_POST['phone'];
$cpassword=md5($_POST['cpassword']);
$apassword=md5($_POST['apassword']);
if ($apassword == $cpassword){
    if($apassword == $cpassword){
        $sql ="UPDATE admin SET address = ?, phone = ? , password = ? WHERE email = ? limit 1";
        $stm = $db->prepare($sql);
        if ($stm->execute(array($uaddress, $uphone, $cpassword, $_SESSION['email']))) {
            print "<script>alert('your data updated');window.location.assign('settings.php')</script>";

            }
    } else{
        echo "<script>alert('Passwords are not match');window.location.assign('settings.php')</script>";
    }
} else{
    echo "<script>alert('Passwords are not match');window.location.assign('account.php')</script>";
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - settings</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
        <?php require 'php-includes/menu.php';?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Settings</h1>

                    <div class="row">
                    <div class="row">

<div class="col-lg-12">

    <!-- Circle Buttons -->
    <div class="card shadow mb-6">
        <form method="post">
        <div class="card-header py-6">
            <h6 class="m-0 font-weight-bold text-primary">You can update your details</h6>
        </div>
        <div class="card-body">
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Names:</span>
            <input type="text" style="width:350px;" class="form-control" name="names" value="<?php echo $names;?>" disabled>
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Email:</span>
            <input type="text" style="width:350px;" class="form-control" name="email" value="<?php echo $email;?>" disabled>
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Phone:</span>
            <input type="text" style="width:350px;" class="form-control" name="phone" value="<?php echo $phone; ?>">
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Address:</span>
            <input type="text" style="width:350px;" class="form-control" name="address" value="<?php echo $address; ?>">
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Password:</span>
            <input type="password" style="width:350px;" class="form-control" name="apassword" required>
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Confirm password:</span>
            <input type="password" style="width:350px;" class="form-control" name="cpassword" required>
        </div>
        <button type="submit" class="btn btn-facebook btn-block" name="update">Update</button>
        </div>
        </form>
    </div>

</div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->


        </div>
        <!-- End of Content Wrapper -->
        <?php require '../seller/php-includes/footer.php'; ?>
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>