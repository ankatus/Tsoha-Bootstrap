<?php
class Game extends BaseModel {

	public $name, $id, $url, $desc;

	public function __construct($attributes) {
		parent::__construct($attributes);
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

	
}