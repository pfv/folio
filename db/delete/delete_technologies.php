<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$technologies_records = $gui->select('technologies');

if(!empty($_POST)){
	$db->table('technologies');
	if($db->delete($_POST['id'])){
		$db->table('technologies_for_projects');
		$db->deleteWhich($_POST['id'],'technology_id');
		$db->table('technologies_for_works');
		$db->deleteWhich($_POST['id'],'technology_id');
		header('Location: delete_technologies.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete technologies</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Technologies</h3>
		<h4>Delete technology</h4>

		<?php
		if(!count($technologies_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>technology</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($technologies_records as $tr){
					?>
						<tr>
							<?php
								$gui->showElement($tr,'id');
								$gui->showElement($tr,'technology');
								$gui->deleteButton($tr);
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






