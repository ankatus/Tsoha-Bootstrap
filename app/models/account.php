<?php

class Account extends BaseModel {

	//$friend is a 'hack' to get accountList working as I want it to :^)
	public $name, $password, $id, $steamid, $validators, $friend;

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
			'id' => $row['account_id'],
			'steamid' => $row['account_steamid']
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
				'id' => $row['account_id'],
				'steamid' => $row['account_steamid']
				));
		}

		return $accounts;
	}

	//returns an array containing all the friends of the $id -id
	public static function getAllFriendsForAccount($id) {
		$query = DB::connection()->prepare('SELECT * FROM Account INNER JOIN Friend ON (Account.account_id = Friend.account_1_id OR Account.account_id = Friend.account_2_id) WHERE (Friend.account_1_id = :id OR Friend.account_2_id = :id) AND NOT account.account_id = :id');
		$query->execute(array('id' =>$id));
		$rows = $query->fetchAll();
		$accounts = array();
		foreach ($rows as $row) {
			$accounts[] = new Account(array(
				'name' => $row['account_name'], 
				'password' => $row['account_password'], 
				'id' => $row['account_id'],
				'steamid' => $row['account_steamid']
				));
		}
		return $accounts;
	}

	//checks if the $username and $password -arguments correspond to an account in the database, and if so, returns that account
	public static function authenticate($username, $password) {
		$query = DB::connection()->prepare('SELECT * FROM Account WHERE account_name = :username AND account_password = :password');
		$query->execute(array('username' => $username, 'password' => $password));
		$row = $query->fetch();
		if($row) {
			$account = new Account(array(
				'name' => $row['account_name'],
				'password' => $row['account_password'],
				'id' => $row['account_id'],
				'steamid' => $row['account_steamid']
				));
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
		$query = DB::connection()->prepare('DELETE FROM Account_game WHERE Account_id = :id');
		$query->execute(array('id' => $id));
		$query = DB::connection()->prepare('DELETE FROM Friend WHERE Account_1_id = :id OR Account_2_id = :id');
		$query->execute(array('id' => $id));
		$query = DB::connection()->prepare('DELETE FROM Account WHERE Account_id = :id');
		$query->execute(array('id' => $id));
	}

	//returns all accounts that own the game corresponding to $id
	public static function getAllOwnersOfGame($id) {
		$query = DB::connection()->prepare('SELECT * FROM Account INNER JOIN Account_game ON Account.account_id = Account_game.account_id WHERE Account_game.game_id = :id');
		$query->execute(array('id' => $id));
		$rows = $query->fetchAll();
		$accounts = array();
		foreach ($rows as $row) {
			$accounts[] = new Account(array(
				'name' => $row['account_name'],
				'password' => $row['account_password'],
				'id' => $row['account_id'],
				'steamid' => $row['account_steamid']
				));
		}
		return $accounts;
	}

	//same as getAllOwnersOfGame() but only returns friends of the current logged in user
	public static function getFriendsOwnersOfGame($gameId, $accountId) {
		$query = DB::connection()->prepare('SELECT * FROM Account INNER JOIN Account_game ON Account.account_id = Account_game.account_id INNER JOIN Friend ON (Account.account_id = Friend.Account_1_id OR Account.account_id = Friend.account_2_id)WHERE Account_game.game_id = :gameId AND (Friend.account_1_id = :accountId OR Friend.account_2_id = :accountId) AND NOT Account.account_id = :accountId');
		$query->execute(array('gameId' => $gameId, 'accountId' => $accountId));
		$rows = $query->fetchAll();
		$accounts = array();
		foreach ($rows as $row) {
			$accounts[] = new Account(array(
				'name' => $row['account_name'],
				'password' => $row['account_password'],
				'id' => $row['account_id'],
				'steamid' => $row['account_steamid']
				));
		}
		return $accounts;
	}

	public static function editSteamid($accountId, $steamid) {
		$query = DB::connection()->prepare('UPDATE Account SET account_steamid = :steamid WHERE account_id = :accountId');
		$query->execute(array('steamid' => $steamid, 'accountId' => $accountId));
	}

	public function save() {
		$query = DB::connection()->prepare('INSERT INTO Account (account_name, account_password, account_steamid) VALUES (:name, :password, null)');
		$query->execute(array('name' => $this->name, 'password' => $this->password));
	}

	//checks that this object's name is not empty, null or in use
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

	//checks that this accounts password is not empty or null
	public function validatePassword() {
		$errors = array();

		if ($this->password == '' || $this->password == null) {
			$errors[] = 'password cannot be empty';
		}
		return $errors;
	}


}