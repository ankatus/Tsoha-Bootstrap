<?php
class Steam extends BaseModel {

	//returns an array of game-objects representing games owned by the specified steam-user
	public static function getOwnedGames($userId, $apiKey) {

		$link = @file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=' . $apiKey . '&steamid=' . $userId . '&format=json&include_appinfo=1');
		if ($link == false) {
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
		return $games;
	}

}