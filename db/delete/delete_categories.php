<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$categories_records = $gui->select('categories');

if(!empty($_POST)){
	// echo '<pre>',print_r($_POST['id']),'</pre>';
	$db->table('categories');
		if($db->delete($_POST['id'])){
			$db->table('categories_for_projects');
			$db->deleteWhich($_POST['id'],'category_id');
			$db->table('categories_for_works');
			$db->deleteWhich($_POST['id'],'category_id');
			header('Location: delete_categories.php');
			die();
		}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete categories</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Categories</h3>
		<h4>Delete category</h4>

		<?php
		if(!count($categories_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>category</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($categories_records as $cr){
					?>
						<tr>
							<?php
								$gui->showElement($cr,'id');
								$gui->showElement($cr,'category');
								$gui->deleteButton($cr);
							?>
						</tr>
					<?php
					}
					?>

				</tbody>
			</table>

		<?php
		}
		?>

	</body>
</html>






