<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$exhibitions_records = $gui->select('exhibitions');
$exhibition_types_records = $gui->rightJoin('type','exhibition_types','exhibition_type');
$exhibition_kinds_records = $gui->rightJoin('kind','exhibition_kinds','exhibition_kind');
$events_records = $gui->joinThree('exhibitions','events_for_exhibitions','events','event','exhibition_id','event_id');
$spaces_records = $gui->joinThree('exhibitions','spaces_for_exhibitions','spaces','space','exhibition_id','space_id');
$places_records = $gui->joinThree('exhibitions','places_for_exhibitions','places','city','exhibition_id','place_id');
$projects_records = $gui->joinThree('exhibitions','exhibitions_for_projects','projects','project','exhibition_id','project_id');
$institutions_records = $gui->joinThree('exhibitions','institutions_for_exhibitions','institutions','institution','exhibition_id','institution_id');
$people_org_records = $gui->joinThree('exhibitions','people_for_exhibitions_org','people','name','exhibition_id','person_id');
$people_curatorship_records = $gui->joinThree('exhibitions','people_for_exhibitions_curatorship','people','name','exhibition_id','person_id');
$people_participants_records = $gui->joinThree('exhibitions','people_for_exhibitions_participants','people','name','exhibition_id','person_id');

if(!empty($_POST)){
	$db->table('exhibitions');
	if($db->delete($_POST['id'])){
		$db->table('events_for_exhibitions');
		$db->deleteWhich($_POST['id'],'exhibition_id');
		$db->table('spaces_for_exhibitions');
		$db->deleteWhich($_POST['id'],'exhibition_id');
		$db->table('places_for_exhibitions');
		$db->deleteWhich($_POST['id'],'exhibition_id');
		$db->table('projects_for_exhibitions');
		$db->deleteWhich($_POST['id'],'exhibition_id');
		$db->table('institutions_for_exhibitions');
		$db->deleteWhich($_POST['id'],'exhibition_id');
		$db->table('people_for_exhibitions_org');
		$db->deleteWhich($_POST['id'],'exhibition_id');
		$db->table('people_for_exhibitions_curatorship');
		$db->deleteWhich($_POST['id'],'exhibition_id');
		$db->table('people_for_exhibitions_participants');
		$db->deleteWhich($_POST['id'],'exhibition_id');
		$db->table('exhibitions_for_projects');
		$db->deleteWhich($_POST['id'],'exhibition_id');
		header('Location: delete_exhibitions.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete exhibitions</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Exhibitions</h3>
		<h4>Delete exhibition</h4>

		<?php
		if(!count($exhibitions_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>type</th>
						<th>kind</th>
						<th>exhibition</th>
						<th>year_start</th>
						<th>year_end</th>
						<th>event</th>
						<th>space</th>
						<th>place</th>
						<th>project</th>
						<th>institution</th>
						<th>person_org</th>
						<th>person_curatorship</th>
						<th>person_participant</th>
					</tr>
				</thead>
				<tbody>

					<?php
					for($i = 0; $i < count($exhibitions_records) ;$i++){
					?>
						<tr>
							<?php
								$gui->showElement($exhibitions_records[$i],'id');
								$gui->showElement($exhibition_types_records[$i],'exhibition_type');
								$gui->showElement($exhibition_kinds_records[$i],'exhibition_kind');
								$gui->showElement($exhibitions_records[$i],'exhibition');
								$gui->showElement($exhibitions_records[$i],'year_start');
								$gui->showElement($exhibitions_records[$i],'year_end');
								$gui->showMultipleElements($events_records,$exhibitions_records[$i],'event');
								$gui->showMultipleElements($spaces_records,$exhibitions_records[$i],'space');
								$gui->showMultipleElements($places_records,$exhibitions_records[$i],'city');
								$gui->showMultipleElements($projects_records,$exhibitions_records[$i],'project');
								$gui->showMultipleElements($institutions_records,$exhibitions_records[$i],'institution');
								$gui->showMultipleElements($people_org_records,$exhibitions_records[$i],'name');
								$gui->showMultipleElements($people_curatorship_records,$exhibitions_records[$i],'name');
								$gui->showMultipleElements($people_participants_records,$exhibitions_records[$i],'name');
								$gui->deleteButton($exhibitions_records[$i]);
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





