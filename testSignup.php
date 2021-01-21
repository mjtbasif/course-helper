<?php
	include "header.html";
	session_start();

?>
<!-- Main Content-->
<br><br>
<div class="row">
 	<div class="column">
 		<img src="disp.png">
	</div>
  <div class="column">
  	<div class="signUp">
   		 <h3>Sign Up</h3>
   		 <p>Already have an account? <a href="login.php">Log In</a> now.</p>
   		 <form action='<?php echo $_SERVER['PHP_SELF']?>' method="post" id="userForm">
   		 First Name: <input type="text" name="fname" placeholder="Your first name" required> <br>
   		 Last Name: <input type="text" name="lname" placeholder="Your last name" required> <br>
   		 Email: <input type="email" name="email" placeholder="Your email" required> <br>
   		 <?php
   		 	if(!empty($_SESSION['msg']) &&  !empty($_REQUEST['email']))
   		 	{
   		 		echo "<p style='color:red'>Email already registered<br></p>";
   		 	}
   		 ?>
   		 Password: <input type="password" name="password" placeholder="Your password" required><br>

       	 I am a <input type="radio" name="userType" id="student" value="student" onchange="radioOption()" required="">Student 
       	 <input type="radio" name="userType" id="teacher" value="teacher" onchange="radioOption()">Teacher<br>

       	 <div id="special"></div>

   		 <p><input type="checkbox"> I agree to the <a href="#">Course Helper Terms</a>.</p>
   		 <input type="submit" value="Sign Up"></input>
   		 </form>
   		 <br><br><br><br>
   	</div>
  </div>
</div>

<?php
include"footer.html";
?>

<script>
function radioOption() {
  var str="";
  if(document.getElementById("teacher").checked){
  	str="Initial: <input type='text' name='special' placeholder='Your faculty initial' required> <br>";
  }else if(document.getElementById("student").checked) {
  	str="Student ID: <input type='text' name='special' placeholder='Your student ID' required> <br>";
  }
  document.getElementById("special").innerHTML=str;
}

</script>
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
    $sql = "INSERT INTO student(firstname, lastname, studentID, email, password, pic, bio)
             values ('$firstname','$lastname', '$special', '$email','$pass', 'default.jpg', 'ADD BIO')";
}
else {
    $sql = "INSERT INTO teacher(firstname, lastname, initial, email, password, pic, bio)
             values ('$firstname','$lastname','$special', '$email','$pass', 'default.jpg', 'ADD BIO')";
}


// Create connection
$conn = new mysqli($servername, $username, $password, $db);

//validate 
if($userType=='student'){
    $VALsql = "SELECT * FROM student where email = '$email'";
}
else if($userType=='teacher'){
    $VALsql = "SELECT * FROM teacher where email = '$email'";
}

// Create connection
$conn = new mysqli($servername, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// validity check
 
 $q1 = "SELECT * FROM student where email = '$email'";
 $q2 = "SELECT * FROM teacher where email = '$email'";

 $flag=0;

if(!empty($_POST))
{
	$result = mysqli_query($conn, $q1);
	$num = mysqli_num_rows($result);
	if(mysqli_num_rows($result)== 1){
		// ALREADY REGISTERED
		$emailMsg="Email already registered";
		$_SESSION['msg']=1;
		$flag=1;
	}
	else{
		// check other table 
		$result = mysqli_query($conn, $q1);
		$num = mysqli_num_rows($result);
		if($num == 1){
			// ALREADY REGISTERED
			$emailMsg="Email already registered";
			$_SESSION['msg']=1;
			$flag=1;
		}
	}
	if($flag==0){
		if ($conn->query($sql)){
			session_destroy();
	        header('Location: login.php');
	        }else{
	            echo "Error: ". $sql ."
	            ". $conn->error;
	        }
	}
}
$conn->close();

     
?>
​