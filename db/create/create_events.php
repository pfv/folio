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
	$post_event = array_slice($_POST, 0, 2);
	$db->table('events');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'event' => [
			'required'=>true
		],
		'year' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($post_event)){
			$last_id = $db->lastID();
			if($_POST['place']){
				$post_place = $_POST['place'];
				for($m = 0; $m < count($post_place);$m++){
					$insert_place = ['place_id'=>$post_place[$m],'event_id'=>$last_id];
					$db->table('places_for_events')->insert($insert_place);
				}
			}
			header('Location: create_events.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create events</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Events</h3>

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

		<form action="" method="post">
		<h4>Add new event</h4>
			<?php
				$gui->inputText('event');
				$gui->inputText('year');
				$gui->selectMultiple('place','places','city','create_places.php','Novo lugar +');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>





