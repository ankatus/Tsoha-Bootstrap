<?php
class AdminController extends BaseController {

	public static function adminFrontpage() {
		if (isset($_GET['deleteAccounts'])) {
			$accounts = Account::getAllAccounts();
			View::make('admin/adminFrontpage.html', array('deleteAccounts' => true, 'accounts' => $accounts));
		} else {
			$games = Game::getAllGames();
			View::make('admin/adminFrontpage.html', array('deleteAccounts' => false, 'games' => $games));
		}
	}

	public static function adminDeleteAccount() {
		$accountId = $_POST['list'];
		Account::deleteAccount($accountId);
		Redirect::to('/adminFrontpage', array('messages' => array('account deleted')));
	}

	public static function adminDeleteGame() {
		$gameId = $_POST['list'];
		Game::deleteGame($gameId);
		Redirect::to('/adminFrontpage', array('messages' => array('game deleted')));
	}
}