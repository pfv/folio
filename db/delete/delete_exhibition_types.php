<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$exhibition_types_records = $gui->select('exhibition_types');

if(!empty($_POST)){
	$db->table('exhibition_types');
	if($db->delete($_POST['id'])){
		header('Location: delete_exhibition_types.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete exhibition types</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Exhibition types</h3>
		<h4>Delete exhibition type</h4>

		<?php
		if(!count($exhibition_types_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>exhibition_type</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($exhibition_types_records as $etr){
					?>
						<tr>
							<?php
								$gui->showElement($etr,'id');
								$gui->showElement($etr,'exhibition_type');
								$gui->deleteButton($etr);
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






