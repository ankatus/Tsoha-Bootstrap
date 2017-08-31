<?php

  class BaseController{

    public static function get_user_logged_in(){
      // Toteuta kirjautuneen käyttäjän haku tähän
      if (isset($_SESSION['user'])) {
        $userId = $_SESSION['user'];
        $user = Account::getAccount($userId);
        return $user;
      }
      return null;
    }

    public static function check_logged_in(){
      // Toteuta kirjautumisen tarkistus tähän.
      // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
      if(!isset($_SESSION['user'])) {
        Redirect::to('/login', array('errors' => array('log in please')));
      }
    }

    public static function check_admin() {
      if (!isset($_SESSION['admin'])) {
        Redirect::to('/login', array('errors' => array('not logged in as admin')));
      }
    }

  }
