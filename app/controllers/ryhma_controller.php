<?php

class RyhmaController extends BaseController {
    
    public static function show_group($id) {
        self::check_logged_in();
        $ryhma = Ryhma::hae($id);
        View::make('groups/show.html', array('ryhma' => $ryhma));
    }
    
    public static function group_members($id) {
        self::check_logged_in();
        $ryhma = Ryhma::hae($id);
        if (in_array(self::get_user_logged_in(), $ryhma->haeJasenet())) {
            View::make('groups/members.html', array('ryhma' => $ryhma));
        } else {
            Redirect::to('/group/' . $id, array('message' => 'Vain jäsenet voivat katsoa muita ryhmän jäseniä.'));
        }
    }
    
    public static function remove_member($id) {
        self::check_logged_in();
        $ryhma = Ryhma::hae($id);
        $kirjautunut_kayttaja = self::get_user_logged_in();
        $poistettava_kayttaja = Kayttaja::hae($_POST['user']);
        
        if ($kirjautunut_kayttaja->id != $poistettava_kayttaja->id && $kirjautunut_kayttaja->id != $ryhma->yllapitaja->id && !$kirjautunut_kayttaja->yllapitaja) {
            Redirect::to('/group/' . $id . '/members', array('error_messages' => array('Sinulla ei ole oikeuksia poistaa kyseistä käyttäjää ryhmästä')));
        }
        
        try {
            $ryhma->poistaJasen($poistettava_kayttaja);
            Redirect::to('/group/' . $id . '/members', array('message' => 'Ryhmän jäsen poistettu'));
        } catch (Exception $ex) {
            Redirect::to('/group/' . $id . '/members', array('error_messages' => array('Virhe: ' . $ex->getMessage())));
        }
    }
    
    public static function edit_group($id) {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $ryhma = Ryhma::hae($id);
        
        if ($kayttaja->id != $ryhma->yllapitaja->id && !$kayttaja->yllapitaja) {
            Redirect::to('/group/' . $id, array('error_messages' => array('Vain ryhmän ylläpitäjä voi muokata ryhmää')));
        } else {
            View::make('groups/edit.html', array('ryhma' => $ryhma));
        }
    }
}
