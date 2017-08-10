<?php
class GameController extends BaseController {

	public static function listAll() {
		$games = Game::getAllGames();
		View::make('game/list.html', array('games' => $games));
	}
}