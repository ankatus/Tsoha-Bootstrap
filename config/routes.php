<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/list', function() {
  	GameController::listAll();
  });

  $routes->get('/createAccount', function() {
    AccountController::newAccountForm();
  });

  $routes->get('/accountsList', function() {
    AccountController::listAccounts();
  });

  $routes->get('/viewAccount/:id', function($id) {
    AccountController::showAccount($id);
  });

  $routes->post('/newAccount', function() {
    AccountController::storeNewAccount();
  });
