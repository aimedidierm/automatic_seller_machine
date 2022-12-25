<?php
require '../php-includes/connect.php';
require 'php-includes/check-login.php';
$query = "SELECT * FROM admin WHERE email= ? limit 1";
$stmt = $db->prepare($query);
$stmt->execute(array($_SESSION['email']));
$rows = $stmt->fetch(PDO::FETCH_ASSOC);
if ($stmt->rowCount()>0) {
    $admin_id=$rows['id'];
}
if(isset($_POST['send'])){
    $useemail=$_POST['email'];
    $useramount=$_POST['amount'];
    $query = "SELECT * FROM user WHERE email= ? limit 1";
    $stmt = $db->prepare($query);
    $stmt->execute(array($useemail));
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($stmt->rowCount()>0) {
        $balance=$rows['balance'];
        $userid=$rows['id'];
    $newbalance=$balance+$useramount;
    $sql ="UPDATE user SET balance = ? WHERE id = ? limit 1";
    $stm = $db->prepare($sql);
    if ($stm->execute(array($newbalance,$userid))) {
        $sql ="INSERT INTO transactions (debit,user) VALUES (?,?)";
        $stm = $db->prepare($sql);
        if ($stm->execute(array($useramount,$userid))) {
            print "<script>alert('Comfirmed');window.location.assign('money_to_user.php')</script>";

        } else {
            print "<script>alert('Fail');window.location.assign('money_to_user.php')</script>";
        }
    } else {
    print "<script>alert('Fail');window.location.assign('money_to_user.php')</script>";
}
}else {
    print "<script>alert('User not found');window.location.assign('money_to_user.php')</script>";
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

    <title>Admin - money to user</title>

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
                    <h1 class="h3 mb-4 text-gray-800">Send money to user</h1>

                    <div class="row">
                    <div class="row">

<div class="col-lg-12">

    <!-- Circle Buttons -->
    <div class="card shadow mb-6">
        <form method="post">
        <div class="card-header py-6">
            <h6 class="m-0 font-weight-bold text-primary">Send money to user</h6>
        </div>
        <div class="card-body">
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">User email:</span>
            <input type="text" style="width:350px;" class="form-control" name="email">
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Amount:</span>
            <input type="text" style="width:350px;" class="form-control" name="amount">
        </div>
        <button type="submit" class="btn btn-facebook btn-block" name="send">Send money</button>
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