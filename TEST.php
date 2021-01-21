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

		// Profile
		$userType="teacher";
		$sql="SELECT * FROM teacher WHERE email='$email'";
		$result=mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		$specialData=$row["initial"];
		$special="FACULTY INITIAL# ";

		//Course
		$sql="SELECT * FROM course WHERE instructor='$specialData'";
		$resultC=mysqli_query($conn, $sql);
		$rowC = mysqli_fetch_assoc($resultC);

		//Assignment
		$sql="SELECT * FROM assignment WHERE instructor='$specialData'";
		$resultA=mysqli_query($conn, $sql);
		$rowA = mysqli_fetch_assoc($resultA);

	}
	$name=$row["firstname"]." ".$row["lastname"];
	$dp="'".$row["pic"]."'";
	$bio=$row["bio"];
	$target_file='';

	// COURSE CREATE 
	if(!empty($_POST)){
		//course
		if(!empty($_REQUEST["cCode"]) && !empty($_REQUEST["cName"]) && !empty($_REQUEST["cSem"]) && !empty($_REQUEST["cAbout"])){
			$cCode=$_POST["cCode"];
			$cName=$_POST["cName"];
			$cSem=$_POST["cSem"];
			$cAbout=$_POST["cAbout"];
			$cInstructor = $specialData;
			$fid = $aid = $cCode.$cSem.$cInstructor; //i.e. CSE411SDSSUMMER19


			if($userType=="teacher") {
		       	$sql = "INSERT INTO course(code, name, semester, about, instructor,aid, fid)
             values ('$cCode','$cName', '$cSem', '$cAbout','$cInstructor', '$aid', '$fid')";
		    }
		        
			if ($conn->query($sql) === TRUE) {
				echo "Database updated successfully";
			} else {
				echo "Error updating database: " . $conn->error;
			}
			header('location: profile.php' );

		}
	}

	//FILE UPLOAD
	
	if(!empty($_POST)){
		//bio
		if(!empty($_REQUEST["updateBio"])){
			$tempBio=$_POST["updateBio"];

			if($userType=="student") {
		       	$sql="UPDATE student SET bio='$tempBio' WHERE email='$email'";
		    } else {
		        $sql="UPDATE teacher SET bio='$tempBio' WHERE email='$email'";
		    }
		        
			if ($conn->query($sql) === TRUE) {
				echo "Database updated successfully";
			} else {
				echo "Error updating database: " . $conn->error;
			}
			header('location: profile.php' );

		}

		//picture
		$target_dir = "misc/";
		$target_file = $target_dir .$email. basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$target_file=$target_dir.$email.".".$imageFileType;

		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
		    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		    if($check !== false) {
		        echo "File is an image - " . $check["mime"] . ".";
		        $uploadOk = 1;
		    } else {
		        echo "File is not an image.";
		        $uploadOk = 0;
		    }
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

		        if($userType=="student") {
		        	$sql="UPDATE student SET pic='$target_file' WHERE email='$email'";
		        } else {
		        	$sql="UPDATE teacher SET pic='$target_file' WHERE email='$email'";
		        }
		        
				if ($conn->query($sql) === TRUE) {
				    echo "Database updated successfully";
				} else {
				    echo "Error updating database: " . $conn->error;
				}
				header('location: profile.php' );

		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
		}
	} 

	$conn->close();
?>
	<div class="wrap">
		<h1></h1>
		<div class="row">
			<div class="columnA">
				<center><img src=<?php echo $dp; ?>></center>
			</div>
			<div class="columnB">
				<h2><?php echo $name; ?></h2>
    			<p><?php echo $special, $specialData; ?></p>
    			<p>Facebook | GitHub | Linked In | eMail</p>
    			<p><?php echo $bio; ?></p>
			</div>
		</div>
		
		<?php 
			if ($userType=='teacher'){
				echo'<a href="#course"><button class="button buttonTeacher">Course</button></a>';
				echo'<a href="#assignment"><button class="button buttonTeacher">Assignment</button></a>';
				echo'<a href="#"><button class="button buttonTeacher" onclick="showHidden(\'myEdit\')">Edit</button></a>';
				echo '<a href="#"><button class="button buttonTeacher" onclick="showHidden(\'myCreate\')">Create</button></a>';
			} else {
				echo'<a href="#course"><button class="button buttonStudent">Course</button></a>';
				echo'<a href="#assignment"><button class="button buttonStudent">Assignment</button></a>';
				echo'<a href="#"><button class="button buttonStudent" onclick="showHidden(\'myEdit\')">Edit</button></a>';
			}
		?>
		<div id="myEdit" class="edit">
			<h1>Edit Profile</h1>
	   		
	   		 <form action='<?php echo $_SERVER['PHP_SELF']?>' method="post" enctype="multipart/form-data">
	   		 <h2>Upload Profile Picture:<h2><input type="file" name="fileToUpload" id="fileToUpload">
	   		 <h2>Bio: <input type="text" name="updateBio" placeholder="write something about you..." value="<?php echo $bio; ?>" maxlength="356"> <h2>

	   		 <input type="submit" value="Save Changes"></input>
	   		 </form>
		</div>

		<div id="myCreate" class="edit">
			<h1>Create Course</h1>
	   		
	   		 <form action='<?php echo $_SERVER['PHP_SELF']?>' method="post" enctype="multipart/form-data">
	   		 <h2>Course Code: <input type="text" name="cCode" placeholder="i.e. CSE411"  maxlength="356"> <h2>	   		 
	   		 <h2>Course Name: <input type="text" name="cName" placeholder="i.e. Software Engineering"  maxlength="356"> <h2>
	   		 <h2>Semester: <input type="text" name="cSem" placeholder="i.e. SUMMER2019" maxlength="356"> <h2>
	   		 <h2>Description: <input type="text" name="cAbout" placeholder="write something about the course..." maxlength="356"> <h2>

	   		 <input type="submit" value="Create New Course"></input>
	   		 </form>
		</div>


		<?php
			error_reporting(0);
			// course
			echo '<h1><a name="course">Courses<a></h1>';
			if ($resultC->num_rows > 0) {
			    // output data of each row
			    while($rowC = $resultC->fetch_assoc()) {
					echo '<div class="courseBox">';
						echo '<h2>'.$rowC["code"].': '.$rowC["name"].'</h2>';
						echo '<h3>instructed by '.$rowC["instructor"].'</h3>';
						echo '<h4>'.$rowC["semester"].'</h4>';
					echo '</div>';
			    }
			} else {
			    echo '<div class="courseBox">';
						echo '<h4><i>There are no courses to show</i></h4>';
					echo '</div>';
			}
			
			// Assignment
			echo '<h1><a name="assignment">Assignment<a></h1>';
			if ($resultA->num_rows > 0) {
			// output data of each row
				while($rowA = $resultA->fetch_assoc()) {
					echo '<div class="courseBox">';
						echo '<h2>'.$rowA["aid"].'</h2>';
						echo '<h3>submitted by '.$rowA["sid"].'</h3>';
						echo '<h4>'.$rowA["fid"].'</h4>';
					echo '</div>';
				}
			} else {
				echo '<div class="courseBox">';
				echo '<h4><i>There are no assignments to show</i></h4>';
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