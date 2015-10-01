<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$places_records = $gui->select('places');

if(!empty($_POST)){
	$db->table('places');
	if($db->delete($_POST['id'])){
		$db->table('places_for_collections');
		$db->deleteWhich($_POST['id'],'place_id');
		$db->table('places_for_events');
		$db->deleteWhich($_POST['id'],'place_id');
		$db->table('places_for_exhibitions');
		$db->deleteWhich($_POST['id'],'place_id');
		$db->table('places_for_institutions');
		$db->deleteWhich($_POST['id'],'place_id');
		$db->table('places_for_prizes');
		$db->deleteWhich($_POST['id'],'place_id');
		$db->table('places_for_projects');
		$db->deleteWhich($_POST['id'],'place_id');
		$db->table('places_for_spaces');
		$db->deleteWhich($_POST['id'],'place_id');

		header('Location: delete_places.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete places</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body style="font-family:Helvetica;font-size:12px">
		<h3>Places</h3>
		<h4>Delete place</h4>

		<?php
		if(!count($places_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>city</th>
						<th>state</th>
						<th>country</th>
					</tr>
				</thead>
				<tbody>

					<?php
					foreach($places_records as $pr){
					?>
						<tr>
							<?php
								$gui->showElement($pr,'id');
								$gui->showElement($pr,'city');
								$gui->showElement($pr,'state');
								$gui->showElement($pr,'country');
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





