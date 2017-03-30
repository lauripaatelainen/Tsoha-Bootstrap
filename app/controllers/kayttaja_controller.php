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
            
            if ($kayttajatunnus == '') {
                throw new Excecption('Käyttäjätunnus on tyhjä');
            } else if ($salasana1 != '' && $salasana1 != $salasana2) {
                throw new Exception('Salasanat ei täsmää');
            } else {
                $toinen = Kayttaja::haeKayttajatunnuksella($kayttajatunnus);
                if ($toinen != null && $toinen->id != $id) {
                    throw new Exception("Käyttäjätunnus '" . $kayttajatunnus . "' on jo käytössä");
                }
            }
            
            $kayttaja = Kayttaja::hae($id);
            $kayttaja->kayttajatunnus = $kayttajatunnus;
            if ($salasana1 != '') {
                $kayttaja->salasana = $salasana1;
            }
            $kayttaja->yllapitaja = $yllapitaja;
            $kayttaja->tallenna();
            
            Redirect::to('/user/' . $id . '/edit', array('message' => 'Käyttäjä tallennettu'));
        } catch (Exception $ex) {
            Redirect::to('/user/' . $id . '/edit', array('error_message' => $ex->getMessage()));
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
            if ($salasana1 != $salasana2) {
                throw new Exception("Salasanat ei täsmää");
            } else if ($salasana1 == '') {
                throw new Exception("Salasana on tyhjä");
            } else if ($kayttajatunnus == '') {
                throw new Exception("Käyttäjätunnus on tyhjä");
            } else if (Kayttaja::haeKayttajatunnuksella($kayttajatunnus) != null) {
                throw new Exception("Käyttäjätunnus '" . $kayttajatunnus . "' on varattu.");
            }

            $kayttaja = new Kayttaja(array('kayttajatunnus' => $kayttajatunnus, 'salasana' => $salasana1));
            $kayttaja->tallenna();
            Redirect::to('/user/' . $kayttaja->id);
        } catch (Exception $e) {
            Redirect::to('/register', array('error_message' => $e->getMessage(), 'kayttajatunnus' => $kayttajatunnus));
        }
    }
}
