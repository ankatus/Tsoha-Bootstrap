<?php
class Account_game extends BaseModel {
	public $account_id, $game_id;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}

	public function save() {
		$query = DB::connection()->prepare('INSERT INTO Account_game (account_id, game_id) VALUES (:account_id, :game_id)');
		$query->execute(array('account_id' => $this->account_id, 'game_id' => $this->game_id));
	}
}