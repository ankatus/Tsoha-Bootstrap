<?php
class Account extends BaseModel {

	public $name, $password, $id, $validators;

	public function __construct($attributes) {
		parent::__construct($attributes);
		$this->validators = array('validateName', 'validatePassword');
	}

	public static function getAccount($accountId) {

		$query = DB::connection()->prepare('SELECT * FROM Account WHERE account_id = :accountId');
		$query->execute(array('accountId' => $accountId));
		$row = $query->fetch();

		$account = new Account(array(
			'name' => $row['account_name'], 
			'password' => $row['account_password'], 
			'id' => $row['account_id']
			));
		return $account;
	}

	public static function getAllAccounts() {
		
		$query = DB::connection()->prepare('SELECT * FROM Account');
		$query->execute();
		$rows = $query->fetchAll();
		$accounts = array();

		foreach ($rows as $row) {
			$accounts[] = new Account(array(
				'name' => $row['account_name'], 
				'password' => $row['account_password'], 
				'id' => $row['account_id']
				));
		}

		return $accounts;
	}

	public static function authenticate($username, $password) {
		$query = DB::connection()->prepare('SELECT * FROM Account WHERE account_name = :username AND account_password = :password');
		$query->execute(array('username' => $username, 'password' => $password));
		$row = $query->fetch();
		if($row) {
			$account = new Account(array(
				'name' => $row['account_name'],
				'password' => $row['account_password'],
				'id' => $row['account_id']));
			return $account;
		} else {
			return null;
		}
	}

	public static function changeName($newName, $id) {
		$query = DB::connection()->prepare('UPDATE Account SET account_name = :name WHERE account_id = :id');
		$query->execute(array('name' => $newName, 'id' => $id));
	}

	public static function changePassword($newPassword, $id) {
		$query = DB::connection()->prepare('UPDATE Account SET account_password = :password WHERE account_id = :id');
		$query->execute(array('password' => $newPassword, 'id' => $id));
	}

	public static function deleteUser($id) {
		$query = DB::connection()->prepare('DELETE FROM Account WHERE Account_id = :id');
		$query->execute(array('id' => $id));
	}

	public function save() {
		$query = DB::connection()->prepare('INSERT INTO Account (account_name, account_password) VALUES (:name, :password)');
		$query->execute(array('name' => $this->name, 'password' => $this->password));
	}

	public function validateName() {
		$errors = array();

		if ($this->name == '' || $this->name == null) {
			$errors[] = 'name cannot be empty';
		}
		$query = DB::connection()->prepare('SELECT * FROM Account WHERE account_name = :name');
		$query->execute(array('name' => $this->name));
		$result = $query->fetchAll();
		if (count($result) > 0) {
			$errors[] = 'name already in use';
		}
		return $errors;
	}

	public function validatePassword() {
		$errors = array();

		if ($this->password == '' || $this->password == null) {
			$errors[] = 'password cannot be empty';
		}
		return $errors;
	}


}