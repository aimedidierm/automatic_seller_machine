<?php
require '../php-includes/connect.php';
require 'php-includes/check-login.php';
if(isset($_POST['send'])){
    $price=$_POST['price'];
    $sql ="UPDATE price SET price = ? WHERE id = '1' limit 1";
    $stm = $db->prepare($sql);
    if ($stm->execute(array($price))) {
        print "<script>alert('Price updated');window.location.assign('price.php')</script>";

    } else {
    print "<script>alert('Fail');window.location.assign('price.php')</script>";
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

    <title>Admin - Price management</title>

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
                    <h1 class="h3 mb-4 text-gray-800">Update price</h1>

                    <div class="row">
                    <div class="row">

<div class="col-lg-12">

    <!-- Circle Buttons -->
    <div class="card shadow mb-6">
        <form method="post">
        <div class="card-header py-6">
            <h6 class="m-0 font-weight-bold text-primary">Enter update price per litter</h6>
        </div>
        <div class="card-body">
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Price:</span>
            <input type="text" style="width:350px;" class="form-control" name="price">
        </div>
        <button type="submit" class="btn btn-facebook btn-block" name="send">Update price</button>
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