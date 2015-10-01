<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$prize_types_records = $gui->select('prize_types');

if(!empty($_POST)){
	$db->table('prize_types');
	if($db->delete($_POST['id'])){
		header('Location: delete_prize_types.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete prize types</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Prize types</h3>
		<h4>Delete prize type</h4>

		<?php
		if(!count($prize_types_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>prize_type</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($prize_types_records as $ptr){
					?>
						<tr>
							<?php
								$gui->showElement($ptr,'id');
								$gui->showElement($ptr,'prize_type');
								$gui->deleteButton($ptr);
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






