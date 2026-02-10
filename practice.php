<?php
session_start();


include "inc_header.php";
include "inc_navigation.php";

/* -----------------------------
   HELPER FUNCTION
----------------------------- */
function randomNumbers($count, $min = 1, $max = 20) {
    $nums = [];
    for ($i = 0; $i < $count; $i++) {
        $nums[] = rand($min, $max);
    }
    return $nums;
}

/* -----------------------------
   INITIALIZE QUESTIONS ONCE
----------------------------- */
if (!isset($_SESSION['practice_one'])) {

    $_SESSION['practice_one'] = [
        "Mean" => [
            ["nums" => randomNumbers(4)],
            ["nums" => randomNumbers(3)]
        ],
        "Median" => [
            ["nums" => randomNumbers(5)],
            ["nums" => randomNumbers(4)]
        ],
        "Mode" => [
            ["nums" => array_merge([rand(1,5), rand(1,5)], randomNumbers(3,1,5))],
            ["nums" => array_merge([rand(1,5), rand(1,5)], randomNumbers(2,1,5))]
        ],
        "Range" => [
            ["nums" => randomNumbers(3)],
            ["nums" => randomNumbers(3)]
        ]
    ];

    /* CALCULATE ANSWERS ONCE */
    /* CALCULATE ANSWERS ONCE */
foreach ($_SESSION['practice_one'] as $topic => $qs) {

    foreach ($qs as $i => $q) {

        $nums = $q['nums'];

        switch ($topic) {

            case "Mean":
                $q['answer'] = array_sum($nums) / count($nums);
                $q['question'] = "Find the mean of: " . implode(", ", $nums);
                break;

            case "Median":
                $sorted = $nums;
                sort($sorted);
                $count = count($sorted);

                $q['answer'] = ($count % 2)
                    ? $sorted[floor($count / 2)]
                    : ($sorted[$count/2 - 1] + $sorted[$count/2]) / 2;

                $q['question'] = "Find the median of: " . implode(", ", $nums);
                break;

            case "Mode":
                $counts = array_count_values($nums);
                $maxCount = max($counts);

                if ($maxCount === 1) {
                    $q['answer'] = "No mode";
                } else {
                    $modes = array_keys($counts, $maxCount);
                    sort($modes);
                    $q['answer'] = $modes[0];
                }

                $q['question'] = "Find the mode of: " . implode(", ", $nums);
                break;

            case "Range":
                $q['answer'] = max($nums) - min($nums);
                $q['question'] = "Find the range of: " . implode(", ", $nums);
                break;
        }

        // ✅ WRITE BACK EXPLICITLY
        $_SESSION['practice_one'][$topic][$i] = $q;
    }
}
unset($q);
unset($qs);
}


$questions = $_SESSION['practice_one'];

/* -----------------------------
   HANDLE SECTION SUBMIT
----------------------------- */
$results = [];

foreach ($questions as $topic => $qs) {
    if (isset($_POST["submit_$topic"])) {
        foreach ($qs as $i => $q) {
            $key = $topic . "_" . $i;
            $userAnswer = trim($_POST[$key] ?? "");

            if ($userAnswer === "") {
                $results[$key] = "empty";
            } else {
                $results[$key] = [
                    "correct" => ((string)$userAnswer === (string)$q['answer']),
                    "user" => $userAnswer
                ];
            }
        }
    }
}

/* RESET */
if (isset($_POST['reset'])) {
    unset($_SESSION['practice_one']);
    header("Location: practice.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Practice Statistics</title>
</head>

<body style="margin:0; font-family:Arial; background:#f2c6a0; color:#5c0000;">
<div style="max-width:1000px; margin:40px auto;">
<h1 style="text-align:center;">Practice Statistics</h1>

<form method="post">

<?php foreach ($questions as $topic => $qs): ?>
<div style="background:white; padding:25px; margin-bottom:30px; border-radius:10px;">
<h2><?= $topic ?></h2>

<?php foreach ($qs as $i => $q):
    $key = $topic . "_" . $i;
?>
<p><strong><?= $i+1 ?>.</strong> <?= $q['question'] ?></p>

<input type="text" name="<?= $key ?>" style="width:300px; padding:8px;">

<?php if (isset($results[$key])): ?>
    <?php if ($results[$key] === "empty"): ?>
        <span style="color:orange;">⚠ Enter an answer</span>
    <?php elseif ($results[$key]['correct']): ?>
        <span style="color:green; font-weight:bold;">
            ✔ Correct, your answer is <?= $results[$key]['user'] ?>
        </span>
    <?php else: ?>
        <span style="color:red; font-weight:bold;">
            ✖ Incorrect, correct answer is <?= $q['answer'] ?>
        </span>
    <?php endif; ?>
<?php endif; ?>

<br><br>
<?php endforeach; ?>

<div style="text-align:center;">
<input type="submit" name="submit_<?= $topic ?>" value="Check <?= $topic ?>"
style="padding:12px 30px; background:#5c0000; color:white; border:none;">
</div>
</div>
<?php endforeach; ?>

<div style="text-align:center;">
<input type="submit" name="reset" value="New Questions">
</div>

</form>
</div>
</body>
</html>

<?php include "inc_footer.php"; ?>
