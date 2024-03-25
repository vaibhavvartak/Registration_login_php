<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Retrieve form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $sqlCheck = "SELECT * FROM users WHERE username='$username'";
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
      echo "<div class='alert alert-danger'>User Already Exist.</div>";
      $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<div class='alert alert-danger'>Sorry, file already exists.</div>";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["profilePic"]["size"] > 500000) {
        echo "<div class='alert alert-danger'>Sorry, your file is too large.</div>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "<div class='alert alert-danger'>Sorry, your file was not uploaded.</div>";
    } else {
        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_file)) {
            $profileURL =  basename( $_FILES["profilePic"]["name"]);
        } else {
            echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
        }
    }

    if($uploadOk){
        // Insert user into database
        $sql = "INSERT INTO users (name, username, password, profilePic) VALUES ('$name', '$username', '$password', '$profileURL')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Registration successful!</div>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    .login{
      position: absolute;
      right: 1.3rem;
      top: 0.7rem;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <a href="index.php">Register</a>
          <a href="login.php" class="login">Login</a>
        </div>
        <div class="card-body">
          <form id="registrationForm" method="post" name="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                <div id="nameError" class="invalid-feedback"></div>
                </div>  
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                <div id="usernameError" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                <div id="passwordError" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                <div id="confirmPasswordError" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="profilePic">Upload Your Profile Photo:</label>
                    <input type="file" class="form-control" id="profilePic" name="profilePic" required>
                <div id="profilePicError" class="invalid-feedback"></div>
                </div>
            <button type="submit" class="btn btn-primary">Register</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Custom JavaScript for form validation -->
<script>
  // Client-side form validation
  (function() {
    'use strict';
    window.addEventListener('load', function() {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
          form.submit()
        }, false);
      });
    }, false);
  })();

  // Custom password validation
  document.getElementById("registrationForm").addEventListener("submit", function(event) {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    if (password !== confirmPassword) {
      document.getElementById("password").classList.add("is-invalid");
      document.getElementById("confirmPassword").classList.add("is-invalid");
      document.getElementById("passwordError").innerHTML = "Passwords do not match";
      document.getElementById("confirmPasswordError").innerHTML = "Passwords do not match";
      event.preventDefault();
    }
  });
</script>

</body>
</html>
