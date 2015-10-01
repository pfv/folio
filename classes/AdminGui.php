<?php

class AdminGui{

	protected $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	// --- INSIDE TABLE

	public function showElement($element, $field){
		$returnItem = '';
		if($field == 'id'){
			$returnItem = '<td class="id_field">'.$element->$field.'</td>';
		}
		else{
			$returnItem = '<td>'.$element->$field.'</td>';
		}
		echo $returnItem;
	}

	public function showMultipleElements($array, $element, $field){
		$occupied = false;
		$returnItem = '<td>';

		for($j = 0; $j < count($array);$j++){
			if($array[$j]->id==$element->id){
				if($occupied == true){
					$returnItem = $returnItem.', ';
				}
				$occupied = true;
				$returnItem = $returnItem.$array[$j]->$field;
			}
			else{

			}
		}
		if(!$occupied){
			$returnItem = $returnItem.'-';
		}
		$returnItem = $returnItem.'</td>';
		echo $returnItem;
	}


	public function deleteButton($element){
		$returnItem = '<td>
					   <form action="" method="post">
					   <input type="text" style="display:none" name="id" value="'.$element->id.'" >
					   <input type="submit" value="X">
					   </form>
					   </td>';
		echo $returnItem;
	}

	// --- EDITING FIELDS

	public function selectMultiple($label,$table,$field,$newUrl,$newMessage){

		$returnItem = '<div class="field">
					   <label for="'.$label.'">'.$label.'</label>
					   <select name="'.$label.'[]" multiple="multiple">';

		if($results = $this->db->table($table)->select()){
			foreach($results as $row){
				$returnItem = $returnItem . '<option name="'.$row->$field.'" value="'.$row->id.'">'.$row->$field.'</option>';
			}
		}

		$returnItem = $returnItem . '</select><a href="'.$newUrl.'" class="new">'.$newMessage.'</a></div>';
		echo $returnItem;
	}

	public function selectOptions($label,$table,$field,$newUrl,$newMessage){

		$returnItem = '<div class="field">
					   <label for="'.$label.'">'.$label.'</label>
					   <select name="'.$label.'">
					   <option label=" "></option>';

		if($results = $this->db->table($table)->select()){
			foreach($results as $row){
				$returnItem = $returnItem . '<option name="'.$row->$field.'" value="'.$row->id.'">'.$row->$field.'</option>';
			}
		}

		$returnItem = $returnItem . '</select><a href="'.$newUrl.'" class="new">'.$newMessage.'</a></div>';
		echo $returnItem;

	}

	public function inputText($label){

		$returnItem = '<div class="field">
					   <label for="'.$label.'">'.$label.'</label>
					   <input type="text" name="'.$label.'" id="'.$label.'">
					   </div>';

		echo $returnItem;
	}

	public function textArea($label,$rows,$cols){

		$returnItem = '<div class="field">
					   <label for="'.$label.'">'.$label.'</label>
					   <textarea name="'.$label.'" id="'.$label.' rows="'.$rows.' cols="'.$cols.'" "></textarea>
					   </div>';

		echo $returnItem;
	}

	// FILL ARRAYS

	public function rightJoin($field, $table, $table_field){
		$returnArray = array();
		if($results = $this->db->rightJoin($field,$table,$table_field)){
			foreach($results as $row){
				$returnArray[] = $row;
			}
		}
		else{
			$returnArray = null;
		}
		return $returnArray;
	}

	public function select($table){
		$returnArray = array();
		if($results = $this->db->table($table)->select()){
			foreach($results as $row){
				$returnArray[] = $row;
			}
		}
		else{
			$returnArray = null;
		}
		return $returnArray;
	}

	public function joinThree($table,$join_table,$second_table,$second_table_field,$join_first_field,$join_second_field){
		$returnArray;
		if($results = $this->db->joinThree($table,$join_table,$second_table,$second_table_field,$join_first_field,$join_second_field)){
			foreach($results as $row){
				$returnArray[] = $row;
			}
		}
		else{
			$returnArray = null;
		}
		return $returnArray;
	}





}



?>