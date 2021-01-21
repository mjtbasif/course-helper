 <?php
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
//sql to drop tables
$sql="drop table teacher, student, course, enroll, assignment, submission, file";
if ($conn->query($sql) === TRUE) {
    echo "table dropped successfully";
} else {
    echo "Error dropping database: " . $conn->error;
}


// sql to create table
$sql = "CREATE TABLE teacher (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
initial VARCHAR(30) NOT NULL,
email VARCHAR(50) NOT NULL,
password VARCHAR(30) NOT NULL,
pic VARCHAR(100) NOT NULL,
bio VARCHAR(400) NOT NULL,
reg_date TIMESTAMP
)";


if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "CREATE TABLE student (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
studentID VARCHAR(30) NOT NULL,
email VARCHAR(50) NOT NULL,
password VARCHAR(30) NOT NULL,
pic VARCHAR(100) NOT NULL,
bio VARCHAR(400) NOT NULL,
reg_date TIMESTAMP
)";


if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "CREATE TABLE course (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
code VARCHAR(30) NOT NULL,
name VARCHAR(130) NOT NULL,
semester VARCHAR(30) NOT NULL,
instructor VARCHAR(50) NOT NULL,
aid VARCHAR(30) NOT NULL,
fid VARCHAR(100) NOT NULL,
about VARCHAR(400) NOT NULL,
notice VARCHAR(400) NOT NULL,
reg_date TIMESTAMP
)";


if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "CREATE TABLE assignment (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
aid VARCHAR(30) NOT NULL,
title VARCHAR(30) NOT NULL,
deadline VARCHAR(30) NOT NULL,
attach VARCHAR(300) NOT NULL,
reg_date TIMESTAMP
)";


if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "CREATE TABLE file (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
fid VARCHAR(30) NOT NULL,
filename VARCHAR(300) NOT NULL,
reg_date TIMESTAMP
)";


if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "CREATE TABLE enroll (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
cid VARCHAR(30) NOT NULL,
sid VARCHAR(100) NOT NULL,
reg_date TIMESTAMP
)";


if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "CREATE TABLE submission (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
aid VARCHAR(30) NOT NULL,
sid VARCHAR(100) NOT NULL,
fid VARCHAR(300) NOT NULL,
reg_date TIMESTAMP
)";


if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?> 