<?php
session_start();
include "inc_header.php";

/* -------------------------------
   COOKIE: read saved username
-------------------------------- */
$savedUsername = $_COOKIE['username'] ?? "";
$message = "";

/* -------------------------------
   LOGIN
-------------------------------- */
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $found = false;

    if (file_exists("password.txt")) {
        foreach (file("password.txt", FILE_IGNORE_NEW_LINES) as $line) {
            list($u, $p) = explode(",", $line);
            if ($username === trim($u) && $password === trim($p)) {
                $found = true;
                break;
            }
        }
    }

    if (!$found && file_exists("enc_password.txt")) {
        foreach (file("enc_password.txt", FILE_IGNORE_NEW_LINES) as $line) {
            list($u, $p) = explode(",", $line);
            if ($username === trim($u) && $password === trim($p)) {
                $found = true;
                break;
            }
        }
    }

    if ($found) {
        $_SESSION['username'] = $username;
        setcookie("username", $username, time() + (30*24*60*60), "/");
        header("Location: index.php");
        exit();
    } else {
        $message = "Invalid username or password.";
    }
}

/* -------------------------------
   CREATE ACCOUNT
-------------------------------- */
if (isset($_POST['create'])) {
    $newUser = trim($_POST['new_username']);
    $newPass = trim($_POST['new_password']);

    if ($newUser && $newPass) {
        file_put_contents("enc_password.txt", "$newUser,$newPass\n", FILE_APPEND);
        $message = "Account created. You can log in.";
    } else {
        $message = "All fields required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

<style>
    body {
        background: #f5f5f5;
    }

    .login-box {
        width: 400px;
        margin: 60px auto;
        background: #e7b68fe0;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 12px rgba(0,0,0,0.2);
    }

    h2 {
        text-align: center;
        color: darkred;
    }

    input[type=text],
    input[type=password] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
    }

    .btn {
        width: 100%;
        padding: 10px;
        margin-top: 15px;
        background: darkred;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn:hover {
        background: #a00000;
    }

    .toggle-btn {
        background: transparent;
        color: darkred;
        border: none;
        margin-top: 15px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: underline;
    }

    .message {
        text-align: center;
        color: darkred;
        font-weight: bold;
        margin-bottom: 10px;
    }

    #createBox {
        display: none;
        margin-top: 20px;
    }
</style>

<script>
    function toggleCreate() {
        const box = document.getElementById("createBox");
        box.style.display = box.style.display === "none" ? "block" : "none";
    }
</script>
</head>

<body>

<?php include "inc_navigation.php"; ?>

<div class="login-box">

    <h2>Login</h2>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
        Username
        <input type="text" name="username"
               value="<?= htmlspecialchars($savedUsername) ?>" required>

        Password
        <input type="password" name="password" required>

        <input type="submit" class="btn" name="login" value="Login">
    </form>

    <!-- CREATE ACCOUNT BUTTON -->
    <button class="toggle-btn" onclick="toggleCreate()">
        Create New Account
    </button>

    <!-- CREATE ACCOUNT FORM -->
    <div id="createBox">
        <h2>Create Account</h2>
        <form method="post">
            Username
            <input type="text" name="new_username" required>

            Password
            <input type="password" name="new_password" required>

            <input type="submit" class="btn" name="create" value="Create Account">
        </form>
    </div>

</div>

<?php include "inc_footer.php"; ?>
</body>
</html>
