<?php

  class View{

    public static function make($view, $content = array()){
      // Alustetaan Twig
      $twig = self::get_twig();

      try{
        // Asetetaan uudelleenohjauksen yhteydessä lisätty viesti
        self::set_flash_message($content);

        // Asetetaan näkymään base_path-muuttuja index.php:ssa määritellyllä BASE_PATH vakiolla
        $content['base_path'] = BASE_PATH;

        // Asetetaan näkymään kirjautunut käyttäjä, jos get_user_logged_in-metodi on toteutettu
        if(method_exists('BaseController', 'get_user_logged_in')){
          $content['user_logged_in'] = BaseController::get_user_logged_in();
        }

        // Tulostetaan Twig:n renderöimä näkymä
        echo $twig->render($view, $content);
      } catch (Exception $e){
        die('Virhe näkymän näyttämisessä: ' . $e->getMessage());
      }

      exit();
    }

    private static function get_twig(){
      Twig_Autoloader::register();

      $twig_loader = new Twig_Loader_Filesystem('app/views');

      $ids_filter = new Twig_SimpleFilter('ids', function($objs) {
          $ids = array();
          foreach ($objs as $obj) {
              $ids[] = $obj->id;
          }
          return $ids;
      });

      $kayttajat_filter = new Twig_SimpleFilter('kayttajat', function($objs) {
          $kayttajat = array();
          foreach ($objs as $obj) {
              $kayttajat[] = $obj->kayttaja;
          }
          return $kayttajat;
      });
      
      $twig = new Twig_Environment($twig_loader);
      $twig->addFilter($ids_filter);
      $twig->addFilter($kayttajat_filter);
      return $twig;
    }

    private static function set_flash_message(&$content){
      if(isset($_SESSION['flash_message'])){

        $flash = json_decode($_SESSION['flash_message']);

        foreach($flash as $key => $value){
          $content[$key] = $value;
        }

        unset($_SESSION['flash_message']);
      }
    }

  }
