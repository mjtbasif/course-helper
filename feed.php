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

	
	//Course
	$sql="SELECT * FROM course";
	$resultC=mysqli_query($conn, $sql);
	

	$conn->close();
?>
	<div class="wrap">

		<?php
			//error_reporting(0);
			// course
			echo '<h1><a name="course">Courses<a></h1>';
			if ($resultC->num_rows > 0) {
			    // output data of each row
			    while($rowC = $resultC->fetch_assoc()) {
					echo '<div class="courseBox">';
						echo '<a href="course.php?id='.$rowC["fid"].'"><h2>'.$rowC["code"].': '.$rowC["name"].'</h2></a>';
						echo '<h3>instructed by '.$rowC["instructor"].'</h3>';
						echo '<h4>'.$rowC["semester"].'</h4>';
					echo '</div>';
			    }
			} else {
			    echo '<div class="courseBox">';
						echo '<h4><i>There are no courses to show</i></h4>';
					echo '</div>';
			}
		?>
	</div>

<?php
include"footer.html";
?>

<script>
function showHidden(id) {
  var x = document.getElementById(id);
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>