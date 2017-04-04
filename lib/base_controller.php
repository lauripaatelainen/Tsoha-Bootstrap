<?php

class BaseController {

    public static function get_user_logged_in() {
        if (isset($_SESSION['user'])) {
            $kayttaja = Kayttaja::hae($_SESSION['user']);
            return $kayttaja;
        }
        
        return null;
    }

    public static function check_logged_in() {
        if (!self::get_user_logged_in()) {
            Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään'));
        }
    }
    
    /* varmistaa että käyttäjä on admin, tai että valinnainen $id attribuutti on
     * sisäänkirjautuneen käyttäjän id, jolloin pääsy myös sallitaan. 
     */
    public static function check_admin($id = null) {
        $kayttaja = self::get_user_logged_in();
        if (!$kayttaja) {
            Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään'));
        } else if (!$kayttaja->yllapitaja && $kayttaja->id != $id) {
            Redirect::to('/', array('error_messages' => array('Tämä sivu vaatii ylläpitäjän oikeudet, joita sinulla ei ole')));
        }
        
    }
}
