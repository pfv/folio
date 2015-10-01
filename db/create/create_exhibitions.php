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
	$post_exhibitions = array_slice($_POST, 0, 5);
	$db->table('exhibitions');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($post_exhibitions, [
		'type' => [
			'required'=>false
		],
		'exhibition' => [
			'required'=>true
		],
		'year_start' => [
			'required'=>true
		],
		'year_end' => [
			'required'=>false
		],
		'kind' => [
			'required'=>false
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($post_exhibitions)){
			$last_id = $db->lastID();
			if($_POST['event']){
				$post_event = $_POST['event'];
				for($m = 0; $m < count($post_event);$m++){
					$insert_event = ['event_id'=>$post_event[$m],'exhibition_id'=>$last_id];
					$db->table('events_for_exhibitions')->insert($insert_event);
				}
			}
			if($_POST['space']){
				$post_space = $_POST['space'];
				for($m = 0; $m < count($post_space);$m++){
					$insert_space = ['space_id'=>$post_space[$m],'exhibition_id'=>$last_id];
					$db->table('spaces_for_exhibitions')->insert($insert_space);
				}
			}
			if($_POST['place']){
				$post_place = $_POST['place'];
				for($m = 0; $m < count($post_place);$m++){
					$insert_place = ['place_id'=>$post_place[$m],'exhibition_id'=>$last_id];
					$db->table('places_for_exhibitions')->insert($insert_place);
				}
			}
			if($_POST['project']){
				$post_project = $_POST['project'];
				for($m = 0; $m < count($post_project);$m++){
					$insert_project = ['project_id'=>$post_project[$m],'exhibition_id'=>$last_id];
					$db->table('exhibitions_for_projects')->insert($insert_project);
				}
			}
			if($_POST['institution']){
				$post_institution = $_POST['institution'];
				for($n = 0; $n < count($post_institution);$n++){
					$insert_institution = ['institution_id'=>$post_institution[$n],'exhibition_id'=>$last_id];
					$db->table('institutions_for_exhibitions')->insert($insert_institution);
				}
			}
			if($_POST['person_org']){
				$post_person_org = $_POST['person_org'];
				for($n = 0; $n < count($post_person_org);$n++){
					$insert_person_org = ['person_id'=>$post_person_org[$n],'exhibition_id'=>$last_id];
					$db->table('people_for_exhibitions_org')->insert($insert_person_org);
				}
			}
			if($_POST['person_curatorship']){
				$post_person_curatorship = $_POST['person_curatorship'];
				for($n = 0; $n < count($post_person_curatorship);$n++){
					$insert_person_curatorship = ['person_id'=>$post_person_curatorship[$n],'exhibition_id'=>$last_id];
					$db->table('people_for_exhibitions_curatorship')->insert($insert_person_curatorship);
				}
			}
			if($_POST['person_participant']){
				$post_person_participant = $_POST['person_participant'];
				for($n = 0; $n < count($post_person_participant);$n++){
					$insert_person_participant = ['person_id'=>$post_person_participant[$n],'exhibition_id'=>$last_id];
					$db->table('people_for_exhibitions_participants')->insert($insert_person_participant);
				}
			}
			header('Location: create_exhibitions.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create exhibitions</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Exhibitions</h3>

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
			<h4>Add new exhibition</h4>
			<?php
				$gui->selectOptions('type','exhibition_types','exhibition_type','create_exhibition_types.php','Novo tipo +');
				$gui->selectOptions('kind','exhibition_kinds','exhibition_kind','create_exhibition_kinds.php','Novo tipo +');
				$gui->inputText('exhibition');
				$gui->inputText('year_start');
				$gui->inputText('year_end');
				$gui->selectMultiple('event','events','event','create_events.php','Novo evento +');
				$gui->selectMultiple('space','spaces','space','create_spaces.php','Novo espaço +');
				$gui->selectMultiple('place','places','city','create_places.php','Novo lugar +');
				$gui->selectMultiple('project','projects','project','create_projects.php','Novo projeto +');
				$gui->selectMultiple('institution','institutions','institution','create_institutions.php','Nova instituição +');
				$gui->selectMultiple('person_org','people','name','create_people.php','Nova pessoa +');
				$gui->selectMultiple('person_curatorship','people','name','create_people.php','Nova pessoa +');
				$gui->selectMultiple('person_participant','people','name','create_people.php','Nova pessoa +');
			?>
			<input type="submit" value="Inserir">
		</form>
	</body>
</html>





