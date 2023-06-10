<?php
include 'functions.php';
// Your PHP code here.
if (isset($_SESSION['user'])){
    header("Location: /phpcrud/phpcrud/read.php");
}



$pdo = pdo_connect_mysql();
$msg = "";
// Home Page template below.
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $password = $_POST['password'];
    $username = $_POST['username'];

    // Insert new record into the contacts table
    $stmt = $pdo->prepare('SELECT * FROM tugas WHERE username=?');
    $stmt->execute([$username]);

	$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);


	$check = password_verify($password, $contacts[0]['password']);

	if ($check) {
		$_SESSION['user'] = $contacts[0];		
	} else {
		$msg = "Username atau password salah!";
	}


    header("Location: /phpcrud/phpcrud/read.php");


    // Output messaged
    $msg = 'Created Successfully!';
}

?>

<?=template_header('Home')?>

<div class="content update">
	<h2>Login Form</h2>
	<?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <!-- Username -->
        <label for="nama">Username</label>
        <input type="text" name="username" id="nama">

        <label for="password">password</label>
        <input type="password" name="password" id="password">

        <input type="submit" value="Log in">
    </form>
</div>

<?=template_footer()?>