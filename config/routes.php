<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/login', function() {
  	HelloWorldController::login();
  });

  $routes->get('/add', function() {
  	HelloWorldController::add();
  });

  $routes->get('/list', function() {
  	HelloWorldController::list();
  });

  $routes->get('/remove', function() {
  	HelloWorldController::remove();
  });