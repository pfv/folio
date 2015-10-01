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
	$post_collections = array_slice($_POST, 0, 3);
	$db->table('collections');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($post_collections, [
		'type' => [
			'required'=>false
		],
		'collection' => [
			'required'=>true
		],
		'year' => [
			'required'=>false
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($post_collections)){
			$last_id = $db->lastID();
			if($_POST['place']){
				$post_place = $_POST['place'];
				for($m = 0; $m < count($post_place);$m++){
					$insert_place = ['place_id'=>$post_place[$m],'collection_id'=>$last_id];
					$db->table('places_for_collections')->insert($insert_place);
				}
			}
			if($_POST['institution']){
				$post_institution = $_POST['institution'];
				for($n = 0; $n < count($post_institution);$n++){
					$insert_institution = ['institution_id'=>$post_institution[$n],'collection_id'=>$last_id];
					$db->table('institutions_for_collections')->insert($insert_institution);
				}
			}
			header('Location: create_collections.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create collections</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Collections</h3>

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
			<h4>Add new collection</h4>
			<?php
				$gui->selectOptions('type','collection_types','collection_type','create_collection_types.php','Novo tipo +');
				$gui->inputText('collection');
				$gui->inputText('year');
				$gui->selectMultiple('place','places','city','create_places.php','Novo lugar +');
				$gui->selectMultiple('institution','institutions','institution','create_institutions.php','Nova instituição +');
			?>
			<input type="submit" value="Inserir">
		</form>
	</body>
</html>





