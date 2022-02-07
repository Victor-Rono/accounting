<?php
require("./connect.php");
$data = json_decode(file_get_contents("php://input"),true);
$p = false;

$request = $con->real_escape_string($data['request']);

if($request =='respond'){
  $id = $con->real_escape_string($data['id']);
  $type = $con->real_escape_string($data['type']);
  $username = $con->real_escape_string($data['username']);
  $email = $con->real_escape_string($data['email']);
  $sender = $con->real_escape_string($data['sender']);

 if($type == 'Yes'){
  $query = $con->query("UPDATE orders SET `accepted` = '{$type}' WHERE `id` = '{$id}'");
 } else if($type =='No'){
  $query = $con->query("UPDATE orders SET `contractor` = NULL WHERE `id` = '{$id}'");
 }





  if($query == 1){

        //notifications
if($type == 'Yes'){
  $msg1 = $con->query("INSERT INTO notifications (`username`,`title`,`message`)
  VALUES ('{$username}','You accepted order number $id','You have accepted to procure the items in order number $id.')");

  $msg2 = $con->query("INSERT INTO notifications (`username`,`title`,`message`)
   VALUES('{$sender}','$username has accepted order number $id','$username has accepted to procure the items in order number $id')");
}

if($type == 'No'){
  $msg1 = $con->query("INSERT INTO notifications (`username`,`title`,`message`)
  VALUES ('{$username}','You rejected order number $id','You have declined to procure the items in order number $id.')");

  $msg2 = $con->query("INSERT INTO notifications (`username`,`title`,`message`)
   VALUES('{$sender}','$username has rejected order number $id','$username has declined order number $id, assign it to another contractor')");
}

    $p = $type;
  }

}


if($request == 'completeOrder'){
  $id = $con->real_escape_string($data['id']);
  // $type = $con->real_escape_string($data['type']);
  $username = $con->real_escape_string($data['username']);
  $email = $con->real_escape_string($data['email']);
  $sender = $con->real_escape_string($data['sender']);

    $query = $con->query("UPDATE orders SET `completed` = 'Yes' WHERE `id` = '{$id}'");

    if($query ==1){

      $msg1 = $con->query("INSERT INTO notifications (`username`,`title`,`message`)
      VALUES ('{$username}','You marked an order as complete','You have marked order number $id as complete. Deliver the items to the appropriate department')");

      $msg2 = $con->query("INSERT INTO notifications (`username`,`title`,`message`)
       VALUES('{$sender}','$username has marked order number $id as complete','$username should deliver the items on the order to the appropriate department')");

      $p = 'Success';
    }

 }

echo json_encode($p);
?>