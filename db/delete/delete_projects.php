<?php

require '../../classes/Database.php';
require '../../classes/Validator.php';
require '../../classes/ErrorHandler.php';
require '../../classes/AdminGui.php';
require '../../functions/security.php';

$errorHandler = new ErrorHandler;

$db = new Database;
$gui = new AdminGui($db);

$projects_records = $gui->select('projects');
$project_types_records = $gui->rightJoin('type','project_types','given_name');
$categories_records = $gui->joinThree('projects','categories_for_projects','categories','category','project_id','category_id');
$companies_records = $gui->joinThree('projects','companies_for_projects','companies','company','project_id','company_id');
$techniques_records = $gui->joinThree('projects','techniques_for_projects','techniques','technique','project_id','technique_id');
$technologies_records = $gui->joinThree('projects','technologies_for_projects','technologies','technology','project_id','technology_id');
$places_records = $gui->joinThree('projects','places_for_projects','places','city','project_id','place_id');
$media_records = $gui->joinThree('projects','media_for_projects','media','media_title','project_id','media_id');
$works_records = $gui->joinThree('projects','works_for_projects','works','work_title','project_id','work_id');
$functions_records = $gui->joinThree('projects','functions_for_projects','functions','function','project_id','function_id');
$people_authors_records = $gui->joinThree('projects','people_for_projects_author','people','name','project_id','person_id');
$people_collaborators_records = $gui->joinThree('projects','people_for_projects_collaboration','people','name','project_id','person_id');
$exhibitions_records = $gui->joinThree('projects','exhibitions_for_projects','exhibitions','exhibition','project_id','exhibition_id');
$prizes_records = $gui->joinThree('projects','prizes_for_projects','prizes','name','project_id','prize_id');
$collections_records = $gui->joinThree('projects','collections_for_projects','collections','collection','project_id','collection_id');

if(!empty($_POST)){
	$db->table('projects');
	if($db->delete($_POST['id'])){
		$db->table('categories_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('companies_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('techniques_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('technologies_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('places_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('media_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('works_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('functions_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('people_for_projects_author');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('people_for_projects_collaboration');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('exhibitions_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('prizes_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('collections_for_projects');
		$db->deleteWhich($_POST['id'],'project_id');
		$db->table('projects_for_exhibitions');
		$db->deleteWhich($_POST['id'],'project_id');
		header('Location: delete_projects.php');
		die();
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Delete projects</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Projects</h3>
		<h4>Delete project</h4>

		<?php
		if(!count($projects_records)){
			echo 'Tabela vazia';
		}
		else{
		?>

			<table>
				<thead>
					<tr>
						<th class="id_field">id</th>
						<th>type</th>
						<th>project</th>
						<th>short_description</th>
						<th>long_description</th>
						<th>year_start</th>
						<th>year_end</th>
						<th>priority</th>
						<th>active</th>
						<th>category</th>
						<th>company</th>
						<th>technique</th>
						<th>technology</th>
						<th>place</th>
						<th>media</th>
						<th>work</th>
						<th>function</th>
						<th>author</th>
						<th>collaborator</th>
						<th>exhibition</th>
						<th>prize</th>
						<th>collection</th>
					</tr>
				</thead>
				<tbody>

					<?php
					for($i = 0; $i < count($projects_records) ;$i++){
					?>
						<tr>
							<?php
								$gui->showElement($projects_records[$i],'id');
								$gui->showElement($project_types_records[$i],'given_name');
								$gui->showElement($projects_records[$i],'project');
								$gui->showElement($projects_records[$i],'short_description');
								$gui->showElement($projects_records[$i],'long_description');
								$gui->showElement($projects_records[$i],'year_start');
								$gui->showElement($projects_records[$i],'year_end');
								$gui->showElement($projects_records[$i],'priority');
								$gui->showElement($projects_records[$i],'active');
								$gui->showMultipleElements($categories_records,$projects_records[$i],'category');
								$gui->showMultipleElements($companies_records,$projects_records[$i],'company');
								$gui->showMultipleElements($techniques_records,$projects_records[$i],'technique');
								$gui->showMultipleElements($technologies_records,$projects_records[$i],'technology');
								$gui->showMultipleElements($places_records,$projects_records[$i],'city');
								$gui->showMultipleElements($media_records,$projects_records[$i],'media_title');
								$gui->showMultipleElements($works_records,$projects_records[$i],'work_title');
								$gui->showMultipleElements($functions_records,$projects_records[$i],'function');
								$gui->showMultipleElements($people_authors_records,$projects_records[$i],'name');
								$gui->showMultipleElements($people_collaborators_records,$projects_records[$i],'name');
								$gui->showMultipleElements($exhibitions_records,$projects_records[$i],'exhibition');
								$gui->showMultipleElements($prizes_records,$projects_records[$i],'name');
								$gui->showMultipleElements($collections_records,$projects_records[$i],'collection');
								$gui->deleteButton($projects_records[$i]);
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





