<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['username']);
?>

<div style="
    background-color:hsla(27, 38%, 52%, 0.801);
    height:80px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:40px;
    font-family:Arial, sans-serif;
">

    <a href="index.php"
       style="color:white; font-size:22px; font-weight:bold; text-decoration:none;">
        Home
    </a>

    <?php if ($isLoggedIn): ?>

        <a href="statistics.php" style="color:white; font-size:22px; font-weight:bold; text-decoration:none;">
            Calculation
        </a>

        <a href="history.php" style="color:white; font-size:22px; font-weight:bold; text-decoration:none;">
            History
        </a>

        <a href="practice.php" style="color:white; font-size:22px; font-weight:bold; text-decoration:none;">
            Practice
        </a>

        <a href="practice_two.php" style="color:white; font-size:22px; font-weight:bold; text-decoration:none;">
            Practice Two
        </a>

        <a href="logout.php"
           style="color:white; font-size:22px; font-weight:bold; text-decoration:none;">
            Logout
        </a>

    <?php else: ?>

        <a href="login.php"
           style="color:white; font-size:22px; font-weight:bold; text-decoration:none;">
            Login
        </a>

    <?php endif; ?>

</div>
