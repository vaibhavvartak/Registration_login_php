<?php
include 'config.php';
session_start();

// Retrieve form data
$name = $_POST['name'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
    echo "<script>alert('Sorry, file already exists.')</script>";
    header('Location: index.php');
    exit;
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "<script>alert('Sorry, your file is too large.')</script>";
    header('Location: index.php');
    exit;
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.')</script>";
    header('Location: index.php');
    exit;
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "<script>alert('Sorry, your file was not uploaded.')</script>";
} else {
    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_file)) {
        $profileURL =  basename( $_FILES["profilePic"]["name"]);
    } else {
        echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
        header('Location: index.php');
        exit;
    }
}

if($uploadOk){
    // Insert user into database
    $sql = "INSERT INTO users (name, username, password, profilePic) VALUES ('$name', '$username', '$password', '$profileURL')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!')</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
