<?php


//login
require("./connect.php");
$data = json_decode(file_get_contents("php://input"),true);
$p = false;

// $password = $data['password']

// $referral = $con->real_escape_string($data['referral']);
$request = $con->real_escape_string($data['request']);

if($request == 'getOrders'){
  $email = $con->real_escape_string($data['email']);
  $list = array();

  $query = $con->query("SELECT * FROM orders WHERE  `sent` = 'Yes' && `status` = 'Unseen' ORDER BY `date` DESC");

  while($row = $query->fetch_assoc()){
    $id = $row['id'];
    $list2 = array();

    $query2 = $con->query("SELECT `itemName`,`quantity` FROM order_items WHERE `order_id` = '{$id}' LIMIT 3");
$i = 1;
    while($row2 = $query2->fetch_assoc()){
      $row2['id'] = $i;
      array_push($list2, $row2);
      $i++;
    }

    $row['order_items'] = $list2;

    array_push($list, $row);
  }
$p = $list;
}


if($request == 'getMyOrders'){
  // $email = $con->real_escape_string($data['email']);
  $username = $con->real_escape_string($data['username']);
  $list = array();

  $query = $con->query("SELECT * FROM orders WHERE  `sent` = 'Yes' && `contractor` = '{$username}' ORDER BY `date` DESC");

  while($row = $query->fetch_assoc()){
    $id = $row['id'];
    $list2 = array();

    $query2 = $con->query("SELECT `itemName`,`quantity` FROM order_items WHERE `order_id` = '{$id}' LIMIT 3");
$i = 1;
    while($row2 = $query2->fetch_assoc()){
      $row2['id'] = $i;
      array_push($list2, $row2);
      $i++;
    }

    $row['order_items'] = $list2;

    array_push($list, $row);
  }
$p = $list;
}

if($request == 'contractors'){
  // $obj = $con->query("SELECT `id` FROM users LIMIT 1")->fetch_assoc();
  $list = array();

  $query = $con->query("SELECT `firstName`, `lastName`, `username`,`phone` FROM users WHERE `occupation` = 'Contractor'");

  while($row = $query->fetch_assoc()){
    $row['fullName'] = $row['firstName'].' '.$row['lastName'];
    $row['label'] = $row['username'];
    array_push($list, $row);

  }

  $p = $list;

}


if($request == 'submitOrder'){

  $id = $con->real_escape_string($data['id']);
  $contractor = $con->real_escape_string($data['contractor']);
  $username = $con->real_escape_string($data['username']);
  $email = $con->real_escape_string($data['email']);
  $fullName = $con->real_escape_string($data['fullName']);

  // $userQuery = $con->query("SELECT `username` FROM users WHERE `email` = '{$email}'")->fetch_assoc();
  // $user = $userQuery['username'];

  $query = $con->query("UPDATE orders SET `contractor` = '{$contractor}', `sender` ='{$username}' WHERE `id` = '{$id}'");

  if($query ==1){
    $msg1 = $con->query("INSERT INTO notifications(`username`, `title`,`message`) VALUES ('{$username}','Order sent to $fullName','You sent order number $id to $fullName, username: $contractor')");

    $msg2 = $con->query("INSERT INTO notifications(`username`, `title`,`message`) VALUES ('{$contractor}','You have a new order, number $id','Please check the order and confirm whether you can fulfill it' )");
    // $msg3 = $con->query("INSERT INTO notifications(`username`, `title`,`message`) VALUES ('{$user}','Your order number $id ')");


  }
  $p = 'Success';
}

echo json_encode($p);
?>