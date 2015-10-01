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
	$db->table('works');
	if($db->delete($_POST['id'])){
		$db->table('media_for_works');
		$db->deleteWhich($_POST['id'],'work_id');
		$db->table('categories_for_works');
		$db->deleteWhich($_POST['id'],'work_id');
		$db->table('techniques_for_works');
		$db->deleteWhich($_POST['id'],'work_id');
		$db->table('technologies_for_works');
		$db->deleteWhich($_POST['id'],'work_id');
		$db->table('works_for_projects');
		$db->deleteWhich($_POST['id'],'work_id');
		header('Location: delete_works.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete works</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Works</h3>
		<h4>Delete work</h4>

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
								$gui->deleteButton($works_records[$i]);
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





