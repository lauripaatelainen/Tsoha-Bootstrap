<?php

class KayttajaController extends BaseController {

    public static function list_users() {
        $kayttajat = Kayttaja::kaikki();
        View::make('users/list.html', array('users' => $kayttajat));
    }

    public static function show_user($id) {
        $kayttaja = Kayttaja::hae($id);
        View::make('users/show.html', array('user' => $kayttaja));
    }

    public static function edit_user($id) {
        $kayttaja = Kayttaja::hae($id);
        View::make('users/edit.html', array('user' => $kayttaja));
    }
    
    public static function handle_edit_user($id) {
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
        $kayttaja = Kayttaja::hae($id);
        View::make('users/delete.html', array('user' => $kayttaja));
    }
    
    public static function handle_delete_user($id) {
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
}
