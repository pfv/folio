<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$collections_records = $gui->select('collections');
$collection_types_records = $gui->rightJoin('type','collection_types','collection_type');
$places_records = $gui->joinThree('collections','places_for_collections','places','city','collection_id','place_id');
$institutions_records = $gui->joinThree('collections','institutions_for_collections','institutions','institution','collection_id','institution_id');

if(!empty($_POST)){
	$db->table('collections');
		if($db->delete($_POST['id'])){
			$db->table('places_for_collections');
			$db->deleteWhich($_POST['id'],'collection_id');
			$db->table('institutions_for_collections');
			$db->deleteWhich($_POST['id'],'collection_id');
			$db->table('collections_for_projects');
			$db->deleteWhich($_POST['id'],'collection_id');
			header('Location: delete_collections.php');
			die();
		}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete collections</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Collections</h3>
		<h4>Delete collection</h4>

		<?php
		if(!count($collections_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>type</th>
						<th>collection</th>
						<th>year</th>
						<th>place</th>
						<th>institution</th>
					</tr>
				</thead>
				<tbody>

					<?php
					for($i = 0; $i < count($collections_records) ;$i++){
					?>
						<tr>
							<?php
								$gui->showElement($collections_records[$i],'id');
								$gui->showElement($collection_types_records[$i],'collection_type');
								$gui->showElement($collections_records[$i],'collection');
								$gui->showElement($collections_records[$i],'year');
								$gui->showMultipleElements($places_records,$collections_records[$i],'city');
								$gui->showMultipleElements($institutions_records,$collections_records[$i],'institution');
								$gui->deleteButton($collections_records[$i]);
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





