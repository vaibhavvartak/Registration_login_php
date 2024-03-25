<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        // Prepare SQL statement to fetch user from database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            // User exists, verify password
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables and redirect to dashboard or home page
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect, show error message
                $error = "Invalid password.";
            }
        } else {
            // User doesn't exist, show error message
            $error = "User does not exist.";
        }

        // Close database connection
        $stmt->close();
        $conn->close();
    }
  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
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
<?php if (isset($error)) { ?>
    <div class='alert alert-danger'><?php echo $error; ?></div>
<?php } ?>
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
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                <div id="usernameError" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                <div id="passwordError" class="invalid-feedback"></div>
                </div>
            <button type="submit" class="btn btn-primary">Login</button>
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
</body>
</html>
