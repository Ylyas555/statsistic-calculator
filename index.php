<?php
session_start();
include "inc_header.php";
include "inc_navigation.php";
?>
<body style="margin:0; font-family:Arial; background:#e7b68fe0; color:#5c0000;">

<div class="content" style="
    max-width:1400px;   /* wider container */
    margin:30px auto;
    text-align:center;
">

    <h2 style="margin-bottom:25px;">Welcome to Statistics Calculator</h2>

    <!-- IMAGE GRID -->
    <div style="
        display:grid;
        grid-template-columns:repeat(2, 1fr);
        gap:20px;       /* smaller gap = bigger images */
    ">

        <?php
        $images = [
            "mean.JPG",
            "median.JPG",
            "mode.JPG",
            "range.JPG",
            "sample-standard-deviation.JPG",
            "population-standard-deviation.JPG"
        ];

        foreach ($images as $img) {
            echo "
            <img src='images/$img'
                 alt=''
                 style='
                     width:100%;
                     height:auto;
                     border-radius:10px;
                     display:block;
                 '>
            ";
        }
        ?>

    </div>

</div>

<!-- RESPONSIVE -->
<style>
@media (max-width: 900px) {
    .content > div {
        grid-template-columns: 1fr !important;
    }
}
</style>


<?php include "inc_footer.php"; ?>
