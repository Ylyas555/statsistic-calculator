<?php
$year = date("Y");
$user = $_SESSION['username'] ?? "Guest";
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        /* Remove default browser spacing */
        html, body {
            margin: 0;
            padding: 0;
        }

        header {
            position: relative;
            text-align: center;
            background: #b22222;
            color: white;
            overflow: hidden;
        }

        /* Right branch image */
        .branch-right {
            position: fixed; /* fixed to viewport top-right */
            top: 0;
            right: 0;
            height: 25vh; /* use relative height */
            width: auto;
            max-height: 450px; /* optional max */
            object-fit: contain;
            pointer-events: none;
            z-index: 10;
        }

        /* Left branch image */
        .branch-left {
            position: fixed; /* fixed to viewport top-left */
            top: 0;
            left: 0;
            height: 25vh; /* relative height */
            width: auto;
            max-height: 450px; /* optional max */
            object-fit: contain;
            pointer-events: none;
            z-index: 10;
        }

        /* Header content padding */
        .header-content {
            padding: 15px;
            position: relative; /* keep text above branches */
            z-index: 11; /* above branches */
        }
    </style>
</head>
<body>

<header>
    <!-- Left branch -->
    <img src="images/left_branch.png" alt="Left branch with leaves" class="branch-left">
    
    <!-- Right branch -->
    <img src="images/branch_right.png" alt="Right branch with leaves" class="branch-right">

    <div class="header-content">
        <h1>Statistics Calculator</h1>
        <p>Welcome, <?= htmlspecialchars($user) ?> | © <?= $year ?></p>
    </div>
</header>

</body>
</html>
