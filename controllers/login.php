<?php
require_once __DIR__ . '/../src/Authentication.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $loginSystem = new \App\Authentication();
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($loginSystem->authenticate($username, $password)) {
        header("Location: /receipt");
        exit;
    } else {
        $error = "<h3>USERNAME OR PASSWORD IS INCORRECT</h3>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Receipt Form</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<h1>Fuel Receipt Login</h1>
<?php //if (isset($error)) echo $error; ?>
<form method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
