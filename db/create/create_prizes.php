<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$prizes_records = $gui->select('prizes');
$prize_types_records = $gui->rightJoin('type','prize_types','prize_type');
$places_records = $gui->joinThree('prizes','places_for_prizes','places','city','prize_id','place_id');
$institutions_records = $gui->joinThree('prizes','institutions_for_prizes','institutions','institution','prize_id','institution_id');

if(!empty($_POST)){
	$post_prizes = array_slice($_POST, 0, 4);
	$db->table('prizes');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($post_prizes, [
		'type' => [
			'required'=>false
		],
		'name' => [
			'required'=>true
		],
		'their_category' => [
			'required'=>false
		],
		'year' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($post_prizes)){
			$last_id = $db->lastID();
			if($_POST['place']){
				$post_place = $_POST['place'];
				for($m = 0; $m < count($post_place);$m++){
					$insert_place = ['place_id'=>$post_place[$m],'prize_id'=>$last_id];
					$db->table('places_for_prizes')->insert($insert_place);
				}
			}
			if($_POST['institution']){
				$post_institution = $_POST['institution'];
				for($n = 0; $n < count($post_institution);$n++){
					$insert_institution = ['institution_id'=>$post_institution[$n],'prize_id'=>$last_id];
					$db->table('institutions_for_prizes')->insert($insert_institution);
				}
			}
			header('Location: create_prizes.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create prizes</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Prizes</h3>

		<?php
		if(!count($prizes_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>type</th>
						<th>name</th>
						<th>their_category</th>
						<th>year</th>
						<th>place</th>
						<th>institution</th>
					</tr>
				</thead>
				<tbody>

					<?php
					for($i = 0; $i < count($prizes_records) ;$i++){
					?>
						<tr>
							<?php
								$gui->showElement($prizes_records[$i],'id');
								$gui->showElement($prize_types_records[$i],'prize_type');
								$gui->showElement($prizes_records[$i],'name');
								$gui->showElement($prizes_records[$i],'their_category');
								$gui->showElement($prizes_records[$i],'year');
								$gui->showMultipleElements($places_records,$prizes_records[$i],'city');
								$gui->showMultipleElements($institutions_records,$prizes_records[$i],'institution');
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
		<h4>Add new prize</h4>
			<?php
				$gui->selectOptions('type','prize_types','prize_type','create_prize_types.php','Novo tipo +');
				$gui->inputText('name');
				$gui->inputText('their_category');
				$gui->inputText('year');
				$gui->selectMultiple('place','places','city','create_places.php','Novo lugar +');
				$gui->selectMultiple('institution','institutions','institution','create_institutions.php','Nova instituição +');
			?>
			<input type="submit" value="Inserir">
		</form>
	</body>
</html>





