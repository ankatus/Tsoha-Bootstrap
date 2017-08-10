<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  echo "tämä on etusivu";
    }

    public static function login(){
      View::make("suunnitelmat/login.html");
    }

    public static function add(){
      View::make("suunnitelmat/addgame.html");
    }

    public static function list(){
      View::make("suunnitelmat/list.html");
    }

    public static function remove(){
      View::make("suunnitelmat/removeaccount.html");
    }

    public static function sandbox() {
      $games = Game::getAllGames();
      Kint::dump($games);
    }
  }
