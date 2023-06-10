<?php
include 'functions.php';

if (!isset($_SESSION['user'])){
    header("Location: /phpcrud/phpcrud");
}


// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;


// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM tugas ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_contacts = $pdo->query('SELECT COUNT(*) FROM tugas')->fetchColumn();
?>


<?=template_header('Read')?>

<?php $no = 1; ?>

<div class="content read">
    
	<h2>Read CRUD</h2>
	<a href="create.php" class="create-contact">Create CRUD</a>
	<table>
        <thead>
            <tr>
                <!-- <td>#</td> -->
                <td>No</td>
                <td>Username</td>
                <td>Image</td>
                <!-- <td>Password</td> -->
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
            <tr>
                <!-- <td><?=$contact['id']?></td> -->
                <td>
                    <?= $no++; ?>
                </td>
                <td><?=$contact['username']?></td>
                <td> 
                     <img src="<?=$contact['image']?>" width="100px" alt="">
                </td>
                <!-- <td><?=$contact['password']?></td> -->
                <td class="actions">
                    <a href="update.php?id=<?=$contact['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete.php?id=<?=$contact['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_contacts): ?>
		<a href="read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>