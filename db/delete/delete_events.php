<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$events_records = $gui->select('events');
$places_records = $gui->joinThree('events','places_for_events','places','city','event_id','place_id');

if(!empty($_POST)){
	$db->table('events');
	if($db->delete($_POST['id'])){
		$db->table('places_for_events');
		$db->deleteWhich($_POST['id'],'event_id');
		$db->table('events_for_exhibitions');
		$db->deleteWhich($_POST['id'],'event_id');
		header('Location: delete_events.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete events</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Events</h3>
		<h4>Delete event</h4>

		<?php
		if(!count($events_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>event</th>
						<th>year</th>
						<th>place</th>
					</tr>
				</thead>
				<tbody>

					<?php
					for($i = 0; $i < count($events_records) ;$i++){
					?>
						<tr>
							<?php
								$gui->showElement($events_records[$i],'id');
								$gui->showElement($events_records[$i],'event');
								$gui->showElement($events_records[$i],'year');
								$gui->showMultipleElements($places_records,$events_records[$i],'city');
								$gui->deleteButton($events_records[$i]);
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





