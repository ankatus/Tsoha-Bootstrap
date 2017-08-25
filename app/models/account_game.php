<?php

class Account_game extends BaseModel {
	public $account_id, $game_id;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}

	public static function checkIfAccountOwnsGame($accountId, $gameId) {
		$query = DB::connection()->prepare('SELECT * FROM Account_game WHERE account_id = :accountId AND game_id = :gameId');
		$query->execute(array('accountId' => $accountId, 'gameId' => $gameId));
		$result = $query->fetchAll();
		if (count($result) == 0) {
			return false;
		}
		return true;
	}

	public static function remove($accountId, $gameId) {
		$query = DB::connection()->prepare('DELETE FROM Account_game WHERE account_id = :accountId AND game_id = :gameId');
		$query->execute(array('accountId' => $accountId, 'gameId' => $gameId));
	}

	public function save() {
		$query = DB::connection()->prepare('INSERT INTO Account_game (account_id, game_id) VALUES (:account_id, :game_id)');
		$query->execute(array('account_id' => $this->account_id, 'game_id' => $this->game_id));
	}
}