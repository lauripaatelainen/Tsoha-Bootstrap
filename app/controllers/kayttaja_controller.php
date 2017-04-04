<?php

class KayttajaController extends BaseController {

    public static function list_users() {
        self::check_admin();
        
        $kayttajat = Kayttaja::kaikki();
        View::make('users/list.html', array('users' => $kayttajat));
    }

    public static function show_user($id) {
        self::check_logged_in();
        
        $kayttaja = Kayttaja::hae($id);
        View::make('users/show.html', array('user' => $kayttaja));
    }

    public static function edit_user($id) {
        self::check_admin($id);
        
        $kayttaja = Kayttaja::hae($id);
        View::make('users/edit.html', array('user' => $kayttaja));
    }
    
    public static function handle_edit_user($id) {
        self::check_admin($id);
        
        try {
            $kayttajatunnus = trim($_POST['kayttajatunnus']);
            $salasana1 = $_POST['salasana1'];
            $salasana2 = $_POST['salasana2'];
            $yllapitaja = isset($_POST['yllapitaja']);
            
            $kayttaja = Kayttaja::hae($id);
            $kayttaja->kayttajatunnus = $kayttajatunnus;
            if ($salasana1 != '') { /* salasana päivitetään vain jos se on syötetty lomakkeeseen */
                $kayttaja->salasana1 = $salasana1;
                $kayttaja->salasana2 = $salasana2;
            }
            $kayttaja->yllapitaja = $yllapitaja;
            $kayttaja->tallenna();
            
            Redirect::to('/user/' . $id . '/edit', array('message' => 'Käyttäjä tallennettu'));
        } catch (ValidationException $ex) {
            Redirect::to('/user/' . $id . '/edit', array('error_messages' => $ex->getErrors()));
        } catch (Exception $ex) {
            Redirect::to('/user/' . $id . '/edit', array('error_messages' => array($ex->getMessage())));
        }
    }

    public static function delete_user($id) {
        self::check_admin($id);
        
        $kayttaja = Kayttaja::hae($id);
        View::make('users/delete.html', array('user' => $kayttaja));
    }
    
    public static function handle_delete_user($id) {
        self::check_admin($id);
        
        $kayttaja = Kayttaja::hae($id);
        $kayttaja->poista();
        Redirect::to('/user', array('message' => "Käyttäjä '" . $kayttaja->kayttajatunnus . "' poistettu"));
    }

    public static function register() {
        View::make('users/register.html');
    }

    public static function handle_register() {
        $kayttajatunnus = $_POST['kayttajatunnus'];
        $salasana1 = $_POST['salasana1'];
        $salasana2 = $_POST['salasana2'];
        
        try {
            $kayttaja = new Kayttaja(array('kayttajatunnus' => $kayttajatunnus, 'salasana1' => $salasana1, 'salasana2' => $salasana2));
            $kayttaja->tallenna();
            Redirect::to('/user/' . $kayttaja->id);
        } catch (ValidationException $ex) {
            Redirect::to('/register', array('error_messages' => $ex->getErrors(), 'kayttajatunnus' => $kayttajatunnus));
        } catch (Exception $ex) {
            Redirect::to('/register', array('error_messages' => array($ex->getMessage()), 'kayttajatunnus' => $kayttajatunnus));
        }
    }
    
    public static function login() {
        View::make('users/login.html');
    }
    
    public static function handle_login() {
        $kayttajatunnus = $_POST['kayttajatunnus'];
        $salasana = $_POST['salasana'];
        
        $kayttaja = Kayttaja::autentikoi($kayttajatunnus, $salasana);
        if ($kayttaja != null) {
            $_SESSION['user'] = $kayttaja->id;
            Redirect::to('/');
        } else {
            Redirect::to('/login', array('error_messages' => array('Käyttäjätunnus tai salasana väärin'), 'kayttajatunnus' => $kayttajatunnus));
        }
    }
    
    public static function handle_logout() {
        unset($_SESSION['user']);
        Redirect::to('/login', array('message' => 'Sinut on kirjattu ulos'));
    }
}
