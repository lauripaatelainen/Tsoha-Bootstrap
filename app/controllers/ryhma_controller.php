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

    public static function create_group() {
        self::check_logged_in();
        View::make('groups/create.html');
    }

    public static function handle_create_group() {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $ryhma = new Ryhma(array('nimi' => $_POST['nimi'],
            'kuvaus' => $_POST['kuvaus'],
            'suljettu' => isset($_POST['suljettu']),
            'yllapitaja' => $kayttaja));

        try {
            $ryhma->tallenna();
            Redirect::to('/group/' . $ryhma->id, array('message' => 'Ryhmä luotu'));
        } catch (ValidationException $ex) {
            Redirect::to('/create_group', array('error_messages' => $ex->getErrors(), 'nimi' => $ryhma->nimi, 'kuvaus' => $ryhma->kuvaus, 'suljettu' => $ryhma->suljettu));
        } catch (Exception $ex) {
            Redirect::to('/create_group', array('error_messages' => array($ex->getMessage()), 'nimi' => $ryhma->nimi, 'kuvaus' => $ryhma->kuvaus, 'suljettu' => $ryhma->suljettu));
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

    public static function handle_edit_group($id) {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $ryhma = Ryhma::hae($id);

        if ($kayttaja->id != $ryhma->yllapitaja->id && !$kayttaja->yllapitaja) {
            Redirect::to('/group/' . $id, array('error_messages' => array('Vain ryhmän ylläpitäjä voi muokata ryhmää')));
        } else {
            $ryhma->nimi = $_POST['nimi'];
            $ryhma->kuvaus = $_POST['kuvaus'];
            $ryhma->suljettu = isset($_POST['suljettu']);

            try {
                $ryhma->tallenna();
                Redirect::to('/group/' . $id . '/edit', array('message' => 'Muutokset tallennettu'));
            } catch (ValidationException $ex) {
                Redirect::to('/group/' . $id . '/edit', array('error_messages' => $ex->getErrors()));
            } catch (Exception $ex) {
                Redirect::to('/group/' . $id . '/edit', array('error_messages' => array($ex->getMessage())));
            }
        }
    }

    public static function delete_group($id) {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $ryhma = Ryhma::hae($id);

        if ($kayttaja->id != $ryhma->yllapitaja->id && !$kayttaja->yllapitaja) {
            Redirect::to('/group/' . $id, array('error_messages' => array('Vain ryhmän ylläpitäjä voi poistaa ryhmän')));
        } else {
            View::make('groups/delete.html', array('ryhma' => $ryhma));
        }
    }
    
    public static function handle_delete_group($id) {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $ryhma = Ryhma::hae($id);

        if ($kayttaja->id != $ryhma->yllapitaja->id && !$kayttaja->yllapitaja) {
            Redirect::to('/group/' . $id, array('error_messages' => array('Vain ryhmän ylläpitäjä voi poistaa ryhmän')));
        } else {
            try {
                $ryhma->poista();
                Redirect::to('/', array('message' => 'Ryhmä poistettu'));
            } catch (Exception $ex) {
                Redirect::to('/group/' . $id . '/delete', array('error_messages' => array($ex->getMessage())));
            }
        }
    }

}
