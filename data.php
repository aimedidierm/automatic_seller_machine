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
//$amountml=(1000/$cprice)*$amount;
if(isset($_REQUEST['kwiyaboneshaamount'])){
    $card = $_REQUEST['card'];
    //$card="123";
    $amount = $_GET['kwiyaboneshaamount'];
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
                echo $response = json_encode($data)."\n";
            }
        } else {
            $data = array('outml' => 1); 
            echo $response = json_encode($data)."\n";
        }
    }
}
if(isset($_REQUEST['kwishyuraamount'])){
    $amount = floatval($_REQUEST['kwishyuraamount']);
    $card = str_replace( " ", "", $_REQUEST['card']);
    $query = "SELECT balance,id FROM user WHERE   REPLACE(`card`, '\t', '' ) = ? limit 1";
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
if(isset($_REQUEST['phone'])&&($_REQUEST['amount'])){
    $phone = $_REQUEST['phone'];
    $amount = $_REQUEST['amount'];
    // die( "didier");
    $user="test";
    $req = '{"amount":'.$amount.',"number":"'.$phone.'"}';
    define('BASE_URL', 'https://payments.paypack.rw/api');
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => BASE_URL . '/transactions/cashin?Idempotency-Key=OldbBsHAwAdcYalKLXuiMcqRrdEcDGRv',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $req,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . getToken(),
        'Content-Type: application/json'
      ),
    ));
    // die("2");

    $response = curl_exec($curl);
    curl_close($curl);
    $amountml=(1000/$cprice)*$amount;
    $sql ="INSERT INTO transactions (debit,seller,user) VALUES (?,?,'1')";
    $stm = $db->prepare($sql);
    if ($stm->execute(array($amount,$sellerid))) {
        $ubalance=$balan+$amount;
        $sql ="UPDATE seller SET balance = ?";
        $stm = $db->prepare($sql);
        $stm->execute(array($ubalance));
        $data = array('outml' =>$amountml); 
        echo $response = json_encode($data)."\n";
    } else {
    $data = array('outml' => 1 ); 
    echo $response = json_encode($data)."\n";
}
}
function getToken() {
    $curl = curl_init();
  
    curl_setopt_array($curl, array(
      CURLOPT_URL => BASE_URL . '/auth/agents/authorize',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{"client_id": "75141ab2-7fc7-11ed-bc8f-dead986dd4f7","client_secret": "ced7e0678592cea0940f180b3c6f9cb7da39a3ee5e6b4b0d3255bfef95601890afd80709"}',
      CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
    ));
  
    $response = curl_exec($curl);
  
    curl_close($curl);
  
    return json_decode($response)->access;
}
?>