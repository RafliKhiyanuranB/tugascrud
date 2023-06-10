<?php

if (!isset($_SESSION['user'])){
    header("Location: /phpcrud/phpcrud");
}

include 'functions.php';


$_SESSION['user'] = null;

header("Location: /phpcrud/phpcrud");
