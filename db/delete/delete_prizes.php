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
	$db->table('prizes');
	if($db->delete($_POST['id'])){
		$db->table('places_for_prizes');
		$db->deleteWhich($_POST['id'],'prize_id');
		$db->table('institutions_for_prizes');
		$db->deleteWhich($_POST['id'],'prize_id');
		$db->table('prizes_for_projects');
		$db->deleteWhich($_POST['id'],'prize_id');
		header('Location: delete_prizes.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete prizes</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Prizes</h3>
		<h4>Delete prize</h4>

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
								$gui->deleteButton($prizes_records[$i]);
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





