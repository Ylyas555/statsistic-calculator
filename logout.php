<?php
session_start();
session_unset();
session_destroy();
include "inc_header.php";

header("Location: login.php");
exit();
