<?php

 $firstname = filter_input(INPUT_POST, 'fname');
 $lastname = filter_input(INPUT_POST, 'lname');
 $email = filter_input(INPUT_POST, 'email');
 $pass = filter_input(INPUT_POST, 'password');
 $userType=filter_input(INPUT_POST, 'userType');
 $special=filter_input(INPUT_POST, 'special');

$servername = "localhost";
$username = "root";
$password = "";
$db="coursehelperdb";

if($userType=='student'){
    $sql = "INSERT INTO student(firstname, lastname, studentID, email, password)
             values ('$firstname','$lastname', '$special', '$email','$pass')";
}
else {
    $sql = "INSERT INTO teacher(firstname, lastname, initial, email, password)
             values ('$firstname','$lastname','$special', '$email','$pass')";
}


// Create connection
$conn = new mysqli($servername, $username, $password, $db);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else{
        if ($conn->query($sql)){
                header('Location: login.php');
        }else{
                 echo "Error: ". $sql ."
                 ". $conn->error;
        }
    }
        $conn->close();
     
?>