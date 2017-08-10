<?php
class Account extends BaseModel {

	public $name, $password, $id;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}

	public static function getAccount($accountId) {

		$query = DB::connection()->prepare('SELECT * FROM Account WHERE account_id = :accountId LIMIT 1');
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

	public function save() {
		$query = DB::connection()->prepare('INSERT INTO Account (account_name, account_password) VALUES (:name, :password)');
		$query->execute(array('name' => $this->name, 'password' => $this->password));
	}
}