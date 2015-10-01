<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$project_types_records = $gui->select('project_types');

if(!empty($_POST)){
	$db->table('project_types');
	if($db->delete($_POST['id'])){
		header('Location: delete_project_types.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete project types</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Project_types</h3>
		<h4>Delete project type</h4>

		<?php
		if(!count($project_types_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>name</th>
						<th>given_name</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($project_types_records as $ptr){
					?>
						<tr>
							<?php
								$gui->showElement($ptr,'id');
								$gui->showElement($ptr,'name');
								$gui->showElement($ptr,'given_name');
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






