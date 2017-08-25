<?php
class SteamController extends BaseController {

	public static function getGamesForCurrentUser() {
		$key = trim(file_get_contents('apikey.txt'));
		$steamid = parent::get_user_logged_in()->steamid;
		$link = @file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=' . $key . '&steamid=' . $steamid . '&format=json&include_appinfo=1');
		if ($link == false) {
			$errors = array('steam id is bad :(');
			Redirect::to('/addGame', array('errors' => $errors));
			return null;
		}
		$result = json_decode($link, true);
		$result = $result['response']['games'];
		$games = array();
		foreach ($result as $game) {
			$games[] = new Game(array(
				'name' => $game['name'],
				'url' => 'store.steampowered.com/app/' . $game['appid'],
				'imgUrl' => 'http://media.steampowered.com/steamcommunity/public/images/apps/' . $game['appid'] . '/'. $game['img_logo_url'] . '.jpg'
				));
		}

		foreach ($games as $game) {
			if (count($game->errors()) == 0) {
				$query = DB::connection()->prepare('INSERT INTO Game (game_name, game_url, game_img_url) VALUES (:name, :url, :imgUrl) RETURNING game_id');
				$query->execute(array('name' => $game->name, 'url' => $game->url, 'imgUrl' => $game->imgUrl));
				$id = $query->fetch()['game_id'];
			} else {
				$query = DB::connection()->prepare('SELECT game_id FROM Game WHERE game_name = :name');
				$query->execute(array('name' => $game->name));
				$result = $query->fetch();
				$id = $result['game_id'];
			}
			if (!Account_game::checkIfAccountOwnsGame(parent::get_user_logged_in()->id, $id)) {
				$query = DB::connection()->prepare('INSERT INTO Account_game (account_id, game_id) VALUES (:accountId, :gameId)');
				$query->execute(array('accountId' => parent::get_user_logged_in()->id, 'gameId' => $id));
			}
		}
		Redirect::to('/gamesList');
	}
}