<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
require 'php-includes/connect.php';
$newamount=0;
$query = "SELECT * FROM seller WHERE id=1";
$stmt = $db->prepare($query);
$stmt->execute();
$rows = $stmt->fetch(PDO::FETCH_ASSOC);
$balan=$rows['balance'];
$sellerid=$rows['id'];
$query = "SELECT * FROM price";
$stmt = $db->prepare($query);
$stmt->execute();
$rows = $stmt->fetch(PDO::FETCH_ASSOC);
$cprice=$rows['price'];
if(isset($_REQUEST['kwishyuraamount'])){
    $amount = floatval($_REQUEST['kwishyuraamount']);
    //$card = str_replace( " ", "", $_REQUEST['card']);
    $card = $_REQUEST['card'];
    //$query = "SELECT balance,id FROM user WHERE   REPLACE(`card`, '\t', '' ) = ? limit 1";
    $query = "SELECT balance,id FROM user WHERE card = ? limit 1";
    $stmt = $db->prepare($query);
    $stmt->execute(array($card));
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);//print_r($rows);die("");
    $user=$rows['id'];
    if ($stmt->rowCount()>0) {
        if ($amount <= $rows['balance']) {
            $newamount= floatval( $rows['balance'] ?? 0) - $amount;
            $sql ="UPDATE user SET balance = ? WHERE card = ? limit 1";
            $stm = $db->prepare($sql);
            if ($stm->execute(array($newamount, $card))) {
                $query = "SELECT price FROM price limit 1";
                $stmt = $db->prepare($query);
                $stmt->execute();
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $mount = $amount/$rows['price'];
                $sql ="INSERT INTO transactions (credit,user) VALUES (?,?)";
                $stm = $db->prepare($sql);
                $stm->execute(array($amount,$user));
                
                $query = "SELECT balance FROM seller WHERE id = ? limit 1";
                $stmt = $db->prepare($query);
                $stmt->execute(array($sellerid));
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $sebal=$rows['balance'];
                $newselbal=$sebal+$amount;
                $sql ="UPDATE seller SET balance = ? WHERE id = ? limit 1";
                $stm = $db->prepare($sql);
                $stm->execute(array($newselbal,$sellerid));

                $data = array('outml' =>$mount); 
                echo $response = json_encode($data)."\n";
            }
        } else {
            $data = array('outml' => 1 ); 
            echo $response = json_encode($data)."\n";
        }
    }
}
?>