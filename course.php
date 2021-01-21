<?php
	session_start();
	if(!isset($_SESSION['email'])){
		header("Location: login.php");
	}

	if(isset($_GET['id'])=="" && empty($_POST))  {
		header("Location: feed.php");
	} 
	if(isset($_GET['id'])!=""){
		$pageID=$_GET["id"];
	} else {
		header("Location: feed.php");
	}
	if(!empty($_POST)){
		$pageID=$_POST["pageID"];
	}else {
		$pageID=$_GET["id"];
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
	//$pageID=$_GET['id'];

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
	}
	$name=$row["firstname"]." ".$row["lastname"];
	$dp="'".$row["pic"]."'";
	$bio=$row["bio"];
	$target_file='';

	// Files
	if(!empty($_POST) && !empty($_REQUEST["upload"])){
		//course
		$target_dir = "misc/";
		$target_file = basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$target_file=$target_dir.$target_file.'_'.$pageID.'_'.time().".".$imageFileType;

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
		        	//...
		        } else {
		        	$sql = "INSERT INTO file(fid, filename)
             		values ('$pageID','$target_file')";
		        }
		        
				if ($conn->query($sql) === TRUE) {
				    echo "Database updated successfully";
				} else {
				    echo "Error updating database: " . $conn->error;
				}
				header('location: course.php?id='.$pageID );

		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
		}
	}

	//Notice
	if(!empty($_REQUEST["updateNotice"])){
			$tempBio=$_POST["updateNotice"];

			if($userType=="student") {
				//...
		    } else {
		        $sql="UPDATE course SET notice='$tempBio' WHERE fid='$pageID'";
		    }
		        
			if ($conn->query($sql) === TRUE) {
				echo "Database updated successfully";
			} else {
				echo "Error updating database: " . $conn->error;
			}
			header( 'location: course.php?id='.$pageID);
	}


	//Assignement
	/*
	if(!empty($_REQUEST["updateTitle"])){
			

			if($userType=="student") {
				//...
		    } else {
		        $sql="INSERT INTO assignment(aid, title, deadline)
             		values ('$pageID','$updateTitle', '$updateDeadline')";
		    }
		        
			if ($conn->query($sql) === TRUE) {
				echo "Database updated successfully";
			} else {
				echo "Error updating database: " . $conn->error;
			}
			header( 'location: course.php?id='.$pageID);
	}
	*/
	// Assignment Files
	if(!empty($_POST) && !empty($_REQUEST["uploadAssignment"]) && !empty($_REQUEST["updateTitle"])){
		//Assignemnet
		$tempTitle=$_POST["updateTitle"];
		$tempDeadline=$_POST["updateDeadline"];

		// File
		$target_dir = "misc/";
		$target_file = basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$target_file=$target_dir.'_assignment_'.$target_file.'_'.$pageID.'_'.time().".".$imageFileType;

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
		        	//...
		        } else {
		        	$sql="INSERT INTO assignment (aid, title, deadline, attach)
             		values ('$pageID','$tempTitle', '$tempDeadline', '$target_file')";
		        }
		        
				if ($conn->query($sql) === TRUE) {
				    echo "Database updated successfully";
				} else {
				    echo "Error updating database: " . $conn->error;
				}
				header('location: course.php?id='.$pageID );

		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
		}
	}

	// SUBMISSON 
	if(!empty($_POST) && !empty($_REQUEST["submitAs"])){
		
		// File
		$target_dir = "misc/";
		$target_file = basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$target_file=$target_dir.'_submission_'.$target_file.'_'.$email.'_'.$pageID.'_'.time().".".$imageFileType;

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
		        	//...
		        	$sql="INSERT INTO submission (aid, sid, fid)
             		values ('$pageID','$email', '$target_file')";
		        } else {
		        	//...
		        }
		        
				if ($conn->query($sql) === TRUE) {
				    echo "Database updated successfully";
				} else {
				    echo "Error updating database: " . $conn->error;
				}
				header('location: course.php?id='.$pageID );

		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
		}
	}
	// enroll
	if(!empty($_GET) && isset($_GET['enroll'])){
		if ($_GET['enroll']=="true") {
		    $sql="INSERT INTO enroll (cid, sid)
	             values ('$pageID', '$email')";
		 } else if ($_GET['enroll']=="false") {
		 	 $sql="DELETE FROM enroll WHERE cid = '$pageID' && sid='$email'";
		 }

		 if ($conn->query($sql) === TRUE) {
			echo "Database updated successfully";
			header('location: course.php?id='.$pageID );
		} else {
			echo "Error updating database: " . $conn->error;
		}
	}


?>
	<div class="wrap">
		
		<?php 
			//Course
			$sql="SELECT * FROM course WHERE fid='$pageID'";
			$result=mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($result);
			echo '<div class="courseBox">';
				echo '<h2>'.$row["code"].': '.$row["name"].'</h2>';
				echo '<h3>instructed by '.$row["instructor"].'</h3>';
				echo '<h4>'.$row["semester"].'</h4>';
				if($userType=='student'){
					//echo '<h4> Enrolled </h4> ';
					$sql="SELECT * FROM enroll WHERE sid='$email' && cid='$pageID'";
					$result=mysqli_query($conn, $sql);
					$num = mysqli_num_rows($result);
					if($num==0){
						echo '<h4> <a href="course.php?id='.$pageID.'&enroll=true">Enroll Now </a></h4> ';
					} else {
						echo '<h4> <a href="course.php?id='.$pageID.'&enroll=false">Enrolled</a></h4> ';
					}
				}
			echo '</div>';

			if ($userType=='teacher'){
				echo'<a href="#file"><button class="button buttonTeacher">Files</button></a>';
				echo'<a href="#assignment"><button class="button buttonTeacher" onclick="showHidden(\'myAssignment\')">Assignment</button></a>';
				echo'<a href="#"><button class="button buttonTeacher" onclick="showHidden(\'myEdit\')">Notice</button></a>';
				echo '<a href="#"><button class="button buttonTeacher" onclick="showHidden(\'myCreate\')">Upload</button></a>';
			} else {
				echo'<a href="#file"><button class="button buttonStudent">Files</button></a>';
				echo'<a href="#assignment"><button class="button buttonStudent">Assignment</button></a>';
				echo'<a href="#"><button class="button buttonStudent">Notice</button></a>';
			}
		?>
		<div id="myEdit" class="edit">
			<h1>Edit Notice</h1>
	   		
	   		 <form action='<?php echo $_SERVER['PHP_SELF']?>' method="post">
	   		 <h2>Notice: <input type="text" name="updateNotice" placeholder="write the notice..." maxlength="356"> <h2>
	   		 <input type="hidden" name="pageID" value='<?php echo $pageID;?>'>
	   		 <input type="submit" value="Save Changes"></input>
	   		 </form>
		</div>

		<div id="myAssignment" class="edit">
			<h1>Create Assignment</h1>
	   		
	   		 <form action='<?php echo $_SERVER['PHP_SELF']?>' method="post" enctype="multipart/form-data">
	   		 <h3>Title: <input type="text" name="updateTitle" placeholder="write the title..." maxlength="356"> <h3>
	   		 <h3>Deadline: <input type="date" name="updateDeadline" placeholder="write the date..." maxlength="356">
	   		 <h4>Attach File:</h4><input type="file" name="fileToUpload" id="fileToUpload">
	   		 <input type="hidden" name="pageID" value='<?php echo $pageID;?>'>
	   		 <input type="submit" name="uploadAssignment" value="Create Assignment"></input>
	   		 </form>
		</div>


		<div id="myCreate" class="edit">
			<h1>File Upload</h1>
	   		
	   		 <form action='<?php echo $_SERVER['PHP_SELF']?>' method="post" enctype="multipart/form-data">
	   		 <h2>Select File:<h2><input type="file" name="fileToUpload" id="fileToUpload">
	   		 <input type="hidden" name="pageID" value='<?php echo $pageID;?>'>
	   		 <input type="submit" name="upload" value="Upload"></input>
	   		 </form>
		</div>


		<?php
			//Notice
			echo '<h1><a name="notice"><a>Notice</h1>';
			$sql="SELECT notice FROM course WHERE fid='$pageID'";
			$result=mysqli_query($conn, $sql);
			$rowN = mysqli_fetch_assoc($result);
			if($rowN["notice"]==""){
				echo '<div class="courseBox">';
				echo '<h4><i>There are no notice to show</i></h4>';
				echo '</div>';
			} else {
				echo '<div class="courseBox">';
				echo '<div style="color: red"><h4><b><i>'.$rowN["notice"].'</i></b></h4></div>';
				echo '</div>';
			}

			// Assignment
			echo '<h1><a name="assignment">Assignment<a></h1>';
			$sql="SELECT * FROM assignment WHERE aid='$pageID'";
			$result=mysqli_query($conn, $sql);
			if ($result->num_rows > 0) {
			// output data of each row
				while($rowA = $result->fetch_assoc()) {
					echo '<div class="courseBox">';
						echo '<h2>'.$rowA["title"].'</h2>';
						echo '<h3>Deadline: '.$rowA["deadline"].'</h3>';
						echo '<h4><a href="'.$rowA["attach"].'">Download Attachment</a></h4>';
						if($userType=="student" && $rowA["deadline"]>=date("Y-m-d")){
							echo '<button onclick="showHidden(\'subAs\')"><h4>Submit Assignment</h4></button>';
						}
					echo '</div>';
					echo'<div id="subAs" class="edit">';
						echo'<h1>File Upload</h1>';
				   		 echo'<form action="'.$_SERVER['PHP_SELF'].'" method="post" enctype="multipart/form-data">';
				   		 echo'<h2>Select File:<h2><input type="file" name="fileToUpload" id="fileToUpload">';
				   		 echo'<input type="hidden" name="pageID" value="'.$pageID.'">';
				   		 echo'<input type="submit" name="submitAs" value="Upload"></input>';
				   		 echo'</form>';
					echo'</div>';
				}
			} else {
				echo '<div class="courseBox">';
				echo '<h4><i>There are no assignments to show</i></h4>';
				echo '</div>';
			}

			// Files
			echo '<h1><a name="file">Files<a></h1>';
			$sql="SELECT * FROM file WHERE fid='$pageID'";
			$resultF=mysqli_query($conn, $sql);
			//$rowF = mysqli_fetch_assoc($resultF);
			if ($resultF->num_rows > 0) {
			// output data of each row
				while($rowF = $resultF->fetch_assoc()) {
					echo '<div class="courseBox">';
						echo '<h4><a href="'.$rowF["filename"].'">'.substr($rowF["filename"],strpos($rowF["filename"], "/") + 1).'</a></h4>';
					echo '</div>';
				}
			} else {
				echo '<div class="courseBox">';
				echo '<h4><i>There are no files to show</i></h4>';
				echo '</div>';
			}
		?> 


	</div>

<?php
	include"footer.html";
	$conn->close();
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