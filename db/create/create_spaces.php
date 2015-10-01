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
	$post_space = $_POST['space'];
	$db->table('spaces');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($_POST, [
		'space' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		$insert_space = ['space'=>$post_space];
		if($db->insert($insert_space)){
			$last_id = $db->lastID();
			if($_POST['place']){
				$post_place = $_POST['place'];
				for($m = 0; $m < count($post_place);$m++){
					$insert_place = ['place_id'=>$post_place[$m],'space_id'=>$last_id];
					$db->table('places_for_spaces')->insert($insert_place);
				}
			}
			header('Location: create_spaces.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create spaces</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Spaces</h3>

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
			<h4>Add new space</h4>
			<?php
				$gui->inputText('space');
				$gui->selectMultiple('place','places','city','create_places.php','Novo lugar +');
			?>
			<input type="submit" value="Inserir">
		</form>

	</body>
</html>





