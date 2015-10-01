<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$institutions_records = $gui->select('institutions');
$places_records = $gui->joinThree('institutions','places_for_institutions','places','city','institution_id','place_id');

if(!empty($_POST)){
	$post_institution = $_POST['institution'];
	$db->table('institutions');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'institution' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		$insert_institution = ['institution'=>$post_institution];
		if($db->insert($insert_institution)){
			$last_id = $db->lastID();
			if($_POST['place']){
				$post_place = $_POST['place'];
				for($m = 0; $m < count($post_place);$m++){
					$insert_place = ['place_id'=>$post_place[$m],'institution_id'=>$last_id];
					$db->table('places_for_institutions')->insert($insert_place);
				}
			}
			header('Location: create_institutions.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create institutions</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Institutions</h3>

		<?php
		if(!count($institutions_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>institution</th>
						<th>place</th>
					</tr>
				</thead>
				<tbody>

					<?php
					for($i = 0; $i < count($institutions_records) ;$i++){
					?>
						<tr>
							<?php
								$gui->showElement($institutions_records[$i],'id');
								$gui->showElement($institutions_records[$i],'institution');
								$gui->showMultipleElements($places_records,$institutions_records[$i],'city');
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
			<h4>Add new institution</h4>
			<?php
				$gui->inputText('institution');
				$gui->selectMultiple('place','places','city','create_places.php','Novo lugar +');
			?>

			<input type="submit" value="Inserir">
		</form>

	</body>
</html>





