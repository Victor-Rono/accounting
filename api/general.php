<?php


//login
require("./connect.php");
$data = json_decode(file_get_contents("php://input"),true);
$p = false;

// $password = $data['password']

// $referral = $con->real_escape_string($data['referral']);
$request = $con->real_escape_string($data['request']);



if($request == 'getNotifications'){

  $username = $con->real_escape_string($data['username']);

  $query = $con->query("SELECT * from notifications WHERE `username` = '{$username}' && `status` = 'Unread' ORDER BY `date` DESC");

  $list = array();
$i = 1;
  while($row = $query->fetch_assoc()){
    $row['no'] = $i;
    array_push($list, $row);
    $i++;
  }
$p = $list;
}

if($request == 'getAllNotifications'){

  $username = $con->real_escape_string($data['username']);

  $query = $con->query("SELECT * from notifications WHERE `username` = '{$username}' ORDER BY `date` DESC");

  $list = array();
$i = 1;
  while($row = $query->fetch_assoc()){
    $row['no'] = $i;
    array_push($list, $row);
    $i++;
  }
$p = $list;
}


if($request == 'readNotifications'){
  $username = $con->real_escape_string($data['username']);
$p = $username;
  $updater = $con->query("UPDATE notifications SET `status` = 'Read' WHERE `username` = '{$username}' ");

  if($updater == 1){
    $p = "Success";
  }
}


if($request =='dashboard'){

  //id sent contractor closed accepted completed
  $query = $con->query("SELECT `id`,`sent`, `contractor`, `closed`, `accepted`, `completed` FROM orders WHERE `sent` = 'Yes'");
  $list  = array();

  while($row = $query->fetch_assoc()){
    array_push($list, $row);
  }

  $p = $list;
}

echo json_encode($p);
?>