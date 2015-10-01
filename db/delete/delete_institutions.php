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
	$db->table('institutions');
	if($db->delete($_POST['id'])){
		$db->table('places_for_institutions');
		$db->deleteWhich($_POST['id'],'institution_id');
		$db->table('institutions_for_collections');
		$db->deleteWhich($_POST['id'],'institution_id');
		$db->table('institutions_for_exhibitions');
		$db->deleteWhich($_POST['id'],'institution_id');
		$db->table('institutions_for_prizes');
		$db->deleteWhich($_POST['id'],'institution_id');
		header('Location: delete_institutions.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete institutions</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Institutions</h3>
		<h4>Delete institution</h4>

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
								$gui->deleteButton($institutions_records[$i]);
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





