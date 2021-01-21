<?php
	session_start();
	if(!isset($_SESSION['email']))
	{
		header("Location: login.php");
	}
	include "userHeader.html";
	
?>

<?php 
	//page info 
	$name=''; //full name
	$special=''; //student or initial 
	$specialData='';//ID or initial
	$bio=''; //bio text
	$dp=''; //display picture url

	//logged in user info
	$email=$_SESSION['email'];

	//server info
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

	$sql="SELECT * FROM student WHERE email='$email'";

	$result=mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	if(mysqli_num_rows($result)==1){
		$userType="student";
		$specialData=$row["studentID"];
		$special="STUDENT ID# ";
	} else {
		$userType="teacher";
		$sql="SELECT * FROM teacher WHERE email='$email'";
		$result=mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		$specialData=$row["initial"];
		$special="FACULTY INITIAL# ";
	}
	$name=$row["firstname"]." ".$row["lastname"];
	$dp="'".$row["pic"]."'";
	$bio=$row["bio"];

	$conn->close();
?>
	<div class="wrap">
		<h1></h1>
		<div class="row">
			<div class="columnA">
				<center><img src= <?php echo $dp; ?>></center>
			</div>
			<div class="columnB">
				<h2><?php echo $name; ?></h2>
    			<p><?php echo $special, $specialData; ?></p>
    			<p>Facebook | GitHub | Linked In | eMail</p>
    			<p><?php echo $bio; ?></p>
			</div>
		</div>
		<a href="#course"><button class="button button4">Course</button></a>
		<a href="#assignment"><button class="button button4">Assignment</button></a>
		<a href="#"><button class="button button4">Edit</button></a>
		<h1><a name="course">Courses<a></h1>
		<div class="courseBox">
			<h2>CSE411: Software Engineering and Information System Design</h2>
			<h3>instructed by Sadia Sharmin</h3>
			<h4>Summer 2019</h4>
		</div>
		<div class="courseBox">
			<h2>CSE411: Software Engineering and Information System Design</h2>
			<h3>instructed by Sadia Sharmin</h3>
			<h4>Summer 2019</h4>
		</div>
		<div class="courseBox">
			<h2>CSE411: Software Engineering and Information System Design</h2>
			<h3>instructed by Sadia Sharmin</h3>
			<h4>Summer 2019</h4>
		</div>
		<div class="courseBox">
			<h2>CSE411: Software Engineering and Information System Design</h2>
			<h3>instructed by Sadia Sharmin</h3>
			<h4>Summer 2019</h4>
		</div>

		<h1><a name="assignment">Assignment<a></h1>
		<div class="courseBox">
			<h2>CSE411: Software Engineering and Information System Design</h2>
			<h3>instructed by Sadia Sharmin</h3>
			<h4>Summer 2019</h4>
		</div>
		<div class="courseBox">
			<h2>CSE411: Software Engineering and Information System Design</h2>
			<h3>instructed by Sadia Sharmin</h3>
			<h4>Summer 2019</h4>
		</div>
	</div>

<?php
include"footer.html";
?>