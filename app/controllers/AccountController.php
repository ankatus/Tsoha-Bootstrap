<?php
class AccountController extends BaseController {

	public static function accountFrontpage() {
		View::make('frontpage.html');
	}

	public static function newAccountForm() {
		View::make('account/addNewAccount.html');
	}

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

	public static function listAccounts() {
		$accounts = Account::getAllAccounts();
		View::make('account/accountsList.html', array('accounts' => $accounts));
	}

	public static function showAccount($id) {

		$account = Account::getAccount($id);

		View::make('account/viewAccount.html', array('account' => $account));
	}

	public static function loginForm() {
		View::make('account/login.html');
	}

	public static function handleLogin() {
		$params = $_POST;

		$user = Account::authenticate($params['username'], $params['password']);

		if(!$user) {
			View::make('account/login.html');
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
		View::make('account/editAccount.html');
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
		View::make('account/deleteAccountForm.html');
	}

	public static function deleteCurrentUser() {
		Account::deleteUser(parent::get_user_logged_in()->id);
		Redirect::to('/logout');
	}

	public static function accountInfo() {
		View::make('account/accountInfo.html');
	}
}