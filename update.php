<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
require 'php-includes/connect.php';
$sellerid=1;
if(isset($_POST['kwishyura'])){
    $card = $_POST['kwishyura'];
    $amount = $_POST['amount'];
    //$card = '1';
    //$amount = 1000;
    $query = "SELECT balance,id FROM user WHERE card = ? limit 1";
    $stmt = $db->prepare($query);
    $stmt->execute(array($card));
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $user=$rows['id'];
    if ($stmt->rowCount()>0) {
        if ($amount <= $rows['balance']) {
            $newamount=$rows['balance']-$amount;
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
                echo $response = json_encode($data);
            }
        } else {
            $data = array('outml' =>'0'); 
            echo $response = json_encode($data);
        }
    }
}
if(isset($_POST['kwiyabonesha'])){
    $card = $_POST['kwiyabonesha'];
    $amount = $_POST['amount'];
    //$card = '1';
    //$amount = 100;
    $query = "SELECT id FROM user WHERE card = ? limit 1";
    $stmt = $db->prepare($query);
    $stmt->execute(array($card));
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $user=$rows['id'];
    $query = "SELECT * FROM sub WHERE user = ? limit 1";
    $stmt = $db->prepare($query);
    $stmt->execute(array($user));
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($stmt->rowCount()>0) {
        if ($amount <= $rows['amount']) {
            $newamount=$rows['amount']-$amount;
            $sql ="UPDATE sub SET amount = ? WHERE user = ? limit 1";
            $stm = $db->prepare($sql);
            if ($stm->execute(array($newamount, $user))) {
                $data = array('outml' =>$amount); 
                echo $response = json_encode($data);
            }
        } else {
            $data = array('outml' =>'0'); 
            echo $response = json_encode($data);
        }
    }
}
if(isset($_POST['phone'])){
    //$phone = $_POST['phone'];
    //$amount = $_POST['amount'];
    $phone = 250788750979;
    $amount = 100;
    
}
?>