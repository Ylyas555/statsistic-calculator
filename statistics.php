<?php
session_start();

// Require login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include "inc_header.php";
include "inc_navigation.php";

/* -----------------------------
   HELPER FUNCTIONS
----------------------------- */

// Save calculation to CSV
function saveHistory($measurement, $numbers, $result) {
    $file = __DIR__ . "/history.csv";
    $date = date("m-d-Y");

    if (is_array($numbers)) {
        $numbers = implode(",", $numbers);
    }

    $row = [$measurement, $numbers, $result, $date];

    $fp = fopen($file, "a");
    if (!$fp) {
        die("Cannot write to history.csv");
    }
    fputcsv($fp, $row);
    fclose($fp);
}

// Parse input string safely
function parseNumbers($input) {
    $parts = explode(",", $input);
    $nums = [];

    foreach ($parts as $p) {
        $p = trim($p);
        if ($p !== "" && is_numeric($p)) {
            $nums[] = (float)$p;
        }
    }
    return $nums;
}

// Calculations
function mean($nums) {
    return array_sum($nums) / count($nums);
}

function median($nums) {
    sort($nums);
    $count = count($nums);
    $mid = floor($count / 2);
    return ($count % 2 == 0)
        ? ($nums[$mid - 1] + $nums[$mid]) / 2
        : $nums[$mid];
}

function mode($nums) {

    // Convert floats to strings (safe + accurate)
    $normalized = array_map('strval', $nums);

    $counts = array_count_values($normalized);
    $maxCount = max($counts);

    if ($maxCount === 1) {
        return "No mode";
    }

    $modes = array_keys($counts, $maxCount);

    // Convert back to numbers & return smallest (exam rule)
    $modes = array_map('floatval', $modes);
    sort($modes);

    return $modes[0];
}



function variance($nums, $sample = true) {
    $mean = mean($nums);
    $sum = 0;

    foreach ($nums as $n) {
        $sum += pow($n - $mean, 2);
    }

    return $sum / (count($nums) - ($sample ? 1 : 0));
}

function stddev($nums, $sample = true) {
    return sqrt(variance($nums, $sample));
}

/* -----------------------------
   PROCESS FORM
----------------------------- */

// ✅ FIX: preserve calc across POST
$calc = $_POST['calc'] ?? $_GET['calc'] ?? "";
$result = "";

if (isset($_POST['calculate'])) {
    $numbers = parseNumbers($_POST['numbers']);

    switch ($calc) {
        case "mean":
            $result = mean($numbers);
            saveHistory("Mean", $numbers, round($result, 4));
            break;

        case "median":
            $result = median($numbers);
            saveHistory("Median", $numbers, round($result, 4));
            break;

        case "mode":
            $result = mode($numbers);
            saveHistory("Mode", $numbers, $result);
            break;

        case "range":
            if (count($numbers) < 2) {
                $result = "Need at least 2 numbers";
            } else {
                $result = max($numbers) - min($numbers);
                saveHistory("Range", $numbers, round($result, 4));
            }
            break;

        case "sample_variance":
            $result = variance($numbers, true);
            saveHistory("Sample Variance", $numbers, round($result, 4));
            break;

        case "population_variance":
            $result = variance($numbers, false);
            saveHistory("Population Variance", $numbers, round($result, 4));
            break;

        case "sample_std":
            $result = stddev($numbers, true);
            saveHistory("Sample Std Deviation", $numbers, round($result, 4));
            break;

        case "population_std":
            $result = stddev($numbers, false);
            saveHistory("Population Std Deviation", $numbers, round($result, 4));
            break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Statistic Calculations</title>
</head>

<body style="margin:0; font-family:Arial; background:#e7b68fe0; color:white;">

<div style="max-width:1100px; margin:40px auto; text-align:center;">

    <h1>Statistic Calculations</h1>

    <!-- CALCULATION CARDS -->
    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
        gap:25px;
        margin-bottom:40px;
    ">
        <?php
        $cards = [
            "mean" => "Mean",
            "median" => "Median",
            "mode" => "Mode",
            "range" => "Range",
            "sample_variance" => "Sample Variance",
            "population_variance" => "Population Variance",
            "sample_std" => "Sample Std Deviation",
            "population_std" => "Population Std Deviation"
        ];

        foreach ($cards as $key => $label) {
            echo "
            <a href='statistics.php?calc=$key' style='text-decoration:none;'>
                <div style=\"
                    background:white;
                    color:#5c0000;
                    padding:25px;
                    border-radius:15px;
                    font-size:18px;
                    font-weight:bold;
                    box-shadow:0 6px 15px rgba(0,0,0,0.3);
                \">
                    $label
                </div>
            </a>
            ";
        }
        ?>
    </div>

    <!-- INPUT FORM -->
    <?php if ($calc): ?>
        <div style="
            background:#ffffff;
            color:#5c0000;
            padding:30px;
            border-radius:15px;
            max-width:500px;
            margin:0 auto;
        ">
            <h2><?= strtoupper(str_replace("_", " ", $calc)) ?></h2>

            <form method="post">

                <!-- ✅ THIS IS THE MISSING LINE -->
                <input type="hidden" name="calc" value="<?= htmlspecialchars($calc) ?>">

                <p>Enter numbers (comma separated):</p>

                <input type="text" name="numbers"
                       value="<?= htmlspecialchars($_POST['numbers'] ?? '') ?>"
                       style="width:90%; padding:10px; font-size:16px;"
                       required>

                <br><br>

                <input type="submit" name="calculate" value="Calculate"
                       style="
                           padding:10px 25px;
                           font-size:16px;
                           background:#5c0000;
                           color:white;
                           border:none;
                           cursor:pointer;
                       ">
            </form>

            <?php if ($result !== ""): ?>
                <h3 style="margin-top:20px;">
                    Result: <?= $result ?>
                </h3>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>

<?php include "inc_footer.php"; ?>
