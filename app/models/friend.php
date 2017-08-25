<?php

class Friend extends BaseModel {

	public $account_1_id, $account_2_id;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}

	//checks if two accounts are friends
	public static function checkIfFriends($id1, $id2) {
		$query = DB::connection()->prepare('SELECT * FROM Friend where 
			(account_1_id = :id1 AND account_2_id = :id2) OR 
			(account_1_id = :id2 AND account_2_id = :id1)');
		$query->execute(array('id1' => $id1, 'id2' => $id2));
		$rows = $query->fetchAll();
		if (count($rows) > 0) {
			return True;
		}
		if($id1 == $id2) {
			return False;
		} else {
			return False;
		}
	}

	public static function removeFriend($id1, $id2) {
		$query = DB::connection()->prepare('DELETE FROM Friend WHERE (account_1_id = :id1 AND account_2_id = :id2) OR (account_1_id = :id2 AND account_2_id = :id1)');
		$query->execute(array('id1' => $id1, 'id2' => $id2));
	}

	public function save() {
		$query = DB::connection()->prepare('INSERT INTO Friend (account_1_id, account_2_id) VALUES (:account_1_id, :account_2_id)');
		$query->execute(array('account_1_id' => $this->account_1_id, 'account_2_id' => $this->account_2_id));
	}
}