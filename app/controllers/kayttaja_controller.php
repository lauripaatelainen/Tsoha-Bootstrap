<?php

class KayttajaController extends BaseController {

    public static function list_users() {
        $users = Kayttaja::all();
        View::make('users/list.html', array('users' => $users));
    }

    public static function show_user($id) {
        $user = Kayttaja::find($id);
        View::make('users/show.html', array('user' => $user));
    }

    public static function edit_user($id) {
        $user = Kayttaja::find($id);
        View::make('users/edit.html', array('user' => $user));
    }

    public static function delete_user($id) {
        $user = Kayttaja::find($id);
        View::make('users/delete.html', array('user' => $user));
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
