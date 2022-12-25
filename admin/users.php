<?php
require '../php-includes/connect.php';
require 'php-includes/check-login.php';
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
if(isset($_POST['save'])){
    $names=$_POST['names'];
    $email=$_POST['email'];
    $card=$_POST['card'];
    $phone=$_POST['phone'];
    $sql ="INSERT INTO user (names,card,email,phone,balance) VALUES (?,?,?,?,'0')";
    $stm = $db->prepare($sql);
    if ($stm->execute(array($names,$card,$email,$phone))) {
        print "<script>alert('User added');window.location.assign('users.php')</script>";

    } else{
        echo "<script>alert('Error! try again');window.location.assign('users.php')</script>";
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

    <title>Admin - users management</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">
        <?php require 'php-includes/menu.php';?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Users management</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">User details</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>N</th>
                                            <th>Names</th>
                                            <th>Email</th>
                                            <th>Card</th>
                                            <th>Phone</th>
                                            <th>Balance</th>
                                            <th>Time</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>N</th>
                                            <th>Names</th>
                                            <th>Email</th>
                                            <th>Card</th>
                                            <th>Phone</th>
                                            <th>Balance</th>
                                            <th>Time</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                        $sql = "SELECT * FROM user";
                                        $stmt = $db->prepare($sql);
                                        $stmt->execute();
                                        if ($stmt->rowCount() > 0) {
                                            $count = 1;
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                        <tr>
                                            <td><?php print $count?></td>
                                            <td><?php print $row['names']?></td>
                                            <td><?php print $row['email']?></td>
                                            <td><?php print $row['card']?></td>
                                            <td><?php print $row['phone']?></td>
                                            <td><?php print $row['balance']?></td>
                                            <td><?php print $row['time']?></td>
                                            <td><form method="post"><button type="submit" class="btn btn-danger" id="<?php echo $row["id"];$sid=$row["id"];?>" name="delete"><span class="glyphicon glyphicon-trash"></span> Delete</button></form></td>
                                        </tr>
                                        <?php
                                        $count++;
                                        }
                                    }
                                    if(isset($_POST['delete'])){
                                    $sql ="DELETE FROM user WHERE id = ?";
                                    $stm = $db->prepare($sql);
                                    if ($stm->execute(array($sid))) {
                                        print "<script>alert('User deleted');window.location.assign('users.php')</script>";
                            
                                    } else {
                                        print "<script>alert('Delete fail');window.location.assign('users.php')</script>";
                                    }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
                

            </div>
            <!-- End of Main Content -->
            <div class="col-lg-8">

    <!-- Circle Buttons -->
    <div class="card shadow mb-6">
        <form method="post">
        <div class="card-header py-6">
            <h6 class="m-0 font-weight-bold text-primary">Add user</h6>
        </div>
        <div class="card-body">
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Names:</span>
            <input type="text" style="width:350px;" class="form-control" name="names">
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Email:</span>
            <input type="email" style="width:350px;" class="form-control" name="email">
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Card ID:</span>
            <input type="card" style="width:350px;" class="form-control" name="card">
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon" style="width:150px;">Phone:</span>
            <input type="text" style="width:350px;" class="form-control" name="phone">
        </div>
        <button type="submit" class="btn btn-facebook btn-block" name="save">Add user</button>
        </div>
        </form>
    </div>

</div>

            <?php require '../seller/php-includes/footer.php'; ?>

        </div>
        <!-- End of Content Wrapper -->

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
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>

</body>

</html>