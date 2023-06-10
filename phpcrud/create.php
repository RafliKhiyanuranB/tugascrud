<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';


if (!isset($_SESSION['user'])){
    header("Location: /phpcrud/phpcrud");
}



// Check if POST data is not empty
if (!empty($_POST) && !empty($_FILES['img'])) {
    // Post data not empty insert a new record
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables

    $photo = $_FILES['img'];
    $opt = $_POST['username'];

    // upload photo
    $status = move_uploaded_file($photo['tmp_name'], __DIR__ . "\..\img\\" . $opt . "-" . $photo['name']);


    $img =  "..\img\\" . $opt . "-" . $photo['name'];
    $username =  $_POST['username'];
    $password =  password_hash($_POST["password"],PASSWORD_DEFAULT);




    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO tugas VALUES (?, ?, ?, ?)');
    $stmt->execute([null, $username, $img, $password]);
    // Output messaged
    $msg = 'Created Successfully!';
    header("location:/phpcrud/phpcrud/read.php");
}
?>


<?=template_header('Create')?>

<div class="content update">
	<h2>Create Crud</h2>
    <form action="create.php" method="post" enctype="multipart/form-data">
        <!-- Username -->
        <label for="nama">Username</label>
        <input type="text" name="username" id="nama">


        <label for="img">Image</label>
        <input type="file" name="img" id="img">


        <label for="password">password</label>
        <input type="password" name="password" id="password">

        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>