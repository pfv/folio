<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$people_records = $gui->select('people');

if(!empty($_POST)){
	$db->table('people');
	if($db->delete($_POST['id'])){
		$db->table('people_for_exhibitions_curatorship');
		$db->deleteWhich($_POST['id'],'person_id');
		$db->table('people_for_exhibitions_org');
		$db->deleteWhich($_POST['id'],'person_id');
		$db->table('people_for_exhibitions_participants');
		$db->deleteWhich($_POST['id'],'person_id');
		$db->table('people_for_projects_author');
		$db->deleteWhich($_POST['id'],'person_id');
		$db->table('people_for_projects_collaboration');
		$db->deleteWhich($_POST['id'],'person_id');
		header('Location: delete_people.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete people</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>People</h3>
		<h4>Delete person</h4>

		<?php
		if(!count($people_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>name</th>
						<th>website</th>
						<th>description</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($people_records as $pr){
					?>
						<tr>
							<?php
								$gui->showElement($pr,'id');
								$gui->showElement($pr,'name');
								$gui->showElement($pr,'website');
								$gui->showElement($pr,'description');
								$gui->deleteButton($pr);
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





