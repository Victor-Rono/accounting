<?php

//login
require("./connect.php");
$data = json_decode(file_get_contents("php://input"),true);
$p = false;

// $password = $data['password']

// $referral = $con->real_escape_string($data['referral']);
$request = $con->real_escape_string($data['request']);


if($request == "online"){
        //insert into users online
    $query = $con->query("SELECT `id` FROM details LIMIT 1")->fetch_assoc();
    $session=session_id();




$time=time();
$time_check=$time-3900;
$email = $con->real_escape_string($data['email']);
$username = $con->real_escape_string($data['username']);

if($username){
    $query['unreadNotifications'] = $con->query("SELECT * FROM msg WHERE `username` = '$username' && `status` = 'Unread'")->num_rows+0;
        $query['unreadMessages'] = $con->query("SELECT * FROM chat WHERE `username` = '$username' && `status` = 'Unread'")->num_rows+0;
}

$rows = $con->query("SELECT * FROM status WHERE `email` = '{$email}'")->num_rows;

    $deleter = $con->query("DELETE FROM status WHERE `email` = '{$email}'");
    $insert = $con->query("INSERT INTO status (`email`, `session`, `time`) VALUES ('{$email}', '{$session}', '{$time}')");
$query['usersOnline'] = $con->query("SELECT * FROM status")->num_rows;
$p = $query;

}





if($request  == "details"){
    $phone = $con->real_escape_string($data['mobile']);
    $query = $con->query("SELECT * FROM users WHERE `phone` = '{$phone}'")->fetch_assoc();
    $query['password'] = false;
    $p = $query;
}




if($request =="login"){
//phone password



$phone = $con->real_escape_string($data['phone']);
// $email = $phone;
$password = $con->real_escape_string($data['password']);

$query = $con->query("SELECT * FROM users WHERE `email` = '$phone' ||  `username` = '$phone' ||  `phone` = '$phone'")->fetch_assoc();

$pwd = $query['password'];
// $p = $password;
// $p = $query;
$check = password_verify($password, $pwd);

$p = $check;

if($check){
    $query["password"] = null;
    $p = $query;



}



}


if($request == "reset"){
    $p= null;
    $email = $con->real_escape_string($data['email']);
    $phone = $con->real_escape_string($data['phone']);
    $password = $con->real_escape_string($data['password']);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $rows = $con->query("SELECT * FROM users WHERE (`email` = '{$email}' || `username` = '{$email}') && `phone` = '{$phone}'")->num_rows;

    if($rows > 0){

        $passwordUpdate = $con->query("UPDATE users SET `password` = '{$hash}' WHERE (`email` = '{$email}' || `username` = '{$email}') && `phone` = '{$phone}'");

        if($passwordUpdate == 1){
            $p = "Success";
        } else{
            $p = "Could not Update your password, try again later";
        }


    } else{
        $p = "Could not find any user whose details match the ones you provided";
    }

}


if($request =="signup"){
    $p = $data;
    $firstName = $con->real_escape_string($data['firstName']);
    $lastName = $con->real_escape_string($data['lastName']);
    $username = $con->real_escape_string($data['username']);
    $email = $con->real_escape_string($data['email']);
    $phone = $con->real_escape_string($data['phone']);
    $password = $con->real_escape_string($data['password']);
    $occupation = $con->real_escape_string($data['occupation']);
    $department = $con->real_escape_string($data['department']);
    $gender = $con->real_escape_string($data['gender']);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    // $county = $con->real_escape_string($data['county']); '$role', '$department', '$gender'
    // $subCounty = $con->real_escape_string($data['subCounty']);
    // $ward = $con->real_escape_string($data['ward']);
    // $paypal = $con->real_escape_string($data['paypal']);
    // $referral = $con->real_escape_string($data['referral']);






$checkemail = mysqli_query($con, "select * from users where email = '{$email}'");
    $email1 = mysqli_num_rows($checkemail);

    $checkusername = mysqli_query($con, "select * from users where username = '{$username}'");
    $username1 = mysqli_num_rows($checkusername);

    $checkphone = mysqli_query($con, "select * from users where phone = '{$phone}'");
    $phone1 = mysqli_num_rows($checkphone);

    // $checkref = mysqli_query($con, "select * from users where username = '{$referral}' && status = 'activated' ");
    // $numref = mysqli_num_rows($checkref)+0;

if($email1 <1) {
    if($username1 < 1){

        if($phone1 < 1){

            if(3 > 1){
                // $p = true;
                // $msg2 = mysqli_query($con,"INSERT INTO notifications (`username`,`message`,`title`) VALUES ('{$username}','Welcome to fnfcom $firstName $lastName and congratulations on signing up. You have completed your first step in becoming a succesful online entrepreneur.','Welcome!!!')");

            //  $querify = "INSERT INTO users (`firstName`, `lastName`, `username`, `phone`, `email`,  `password`, `occupation` `department` `gender`,`status` )
            // VALUES ('{$firstName}', '{$lastName}', '{$username}',  '{$phone}',  '{$email}',  '{$hash}', '{$occupation}', '{$department}', '{$gender}', 'active')";

            $queryentry =  $con->query("INSERT INTO users (`firstName`,`lastName`,`username`,`email`,`password`,`phone`,`occupation`,`department`,`gender`) VALUES('{$firstName}','{$lastName}','{$username}','{$email}','{$hash}','{$phone}','{$occupation}','{$department}','{$gender}')");

            if($queryentry == 1){
                $res = $con->query("SELECT * FROM users WHERE `username` = '{$username}'")->fetch_assoc();
                // $msg = mysqli_query($con,"INSERT INTO notifications (`username`,`message`,`title`) VALUES ('{$referral}','$username has signed up using the link you shared','New Sign up')");
                // $msg2 = mysqli_query($con,"INSERT INTO notifications (`username`,`message`,`title`) VALUES ('{$username}','Welcome to fnfcom $firstName $lastName and congratulations on signing up. You have completed your first step in becoming a succesful online entrepreneur.','Welcome!!!')");
                // $msg1 = mysqli_query($con,"INSERT INTO notifications (`username`,`message`,`title`) VALUES ('{$username}','We give away prizes every month to some of our users who have activated their membership at fnfcom. Prizes include smartphones, televisions, airtime and many more!!!','Win a Prize')");


                $queryA = $con->query("SELECT * FROM users WHERE `department` = 'Admin'");

                while($row = $queryA->fetch_assoc()){
                  $admin = $row['username'];
                  $msg = mysqli_query($con,"INSERT INTO notifications (`username`,`message`,`title`) VALUES ('{$admin}','$firstName $lastName has been registered in the $department department, occupation: $occupation','New Registration')");
                }

                $res['response'] = 'Success';
                $res['password'] = 'unafikiria nitakuambia??';
                $p = $res;


                // $visitsQuery = mysqli_query($con,"SELECT * FROM visits WHERE `email` = '{$email}'");
                // $visitsRow = mysqli_num_rows($visitsQuery)+0;
                // $visitsArray = mysqli_fetch_array($visitsQuery);
                // $visits = $visitsArray['visits'];
                // $newVisits = $visits+1;
                // if($visitsRow <1){
                //     $query = mysqli_query($con,"INSERT INTO visits (`email`,`visits`) VALUES('{$email}', '1')");
                // } else if($visitsRow >0){

                //     $query = mysqli_query($con,"UPDATE visits SET `visits` = '{$newVisits}' WHERE `email` = '{$email}'");
                // }



            }   else $p ="$phone";
            } else $p = "Phone number must start with '07' or '01'";




        } else $p = "Phone number $phone is already taken";


    }else $p = "the username $username is already taken";



}else $p = "The email $email already exists, use another one";








}




echo json_encode($p);


?>
