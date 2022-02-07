<?php
//login
require("./connect.php");
$data = json_decode(file_get_contents("php://input"),true);
$p = false;


// $referral = $con->real_escape_string($data['referral']);
$request = $con->real_escape_string($data['request']);

if($request == 'getOrder'){
  $id = $con->real_escape_string($data['id']);

  $myOrders = $con->query("SELECT `id`, `itemName`,`quantity`,`price` FROM order_items WHERE `order_id` = '{$id}' LIMIT 3");

  $list = array();
$i = 0;
  while($row = $myOrders->fetch_assoc()){
  $i++;
  $row['number'] = $i;
    array_push($list,$row);
  }

}

if($request == 'getMyOrders'){
  $email = $con->real_escape_string($data['email']);
  $list = array();

  $query = $con->query("SELECT * FROM orders WHERE  `email` = '{$email}' ORDER BY `date` DESC");

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


if($request == 'addOrder'){

  $email = $con->real_escape_string($data['email']);
  $department = $con->real_escape_string($data['department']);
  $occupation = $con->real_escape_string($data['occupation']);
  $fullName = $con->real_escape_string($data['fullName']);

  if($email && $department && $occupation && $fullName){

      //just an object... I dont know how to create one in php
      $obj = $con->query("SELECT `id` FROM orders LIMIT 1")->fetch_assoc();


      //check whrther the current user has made any orders before
      $counter = $con->query("SELECT * FROM orders WHERE `email` = '{$email}'")->num_rows;

      if($counter < 1){

          //add new order if none exists
          $insert = $con->query("INSERT INTO orders (`email`,`department`,`occupation`,`fullName`,`selected`)
          VALUES ('{$email}','{$department}','{$occupation}','{$fullName}','Yes')");

          if($insert == 1){

            $idArrray = $con->query("SELECT `id` FROM orders WHERE `email` = '{$email}' && `selected` = 'Yes'")->fetch_assoc();
            $myId = $idArrray['id'];
              $obj['id'] = $myId;
              $obj['Success'] = true;

              $p = $obj;
      }

  }else{

      //if an order already exists
      $query = $con->query("SELECT * FROM orders WHERE `email` = '{$email}' && `sent` = 'No' LIMIT 1");
      $counter2 = $query->num_rows;

      if($counter2 < 1){

          //if there are no rows where sent = No
          $updater = $con->query("UPDATE orders SET `selected` = 'No' WHERE `email` = '{$email}' && `selected` = 'Yes'");
          $insert = $con->query("INSERT INTO orders (`email`,`department`,`occupation`,`fullName`,`selected`)
          VALUES ('{$email}','{$department}','{$occupation}','{$fullName}','Yes')");

          if($insert == 1){
            $idArrray = $con->query("SELECT `id` FROM orders WHERE `email` = '{$email}' && `selected` = 'Yes'")->fetch_assoc();
            $myId = $idArrray['id'];
              $obj['id'] = $myId;
              $obj['Success'] = true;

              $p = $obj;
      }


      } else{

          $assoc = $query->fetch_assoc();
          $id = $assoc['id'];

          $updater = $con->query("UPDATE orders SET `selected` = 'No' WHERE `email` = '{$email}' && `selected` = 'Yes'");
          $updater2 = $con->query("UPDATE orders SET `selected` = 'Yes' WHERE `id` = '{$id}'");

          if($updater2 ==1){
              $obj['id'] = $id;
              $obj['Success'] = true;

              $p = $obj;
          }

      }

  }


}
}

if($request == "order_items"){

    $id = $con->real_escape_string($data['id']);
    $email = $con->real_escape_string($data['email']);

    $order = $con->query("SELECT `sent` FROM orders WHERE `id` = '{$id}'")->fetch_assoc();


    $list = array();
    $checkQuery = $con->query("SELECT * FROM order_items WHERE `order_id` = '{$id}' LIMIT 1");
    $count = $checkQuery->num_rows;
    if($count > 0){
      $check = $checkQuery->fetch_assoc();

      $storedEmail = $check['email'];

    $query = $con->query("SELECT * FROM order_items WHERE `order_id` = '{$id}'");
    $i=1;
    // $fetch = $query->fetch_assoc();
    // $storedEmail = $fetch['email'];
    if($email === $storedEmail){
      while($row = $query->fetch_assoc()){
        $storedEmail = $row['email'];
        if($email === $storedEmail){
          $row['no'] = $i;
        array_push($list, $row);
        $i++;
        }
      }

      $order['items'] = $list;
      $p = $order;

    } else{
      $p = "Unauthorized";
    }

    } else{
      $order['items'] = $list;
      $p = $order;
    }

}

if($request == "view_order"){

  $id = $con->real_escape_string($data['id']);
  $email = $con->real_escape_string($data['email']);
  $department = $con->real_escape_string($data['department']);

  $orderQuery = $con->query("SELECT * FROM orders WHERE `id` = '{$id}'")->fetch_assoc();

  $itemsQuery = $con->query("SELECT `itemName`,`quantity` FROM order_items WHERE `order_id` = '{$id}'");

  $i = 1;
  $list = array();

  while($row = $itemsQuery->fetch_assoc()){
    $row['no'] = $i;
    array_push($list, $row);
    $i++;
  }
$orderQuery['items'] = $list;

//get phone no
$storedEmail = $orderQuery['email'];
$phoneQuery = $con->query("SELECT `phone` FROM users WHERE `email` = '{$storedEmail}'")->fetch_assoc();
$phone = $phoneQuery['phone'];

$orderQuery['phone'] = $phone;


$p = $orderQuery;


}



if($request == "makeOrder"){

  $itemName = $con->real_escape_string($data['itemName']);
  $quantity = $con->real_escape_string($data['quantity']);
  $email = $con->real_escape_string($data['email']);
  $department = $con->real_escape_string($data['department']);
  $occupation = $con->real_escape_string($data['occupation']);
  $fullName = $con->real_escape_string($data['fullName']);

  $selectedArray = $con->query("SELECT `id` FROM orders WHERE `email` = '{$email}' && `selected` = 'Yes'")->fetch_assoc();
  $order_id = $selectedArray['id'];


//check whether there is another item with similar name
  $orderQuery = $con->query("SELECT * FROM order_items WHERE `itemName` = '{$itemName}' && `email` = '{$email}' && `order_id` = '{$order_id}' LIMIT 1" );
  $count = $orderQuery->num_rows;


  //if there is an existing item with the same name from the same user, just update quantity
  if($count > 0){

    $orderAssoc = $orderQuery->fetch_assoc();
    $orderQuantity = $orderAssoc['quantity'];
    $orderID = $orderAssoc['id'];

    $newQuantity = $orderQuantity + $quantity;

    $updater = $con->query("UPDATE order_items SET `quantity` = '{$newQuantity}' WHERE `id` = '{$orderID}'");
    if($updater == 1){
      $p = 'Success';
    } else{
      $p = '158';
    }
        // $p = "You have already added $itemName to your order.";
  } else{
//itemName quantity unitPrice email department occupation fulName
    $updater = $con->query("INSERT INTO order_items (`order_id`,`itemName`,`quantity`,`email`,`department`,`occupation`,`fullName`)
                            VALUES ('{$order_id}','{$itemName}','{$quantity}','{$email}','{$department}','{$occupation}','{$fullName}')");

    if($updater ==1){
      $p='Success';
    } else{
      $p = "$email $quantity $department $fullName $itemName $occupation $order_id";
    }
  }


}


if($request == 'delete'){
  $id = $con->real_escape_string($data['id']);

  $query = $con->query("DELETE FROM order_items WHERE `id` = '{$id}'");

  if($query == 1){
    $p = "Success";
  }
}


if($request =='submitOrder'){
  $email = $con->real_escape_string($data['email']);
  $fullName = $con->real_escape_string($data['fullName']);
  $username = $con->real_escape_string($data['username']);
  $phone = $con->real_escape_string($data['phone']);
  $orderQuery = $con->real_escape_string($data['orderQuery']);

  $query = $con->query("UPDATE orders SET `selected` = 'No', `sent` = 'Yes' WHERE `email` = '{$email}' && `selected` = 'Yes'");

   if($query == 1){

    $myNotification =  $con->query("INSERT INTO notifications(`username`,`title`, `message`) VALUES ('{$username}','Your order has been sent', 'Order number $orderQuery by $fullName has been sent to the appropriate department and will be addressed soon')");

    $accountants = $con->query("SELECT `username` FROM users WHERE `department` = 'Finance'");
  while($row = $accountants->fetch_assoc()){
  $username1 = $row['username'];

   $notify = $con->query("INSERT INTO notifications(`username`,`title`, `message`) VALUES ('{$username1}','$fullName has made order number $orderQuery', '$fullName, phone number: $phone, email: $email, order number $orderQuery')");
  }


   }

}


echo json_encode($p);

?>