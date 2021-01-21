<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$db="coursehelperdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$email = filter_input(INPUT_POST, 'email');
$pass = filter_input(INPUT_POST, 'password');
$userType=filter_input(INPUT_POST, 'userType');

if($userType=='student'){
	$sql = "SELECT * FROM student WHERE email = '$email' && password = '$pass'";
}
else {
	$sql = "SELECT * FROM teacher WHERE email = '$email' && password = '$pass'";
}

$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);
if($num == 1){
	$_SESSION['email'] = $email;
	$_SESSION['userType'] = $userType;
	header('location: profile.php' );
	
}
else{
	header('location: login.php' );
}

?>