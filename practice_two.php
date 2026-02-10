<?php
session_start();
include "inc_header.php";
include "inc_navigation.php";

/* ---------------------------------
   HELPER FUNCTIONS
--------------------------------- */
function randomNumbers($count = 5, $min = 1, $max = 10) {
    $nums = [];
    for ($i = 0; $i < $count; $i++) {
        $nums[] = rand($min, $max);
    }
    return $nums;
}

function mean($nums) {
    return array_sum($nums) / count($nums);
}

function populationVariance($nums) {
    $m = mean($nums);
    $sum = 0;
    foreach ($nums as $n) {
        $sum += pow($n - $m, 2);
    }
    return $sum / count($nums);
}

function sampleVariance($nums) {
    $m = mean($nums);
    $sum = 0;
    foreach ($nums as $n) {
        $sum += pow($n - $m, 2);
    }
    return $sum / (count($nums) - 1);
}

/* ---------------------------------
   SECTIONS (SAFE KEYS)
--------------------------------- */
$sections = [
    "sample_sd" => "Sample Standard Deviation",
    "population_sd" => "Population Standard Deviation",
    "sample_variance" => "Sample Variance",
    "population_variance" => "Population Variance"
];

/* ---------------------------------
   INITIALIZE QUESTIONS ONCE
--------------------------------- */
if (!isset($_SESSION['practice_two'])) {
    $_SESSION['practice_two'] = [];

    foreach ($sections as $key => $label) {
        $nums = randomNumbers();

        switch ($key) {
            case "sample_sd":
                $answer = round(sqrt(sampleVariance($nums)), 2);
                break;
            case "population_sd":
                $answer = round(sqrt(populationVariance($nums)), 2);
                break;
            case "sample_variance":
                $answer = round(sampleVariance($nums), 2);
                break;
            case "population_variance":
                $answer = round(populationVariance($nums), 2);
                break;
        }

        $_SESSION['practice_two'][$key] = [
            "label"  => $label,
            "nums"   => $nums,
            "answer" => $answer
        ];
    }
}

$questions = $_SESSION['practice_two'];

/* ---------------------------------
   HANDLE SECTION SUBMIT
--------------------------------- */
$results = [];

foreach ($sections as $key => $label) {
    if (isset($_POST["submit_$key"])) {
        $userAnswer = trim($_POST[$key] ?? "");

        if ($userAnswer === "") {
            $results[$key] = "empty";
        } else {
            $results[$key] = [
                "correct" => (round((float)$userAnswer, 2) == $questions[$key]['answer']),
                "user"    => $userAnswer
            ];
        }
    }
}

/* ---------------------------------
   RESET BUTTON
--------------------------------- */
if (isset($_POST['reset'])) {
    unset($_SESSION['practice_two']);
    header("Location: practice_two.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Practice Two</title>
</head>

<body style="margin:0; font-family:Arial; background:#f2c6a0; color:#5c0000;">

<div style="max-width:900px; margin:40px auto;">

    <h1 style="text-align:center;">
        Practice: Standard Deviation & Variance
    </h1>

    <form method="post">

        <?php foreach ($questions as $key => $q): ?>
            <div style="background:white; padding:25px; margin-bottom:30px; border-radius:10px;">

                <h2><?= $q['label'] ?></h2>

                <p>
                    Find the <?= $q['label'] ?> of:
                    <?= implode(", ", $q['nums']) ?>
                </p>

                <input type="text"
                       name="<?= $key ?>"
                       style="width:300px; padding:8px;">

                <?php if (isset($results[$key])): ?>

                    <?php if ($results[$key] === "empty"): ?>
                        <span style="color:orange; font-weight:bold;">
                            ⚠ Enter an answer
                        </span>

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

                <div style="text-align:center;">
                    <input type="submit"
                           name="submit_<?= $key ?>"
                           value="Check <?= $q['label'] ?>"
                           style="padding:12px 30px; background:#5c0000; color:white; border:none;">
                </div>

            </div>
        <?php endforeach; ?>

        <div style="text-align:center;">
            <input type="submit"
                   name="reset"
                   value="New Questions"
                   style="padding:10px 25px; font-size:14px;">
        </div>

    </form>

</div>
</body>
</html>
<?php include "inc_footer.php"; ?>