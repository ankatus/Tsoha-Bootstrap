<?php

  function check_logged_in() {
    BaseController::check_logged_in();
  };

  $routes->get('/sandbox', function() {
    GameController::sandbox();
  });

  $routes->get('/friendsList', 'check_logged_in', function() {
    AccountController::friendsList();
  });

  $routes->get('/accountsList', 'check_logged_in', function() {
    AccountController::accountList();
  });

  $routes->get('/editAccount', 'check_logged_in', function() {
    AccountController::editAccountForm();
  });

  $routes->get('/', 'check_logged_in', function() {
    AccountController::accountFrontPage();
  });

  $routes->get('/list', 'check_logged_in', function() {
  	GameController::currentAccountGameList();
  });

  $routes->get('/createAccount', function() {
    AccountController::newAccountForm();
  });

  $routes->get('/accountsList', 'check_logged_in', function() {
    AccountController::listAccounts();
  });

  $routes->get('/login', function() {
    AccountController::loginForm();
  });

  $routes->get('/viewAccount/:id', 'check_logged_in', function($id) {
    AccountController::showAccount($id);
  });

  $routes->get('/logout', function() {
    AccountController::logout();
  });

  $routes->get('/addGame', 'check_logged_in', function() {
    GameController::addGameForm();
  });

  $routes->get('/deleteAccount', 'check_logged_in', function() {
    AccountController::deleteCurrentUserForm();
  });

  $routes->get('/accountInfo', 'check_logged_in', function() {
    AccountController::accountInfo();
  });

  $routes->get('/viewGame/:id', 'check_logged_in', function($id) {
    GameController::viewGame($id);
  });

  $routes->get('/addFriend', 'check_logged_in', function() {
    AccountController::addFriendForm();
  });

  $routes->post('/gameOwners/:id', 'check_logged_in', function($id) {
    GameController::gameOwners($id);
  });

  $routes->post('/deleteAccount', function() {
    AccountController::deleteCurrentUser();
  });

  $routes->post('/newAccount', function() {
    AccountController::storeNewAccount();
  });

  $routes->post('/login', function() {
    AccountController::handleLogin();
  });

  $routes->post('/addGame', function() {
    GameController::addGame();
  });

  $routes->post('/addGameUserOnly', function() {
    GameController::addGameUserOnly();
  });

  $routes->post('/changename', function() {
    AccountController::changeName();
  });

  $routes->post('/changepassword', function() {
    AccountController::changePassword();
  });

  $routes->post('/addFriend', function() {
    AccountController::addFriend();
  });

