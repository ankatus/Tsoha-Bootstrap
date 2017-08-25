<?php

class Game extends BaseModel {

	public $name, $id, $url, $imgUrl, $desc, $validators;

	public function __construct($attributes) {
		parent::__construct($attributes);
		$this->validators = array('validateName', 'checkForExistence');
	}

	public static function getGame($id) {
		$query = DB::connection()->prepare('SELECT * FROM Game WHERE game_id = :id');
		$query->execute(array('id' => $id));
		$row = $query->fetch();

		$game = new Game(array(
			'name' => $row['game_name'],
			'id' => $row['game_id'],
			'url' => $row['game_url'],
			'desc' => $row['game_desc'],
			'imgUrl' => $row['game_img_url']
			));
		return $game;
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
				'desc' => $row['game_desc'],
				'imgUrl' => $row['game_img_url']
				));
		}

		return $games;
	}

	//returns all games owned by and account
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
				'desc' => $row['game_desc'],
				'imgUrl' => $row['game_img_url']
				));
		}
		return $games;
	}

	public static function removeFromAccount($gameId, $accountId) {
		$query = DB::connection()->prepare('DELETE FROM Account_game WHERE game_id = :gameId AND account_id = :accountId');
		$query->execute(array('gameId' => $gameId, 'accountId' => $accountId));
	}

	public static function checkIfGameExists($gameName) {
		$query = DB::connection()->prepare('SELECT * FROM Game WHERE game_name = :gameName');
		$query->execute(array('gameName' => $gameName));
		$result = $query->fetchAll();
		if (count($result) == 0) {
			return false;
		}
		return true;
	}
	
	public function save() {
		$query = DB::connection()->prepare('INSERT INTO Game (game_name, game_url, game_desc, game_img_url) VALUES (:name, :url, :desc, :imgUrl) RETURNING game_id');
		$query->execute(array('name' => $this->name, 'url' => $this->url, 'desc' => $this->desc, 'imgUrl' => $this->imgUrl));
		$row = $query->fetch();
		return $row['game_id'];
	}

	//checks that this object's name is not empty or null
	public function validateName() {
		$errors = array();
		if ($this->name == '' || $this->name == null) {
			$errors[] = 'Name cannot be empty';
		}
		return $errors;
	}

	public function checkForExistence() {
		$query = DB::connection()->prepare('SELECT * FROM Game WHERE game_name = :name');
		$query->execute(array('name' => $this->name));
		$result = $query->fetchAll();
		$errors = array();
		if (count($result) != 0) {
			$errors[] = 'a game with that name already exists';
		}
		return $errors;
	}
}