<?php
class Game extends BaseModel {

	public $name, $id, $url, $desc, $validators;

	public function __construct($attributes) {
		parent::__construct($attributes);
		$this->validators = array('validateName');
	}

	public static function getAllGames() {
		$query = DB::connection()->prepare('SELECT * FROM Game');
		$query->execute();
		$rows = $query->fetchAll();
		$games = array();

		foreach($rows as $row) {
			$games[] = new Game(array(
				'name' => $row['game_name'],
				'id' => $row['game_id'],
				'url' => $row['game_url'],
				'desc' => $row['game_desc']
				));
		}

		return $games;
	}

	public static function getAllGamesForAccount($accountId) {
		$query = DB::connection()->prepare('SELECT * FROM Game INNER JOIN Account_game ON (Game.game_id = Account_game.game_id) WHERE account_id = :id');
		$query->execute(array('id' => $accountId));
		$rows = $query->fetchAll();
		$games = array();

		foreach($rows as $row) {
			$games[] = new Game(array(
				'name' => $row['game_name'],
				'id' => $row['game_id'],
				'url' => $row['game_url'],
				'desc' => $row['game_desc']
				));
		}
		return $games;
	}
	
	public function save() {
		$query = DB::connection()->prepare('INSERT INTO Game (game_name, game_url, game_desc) VALUES (:name, :url, :desc) RETURNING game_id');
		$query->execute(array('name' => $this->name, 'url' => $this->url, 'desc' => $this->desc));
		$row = $query->fetch();
		return $row['game_id'];
	}

	public function validateName() {
		$errors = array();
		if ($this->name == '' || $this->name == null) {
			$errors[] = 'Name cannot be empty';
		}
		return $errors;
	}
}