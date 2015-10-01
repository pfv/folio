<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$spaces_records = $gui->select('spaces');
$places_records = $gui->joinThree('spaces','places_for_spaces','places','city','space_id','place_id');

if(!empty($_POST)){
	$db->table('spaces');
	if($db->delete($_POST['id'])){
		$db->table('places_for_spaces');
		$db->deleteWhich($_POST['id'],'space_id');
		$db->table('spaces_for_exhibitions');
		$db->deleteWhich($_POST['id'],'space_id');
		header('Location: delete_spaces.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete spaces</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Spaces</h3>
		<h4>Delete space</h4>

		<?php
		if(!count($spaces_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>space</th>
						<th>place</th>
					</tr>
				</thead>
				<tbody>

					<?php
					for($i = 0; $i < count($spaces_records) ;$i++){
					?>
						<tr>
							<?php
								$gui->showElement($spaces_records[$i],'id');
								$gui->showElement($spaces_records[$i],'space');
								$gui->showMultipleElements($places_records,$spaces_records[$i],'city');
								$gui->deleteButton($spaces_records[$i]);
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





