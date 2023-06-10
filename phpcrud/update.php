<?php 
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (!isset($_SESSION['user'])){
    header("Location: /phpcrud/phpcrud/read.php");
}


// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['id'])) {
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM tugas WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Contact doesn\'t exist with that ID!');
    }

    if (!empty($_POST)) {

        // Username
        if ($contact['username'] != $_POST['username']) {
            $username = $_POST['username'];
        }else{
            $username = $contact['username'];
        }


        // img
        if (isset($_FILES['img'])) {
            $photo = $_FILES['img'];
            move_uploaded_file($photo['tmp_name'], __DIR__ . "\..\img\\" . $username . "-" . $photo['name']);
            $img =  "..\img\\" . $username . "-" . $photo['name'];
        } else {
        }


        // password
        if (isset($_POST['password']) && $_POST['password'] != "") {
            $password =  password_hash($_POST["password"],PASSWORD_DEFAULT);
        } else {
            $password = $contact['password'];
        }


        
        // Update the record
        $stmt = $pdo->prepare('UPDATE tugas SET username = ?, password = ?, image = ? WHERE id=?');
        $stmt->execute([$username, $password, $img, $_GET['id']]);
        $msg = 'Updated Successfully!';
        header("location:/phpcrud/phpcrud/read.php");
    }
} else {
    exit('No ID specified!');
}
?>



<?=template_header('Read')?>

<div class="content update">
	<h2>Update Crud</h2>
    <form action="update.php?id=<?=$contact['id']?>" method="post" enctype="multipart/form-data">
        <!-- Username -->
        <label for="nama">Username</label>
        <input type="text" name="username"  id="nama" value="<?= $contact['username'] ?>" >

        <label for="img">Image</label>
        <input type="file" name="img" id="img">

        <label for="password">password</label>
        <input type="password" name="password" id="password">

        <label for="password">preview</label>
        <img src="<?= $contact['image'] ?>" width="400px" alt="">
        
        <br>

        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>