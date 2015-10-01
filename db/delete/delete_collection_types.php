<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$collection_types_records = $gui->select('collection_types');

if(!empty($_POST)){
	$db->table('collection_types');
		if($db->delete($_POST['id'])){
			header('Location: delete_collection_types.php');
			die();
		}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete collection types</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Collection types</h3>
		<h4>Delete collection type</h4>

		<?php
		if(!count($collection_types_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>collection_type</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($collection_types_records as $ctr){
					?>
						<tr>
							<?php
								$gui->showElement($ctr,'id');
								$gui->showElement($ctr,'collection_type');
								$gui->deleteButton($ctr);
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






