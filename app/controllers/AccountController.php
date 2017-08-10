<?php
class AccountController extends BaseController {

	public static function newAccountForm() {
		View::make('account/addNewAccount.html');
	}

	public static function storeNewAccount() {

		$params = $_POST;

		$newAccount = new Account(array(
			'name' => $params['name'],
			'password' => $params['password']
			));

		$newAccount->save();

		Redirect::to('/hiekkalaatikko');
	}

	public static function listAccounts() {
		$accounts = Account::getAllAccounts();
		View::make('account/accountsList.html', array('accounts' => $accounts));
	}

	public static function showAccount($id) {

		$account = Account::getAccount($id);

		View::make('account/viewAccount.html', array('account' => $account));
	}
}