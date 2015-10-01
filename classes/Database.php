<?php

class Database{
	protected $host = 'localhost';
	protected $db = 'pv';
	protected $username = 'pfv';
	protected $password = 'alpha';

	protected $statement;
	protected $table;

	public $pdo;

	public function __construct(){
		$this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db}",$this->username,$this->password);
	}

	public function table($table){
		$this->table = $table;
		return $this;
	}

	public function query($sql){
		return $this->pdo->query($sql);
	}

	public function select(){
		$sql = "SELECT * FROM {$this->table}";
		$this->statement = $this->pdo->query($sql);
		$result = $this->statement->fetchAll(PDO::FETCH_OBJ);
		return $result;
	}

	public function insert($data){
		$keys = array_keys($data);
		$values = array_map('mysql_real_escape_string', array_values($data));
		$sql = "INSERT INTO {$this->table} (".implode(',', $keys).") VALUES ('".implode('\',\'', $values)."')";
		$insert = $this->pdo->prepare($sql);

		return $insert->execute();
	}

	public function delete($id){
		$sql = "DELETE FROM {$this->table} WHERE id={$id};";
		$insert = $this->pdo->prepare($sql);

		return $insert->execute();
	}

	public function deleteWhich($id,$field){
		$sql = "DELETE FROM {$this->table} WHERE {$field}={$id};";
		$insert = $this->pdo->prepare($sql);

		return $insert->execute();
	}

	public function rightJoin($this_field,$table,$field){
		$sql = "SELECT {$field} FROM {$table} RIGHT JOIN {$this->table} ON {$this->table}.{$this_field} = {$table}.id ORDER BY {$this->table}.id";
		// $sql = "SELECT {$field} FROM {$this->table} RIGHT JOIN {$table} ON {$this->table}.{$this_field} = {$table}.id";
		$this->statement = $this->pdo->query($sql);
		$result = $this->statement->fetchAll(PDO::FETCH_OBJ);

		return $result;
	}

	public function joinThree($first_table,$second_table,$third_table,$third_field,$first_id,$second_id){
		$sql = "SELECT {$third_table}.{$third_field}, {$first_table}.id
				FROM {$second_table}
    			INNER JOIN {$first_table}
       			ON {$first_table}.id = {$second_table}.{$first_id}
   				INNER JOIN $third_table 
       			ON {$third_table}.id = {$second_table}.{$second_id}";

       	$this->statement = $this->pdo->query($sql);
		$result = $this->statement->fetchAll(PDO::FETCH_OBJ);
		// echo '<pre>',print_r($result),'</pre>';
		// echo '<pre>',print_r($result[1]->id),'</pre>';
		return $result;
	}

	public function exists($data){
		$field = array_keys($data)[0];
		return $this->where($field,'=',$data[$field])->count() ? true : false;
	}

	public function where($field,$operator,$value){
		$sql = "SELECT * FROM {$this->table} WHERE {$field} {$operator} ?";
		$this->statement = $this->pdo->prepare($sql);
		$this->statement->execute([$value]);
		return $this;
	}

	public function count(){
		return $this->statement->rowCount();
	}

	public function lastID(){
		$last_id = $this->pdo->lastInsertId();
    	return $last_id;
	}
}

?>