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
	$post_projects = array_slice($_POST, 0, 8);
	$db->table('projects');
	$validator = new Validator($db, $errorHandler);
	$validation = $validator->check($post_projects, [
		'type' => [
			'required'=>true
		],
		'project' => [
			'required'=>true
		],
		'short_description' => [
			'required'=>false
		],
		'long_description' => [
			'required'=>false
		],
		'year_start' => [
			'required'=>true
		],
		'year_end' => [
			'required'=>false
		],
		'priority' => [
			'required'=>true
		],
		'active' => [
			'required'=>true
		]
	]);

	if($validation->fails()){
		echo '<pre>',print_r($validation->errors()->all()),'</pre>';
	}
	else{
		if($db->insert($post_projects)){
			$last_id = $db->lastID();
			if($_POST['category']){
				$post_category = $_POST['category'];
				for($m = 0; $m < count($post_category);$m++){
					$insert_category = ['category_id'=>$post_category[$m],'project_id'=>$last_id];
					$db->table('categories_for_projects')->insert($insert_category);
				}
			}
			if($_POST['company']){
				$post_company = $_POST['company'];
				for($m = 0; $m < count($post_company);$m++){
					$insert_company = ['company_id'=>$post_company[$m],'project_id'=>$last_id];
					$db->table('companies_for_projects')->insert($insert_company);
				}
			}
			if($_POST['technique']){
				$post_technique = $_POST['technique'];
				for($m = 0; $m < count($post_technique);$m++){
					$insert_technique = ['technique_id'=>$post_technique[$m],'project_id'=>$last_id];
					$db->table('techniques_for_projects')->insert($insert_technique);
				}
			}
			if($_POST['technology']){
				$post_technology = $_POST['technology'];
				for($m = 0; $m < count($post_technology);$m++){
					$insert_technology = ['technology_id'=>$post_technology[$m],'project_id'=>$last_id];
					$db->table('technologies_for_projects')->insert($insert_technology);
				}
			}
			if($_POST['place']){
				$post_place = $_POST['place'];
				for($m = 0; $m < count($post_place);$m++){
					$insert_place = ['place_id'=>$post_place[$m],'project_id'=>$last_id];
					$db->table('places_for_projects')->insert($insert_place);
				}
			}
			if($_POST['media']){
				$post_media = $_POST['media'];
				for($m = 0; $m < count($post_media);$m++){
					$insert_media = ['media_id'=>$post_media[$m],'project_id'=>$last_id];
					$db->table('media_for_projects')->insert($insert_media);
				}
			}
			if($_POST['work']){
				$post_work = $_POST['work'];
				for($m = 0; $m < count($post_work);$m++){
					$insert_work = ['work_id'=>$post_work[$m],'project_id'=>$last_id];
					$db->table('works_for_projects')->insert($insert_work);
				}
			}
			if($_POST['function']){
				$post_function = $_POST['function'];
				for($m = 0; $m < count($post_function);$m++){
					$insert_function = ['function_id'=>$post_function[$m],'project_id'=>$last_id];
					$db->table('functions_for_projects')->insert($insert_function);
				}
			}
			if($_POST['author']){
				$post_author = $_POST['author'];
				for($m = 0; $m < count($post_author);$m++){
					$insert_author = ['person_id'=>$post_author[$m],'project_id'=>$last_id];
					$db->table('people_for_projects_author')->insert($insert_author);
				}
			}
			if($_POST['collaborator']){
				$post_collaborator = $_POST['collaborator'];
				for($m = 0; $m < count($post_collaborator);$m++){
					$insert_collaborator = ['person_id'=>$post_collaborator[$m],'project_id'=>$last_id];
					$db->table('people_for_projects_collaboration')->insert($insert_collaborator);
				}
			}
			if($_POST['exhibition']){
				$post_exhibition = $_POST['exhibition'];
				for($m = 0; $m < count($post_exhibition);$m++){
					$insert_exhibition = ['exhibition_id'=>$post_exhibition[$m],'project_id'=>$last_id];
					$db->table('exhibitions_for_projects')->insert($insert_exhibition);
				}
			}
			if($_POST['prize']){
				$post_prize = $_POST['prize'];
				for($m = 0; $m < count($post_prize);$m++){
					$insert_prize = ['prize_id'=>$post_prize[$m],'project_id'=>$last_id];
					$db->table('prizes_for_projects')->insert($insert_prize);
				}
			}
			if($_POST['collection']){
				$post_collection = $_POST['collection'];
				for($m = 0; $m < count($post_collection);$m++){
					$insert_collection = ['collection_id'=>$post_collection[$m],'project_id'=>$last_id];
					$db->table('collections_for_projects')->insert($insert_collection);
				}
			}
			header('Location: create_projects.php');
			die();
		}
	}
}
?>

<!doctype html>
	<html>
	<head>
		<title>Create projects</title>
		<link rel="stylesheet" type="text/css" href="../../public/front/css/admin.css">
	</head>
	<body>
		<h3>Projects</h3>

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
		<h4>Add new project</h4>
			<?php
				$gui->selectOptions('type','project_types','given_name','create_project_types.php','Novo tipo +');
				$gui->inputText('project');
				$gui->inputText('short_description');
				$gui->inputText('long_description');
				$gui->inputText('year_start');
				$gui->inputText('year_end');
				$gui->inputText('priority');
				$gui->inputText('active');
				$gui->selectMultiple('category','categories','category','create_categories.php','Nova categoria +');
				$gui->selectMultiple('company','companies','company','create_companies.php','Nova empresa +');
				$gui->selectMultiple('technique','techniques','technique','create_techniques.php','Nova técnica +');
				$gui->selectMultiple('technology','technologies','technology','create_technologies.php','Nova tecnologia +');
				$gui->selectMultiple('place','places','city','create_places.php','Novo lugar +');
				$gui->selectMultiple('media','media','media_title','create_media.php','Nova mídia +');
				$gui->selectMultiple('work','works','work_title','create_works.php','Novo trabalho +');
				$gui->selectMultiple('function','functions','function','create_functions.php','Nova função +');
				$gui->selectMultiple('author','people','name','create_people.php','Nova pessoa +');
				$gui->selectMultiple('collaborator','people','name','create_people.php','Nova pessoa +');
				$gui->selectMultiple('exhibition','exhibitions','exhibition','create_exhibitions.php','Nova exibição +');
				$gui->selectMultiple('prize','prizes','name','create_prizes.php','Novo prêmio +');
				$gui->selectMultiple('collection','collections','collection','create_collections.php','Nova coleção +');
			?>
			<input type="submit" value="Inserir">
		</form>
	</body>
</html>





