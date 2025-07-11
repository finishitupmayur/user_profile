<?php
include('header.php');
include('db.php');

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);



$checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
if (!$checkStmt) {
    die("Email check prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    $message = "❌ Email already registered.";
} else {
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("sss", $name, $email, $hashed_password);
    if ($stmt->execute()) {
        $message = "✅ Registration successful. <a href='login.php'>Login now</a>";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
    $stmt->close();
}
$checkStmt->close();

$checkStmt->close();


    if ($stmt->execute()) {
        $message = "✅ Registration successful. <a href='login.php'>Login now</a>";
    } else {
        $message = "❌ Error: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/validation.js"></script>
</head>
<body>
<div class="container mt-5 col-md-6">
    <h2 class="mb-4">Register</h2>
    <form method="POST" onsubmit="return validateRegisterForm();">
        <div class="mb-3">
            <label for="name">username</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>

        <div class="mb-3">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>

        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password">
        </div>

        <button type="submit" class="btn btn-primary">Register</button>

        <p class="mt-3 text-success"><?= $message ?></p>
    </form>
</div>
</body>
</html>
