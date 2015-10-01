<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$works_records = $gui->select('works');
$media_records = $gui->joinThree('works','media_for_works','media','media_title','work_id','media_id');
$categories_records = $gui->joinThree('works','categories_for_works','categories','category','work_id','category_id');
$techniques_records = $gui->joinThree('works','techniques_for_works','techniques','technique','work_id','technique_id');
$technologies_records = $gui->joinThree('works','technologies_for_works','technologies','technology','work_id','technology_id');

if(!empty($_POST)){
	$post_works = array_slice($_POST, 0, 2);
	$db->table('works');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($post_works, [
		'work_title' => [
			'required'=>true
		],
		'work_description' => [
			'required'=>false
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($post_works)){
			$last_id = $db->lastID();
			if($_POST['media']){
				$post_media = $_POST['media'];
				for($m = 0; $m < count($post_media);$m++){
					$insert_media = ['media_id'=>$post_media[$m],'work_id'=>$last_id];
					$db->table('media_for_works')->insert($insert_media);
				}
			}
			if($_POST['category']){
				$post_category = $_POST['category'];
				for($n = 0; $n < count($post_category);$n++){
					$insert_category = ['category_id'=>$post_category[$n],'work_id'=>$last_id];
					$db->table('categories_for_works')->insert($insert_category);
				}
			}
			if($_POST['technique']){
				$post_technique = $_POST['technique'];
				for($n = 0; $n < count($post_technique);$n++){
					$insert_technique = ['technique_id'=>$post_technique[$n],'work_id'=>$last_id];
					$db->table('techniques_for_works')->insert($insert_technique);
				}
			}
			if($_POST['technology']){
				$post_technology = $_POST['technology'];
				for($n = 0; $n < count($post_technology);$n++){
					$insert_technology = ['technology_id'=>$post_technology[$n],'work_id'=>$last_id];
					$db->table('technologies_for_works')->insert($insert_technology);
				}
			}
			header('Location: create_works.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create works</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Works</h3>

		<?php
		if(!count($works_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>work_title</th>
						<th>work_description</th>
						<th>media</th>
						<th>category</th>
						<th>technique</th>
						<th>technology</th>
					</tr>
				</thead>
				<tbody>

					<?php
					for($i = 0; $i < count($works_records) ;$i++){
					?>
						<tr>
							<?php
								$gui->showElement($works_records[$i],'id');
								$gui->showElement($works_records[$i],'work_title');
								$gui->showElement($works_records[$i],'work_description'); //style max-width:300px
								$gui->showMultipleElements($media_records,$works_records[$i],'media_title');
								$gui->showMultipleElements($categories_records,$works_records[$i],'category');
								$gui->showMultipleElements($techniques_records,$works_records[$i],'technique');
								$gui->showMultipleElements($technologies_records,$works_records[$i],'technology');
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
			<h4>Add new work</h4>
			<?php
				$gui->inputText('work_title');
				$gui->textArea('work_description',4,50);
				$gui->selectMultiple('media','media','media_title','create_media.php','Nova mídia +');
				$gui->selectMultiple('category','categories','category','create_categories.php','Nova categoria +');
				$gui->selectMultiple('technique','techniques','technique','create_techniques.php','Nova técnica +');
				$gui->selectMultiple('technology','technologies','technology','create_technologies.php','Nova tecnologia +');
			?>
			<input type="submit" value="Inserir">
		</form>
	</body>
</html>





