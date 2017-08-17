<?php

  class HelloWorldController extends BaseController{

    public static function sandbox() {
      $account = AccountController::getAccount(1);
      Kint::dump($account);
    }
  }
