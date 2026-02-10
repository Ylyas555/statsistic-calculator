<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$HISTORY_FILE = __DIR__ . "/history.csv";

/* =============================
   CLEAR HISTORY
============================= */
if (isset($_POST['clear'])) {
    file_put_contents($HISTORY_FILE, "");
    header("Location: history.php");
    exit();
}

/* =============================
   LOAD HISTORY
============================= */
$history = [];

if (file_exists($HISTORY_FILE)) {
    if (($h = fopen($HISTORY_FILE, "r")) !== false) {
        while (($row = fgetcsv($h)) !== false) {
            if (count($row) === 4) {
                $history[] = $row;
            }
        }
        fclose($h);
    }
}

include "inc_header.php";
include "inc_navigation.php";
?>

<style>
    .history-container {
        width: 90%;
        margin: 30px auto;
        background: #e7b68fe0;
        padding: 20px;
        border-radius: 10px;
    }

    h2 {
        text-align: center;
        color: darkred;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    th {
        background: darkred;
        color: white;
        padding: 12px;
        text-align: center;
    }

    td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ccc;
    }

    tr:hover {
        background-color: #f3d2b8;
    }

    .clear-btn {
        background: darkred;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        cursor: pointer;
        margin-bottom: 15px;
    }

    .clear-btn:hover {
        background: #a00000;
    }
</style>

<div class="history-container">

<h2>Calculation History</h2>

<form method="post" style="text-align:right;">
    <button class="clear-btn" name="clear"
        onclick="return confirm('Clear all history?')">
        Clear History
    </button>
</form>

<table>
<tr>
    <th>Measurement</th>
    <th>Input Numbers</th>
    <th>Result</th>
    <th>Date</th>
</tr>

<?php if ($history): ?>
    <?php foreach ($history as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r[0]) ?></td>
            <td><?= htmlspecialchars($r[1]) ?></td>
            <td><?= htmlspecialchars($r[2]) ?></td>
            <td><?= htmlspecialchars($r[3]) ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4">No history found.</td>
    </tr>
<?php endif; ?>
</table>

</div>

<?php include "inc_footer.php"; ?>
