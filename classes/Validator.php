<?php

class Validator{
	protected $errorHandler;
	protected $db;

	protected $rules = ['required','maxlength','unique'];
	public $messages = [
		'required' => 'O campo :field é necessário',
		'maxlength' => 'O campo :field deve ter no máximo :satisfier caracteres',
		'unique' => 'Esse nome já está sendo usado'
	];

	public function __construct(Database $db, ErrorHandler $errorHandler){
		$this->errorHandler = $errorHandler;
		$this->db = $db;
	}

	public function check($items,$rules){
		foreach($items as $item => $value){
			if(in_array($item, array_keys($rules))){
				$this->validate([
					'field'=>$item,
					'value'=>$value,
					'rules'=>$rules[$item]
				]);
			}
		}
		return $this;
	}

	public function fails(){
		return $this->errorHandler->hasErrors();
	}

	public function errors(){
		return $this->errorHandler;
	}

	protected function validate($item){
		$field = $item['field'];

		foreach($item['rules'] as $rule => $satisfier){
			if(in_array($rule, $this->rules)){
				if(!call_user_func_array([$this, $rule],[$field, $item['value'],$satisfier])){
					$this->errorHandler->addError(str_replace([':field',':satisfier'],[$field,$satisfier],$this->messages[$rule]),$field);
				}
			}
		}
	}

	protected function required($field, $value, $satisfier){
		$temp = !empty(trim($value));
		if($satisfier === false){
			$temp = true;
		}
		
		return $temp;
	}

	protected function maxlength($field, $value, $satisfier){
		return mb_strlen($value) <= $satisfier;
	}

	protected function unique($field, $value, $satisfier){
		return !$this->db->table($satisfier)->exists([$field => $value]);
	}

}

?>