<?php
class AccountController extends BaseController {

	//
	public static function friendsList() {
		$accounts = Account::getAllFriendsForAccount(
			parent::get_user_logged_in()->id);
		View::make('account/friendsList.html', array('accounts' => $accounts));
	}

	//handles all 3 types of friend-adding: the all-accounts list, the list on the add friend -page and the "add via id" -form on the add friend -page
	public static function addFriend() {
		//different pages send different parameters
		if (isset($_POST['list'])) {
			$ids = array();
			$ids[] = $_POST['list'];
		} else if (isset($_POST['id'])){
			$ids = array();
			$ids[] = $_POST['id'];
		} else {
			$ids = array_keys($_POST);
		}

		$failedAdds = array();
		$successfulAdds = 0;

		//check if friends already
		foreach ($ids as $id) {
			if (!Friend::checkIfFriends(parent::get_user_logged_in()->id, $id) && Account::getAccount($id) != null && paren::get_user_logged_in()->id != $id) {
			$friend = new Friend(array('account_1_id' => parent::get_user_logged_in()->id, 'account_2_id' => $id));
			$friend->save();
			$successfulAdds += 1;
			} else {
				$failedAdds[] = $id;
			}
		}
		$errors = array();
		foreach ($failedAdds as $id) {
			$errors[] = '(id: ' . $id . ') could not be added as a friend';
		}
		$messages = array($successfulAdds . ' new friends added');
		Redirect::to('/friendsList', array('messages' => $messages, 'errors' => $errors));
	}

	public static function addFriendForm(){
		$accounts = Account::getAllAccounts();
		View::make('account/addFriend.html', array('accounts' => $accounts));
	}

	//makes the all accounts -list and checks the friendship status between each account and the logged in user
	public static function accountList() {
		$accounts = Account::getAllAccounts();
		foreach ($accounts as $account) {
			if (Friend::checkIfFriends(parent::get_user_logged_in()->id, $account->id)) {
				$account->friend = True;
			} else {
				$account->friend = False;
			}
		}
		View::make('account/accountsList.html', array('accounts' => $accounts));
	}

	public static function accountFrontpage() {
		View::make('frontpage.html');
	}

	public static function newAccountForm() {
		View::make('account/addNewAccount.html');
	}

	//creates an account-object and saves it into the DB
	public static function storeNewAccount() {

		$params = $_POST;

		$newAccount = new Account(array(
			'name' => $params['name'],
			'password' => $params['password']
			));

		$errors = $newAccount->errors();
		if (count($errors) == 0) {
			$newAccount->save();
			$messages = array('account created');
			Redirect::to('/login', array('messages' => $messages));
		} else {
			Redirect::to('/createAccount', array('errors' => $errors));
		}
	}

	public static function showAccount($id) {
		$account = Account::getAccount($id);
		View::make('account/viewAccount.html', array('account' => $account));
	}

	public static function loginForm() {
		View::make('account/login.html');
	}

	//checks if login credentials are correct, and starts the session if so
	public static function handleLogin() {
		$params = $_POST;

		$user = Account::authenticate($params['username'], $params['password']);

		if(!$user) {
			$errors = array('incorrect username or password');
			View::make('account/login.html', array('errors' => $errors));
		} else {
			$_SESSION['user'] = $user->id;

			Redirect::to('/');
		}
	}

	public static function logout() {
		$_SESSION['user'] = null;
		Redirect::to('/login');
	}

	public static function editAccountForm() {

		View::make('account/editAccount.html', array('account' => parent::get_user_logged_in()));
	}

	public static function changeName() {
		Account::changeName($_POST['name'], parent::get_user_logged_in()->id);
		$messages = array('name changed');
		Redirect::to('/editAccount', array('messages' => $messages));
	}

	public static function changePassword() {
		Account::changePassword($_POST['password'], parent::get_user_logged_in()->id);
		$messages = array('password changed');
		Redirect::to('/editAccount', array('messages' => $messages));
	}

	public static function deleteCurrentUserForm() {
		View::make('account/deleteAccount.html');
	}

	public static function deleteCurrentUser() {
		Account::deleteUser(parent::get_user_logged_in()->id);
		Redirect::to('/logout');
	}

	public static function accountInfo() {
		View::make('account/accountInfo.html');
	}

	public static function editSteamId() {
		$newSteamId = $_POST['steamId'];
		Account::editSteamId(parent::get_user_logged_in()->id, $newSteamId);
		$messages = array('steamId changed');
		Redirect::to('/editAccount', array('messages' => $messages));
	}

	public static function removeFriend() {
		Friend::removeFriend(parent::get_user_logged_in()->id, $_POST['removeid']);
		Redirect::to('/friendsList');
	}
}