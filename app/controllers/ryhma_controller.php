<?php

class RyhmaController extends BaseController {
    public static function show_group($id) {
        self::check_logged_in();
        $ryhma = Ryhma::hae($id);
        $liittymispyynto = Liittymispyynto::hae($ryhma, self::get_user_logged_in());
        View::make('groups/show.html', array('ryhma' => $ryhma, 'liittymispyynto' => $liittymispyynto));
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
            if ($poistettava_kayttaja->id == $kirjautunut_kayttaja->id) {
                Redirect::to('/', array('message' => 'Poistuit ryhmästä ' . $ryhma->nimi));
            } else {
                Redirect::to('/group/' . $id . '/members', array('message' => 'Ryhmän jäsen poistettu'));
            }
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

    public static function list_groups() {
        self::check_logged_in();
        $ryhmat = Ryhma::kaikki();
        View::make('groups/list.html', array('kaikkiryhmat' => $ryhmat));
    }

    public static function join_group($id) {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $ryhma = Ryhma::hae($id);
        if ($ryhma->suljettu) {
            Redirect::to('/group/' . $id, array('message' => 'Ryhmä on suljettu, voit pyytää liittyä ryhmään'));
            return;
        }

        if (in_array($ryhma, $kayttaja->haeRyhmat())) {
            Redirect::to('/group/' . $id, array('message' => 'Kuulut jo tähän ryhmään'));
            return;
        }

        try {
            $ryhma->lisaaJasen($kayttaja);
            Redirect::to('/group/' . $id, array('message' => 'Ryhmään liitytty'));
        } catch (Exception $ex) {
            Redirect::to('/', array('error_messages' => array('Ryhmään liittyminen epäonnistui')));
        }
    }

    public static function request_join($id) {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $ryhma = Ryhma::hae($id);
        
        if (!$ryhma->suljettu) {
            self::join_group($id);
            
        } else if (in_array($ryhma, $kayttaja->haeRyhmat())) {
            Redirect::to('/group/' . $id, array('message' => 'Kuulut jo tähän ryhmään'));
            
        } else {
            try {
                $liittymispyynto = Liittymispyynto::hae($ryhma, $kayttaja);
                if ($liittymispyynto != null) {
                    $liittymispyynto->viesti = $_POST['message'];
                } else {
                    $liittymispyynto = new Liittymispyynto(array('ryhma' => $ryhma, 'kayttaja' => $kayttaja, 'viesti' => $_POST['message']));
                }
                $liittymispyynto->tallenna();
                Redirect::to('/group/' . $id, array('message' => 'Liittymispyyntö lähetetty'));
            } catch (Exception $ex) {
                Redirect::to('/group/' . $id, array('error_messages' => array('Ryhmään liittyminen epäonnistui')));
            }
        }
    }
    
    public static function cancel_request_join($id) {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $ryhma = Ryhma::hae($id);
        
        $liittymispyynto = Liittymispyynto::hae($ryhma, $kayttaja);
        if ($liittymispyynto) {
            $liittymispyynto->poista();
        }
        
        Redirect::to('/group/' . $id, array('message' => 'Liittymispyyntö peruttu'));
    }
    
    public static function accept_request($id) {
        $ryhma = Ryhma::hae($id);
        self::check_admin($ryhma->yllapitaja->id);
        
        $kayttaja = Kayttaja::hae($_POST['user']);
        $liittymispyynto = Liittymispyynto::hae($ryhma, $kayttaja);
        try {
            $ryhma->lisaaJasen($kayttaja);
            $liittymispyynto->poista();
            Redirect::to('/group/' . $id, array('message' => 'Liittymispyyntö hyväksytty'));
        } catch (Exception $ex) {
            Redirect::to('/group/' . $id, array('error_messages' => array('Liittymispyynnön hyväksyminen epäonnistui')));
        }
    }
    
    public static function decline_request($id) {
        $ryhma = Ryhma::hae($id);
        self::check_admin($ryhma->yllapitaja->id);
        
        $kayttaja = Kayttaja::hae($_POST['user']);
        $liittymispyynto = Liittymispyynto::hae($ryhma, $kayttaja);
        try {
            $liittymispyynto->poista();
            Redirect::to('/group/' . $id, array('message' => 'Liittymispyyntö hylätty'));
        } catch (Exception $ex) {
            Redirect::to('/group/' . $id, array('error_messages' => array('Liittymispyynnön hylkääminen epäonnistui')));
        }
    }
    
    public static function post($id) {
        self::check_logged_in();
        $ryhma = Ryhma::hae($id);
        $kayttaja = self::get_user_logged_in();
        $teksti = $_POST['teksti'];
        
        if (!in_array($kayttaja, $ryhma->haeJasenet())) {
            Redirect::to('/group/' . $id, array('error_messages' => array('Et voi julkaista tähän ryhmään, koska et kuulu siihen')));
            return;
        }
        
        try {
            $julkaisu = new Julkaisu(array('kayttaja' => $kayttaja, 'ryhma' => $ryhma, 'teksti' => $teksti, 'aika' => date('Y-m-d H:i:s')));
            $julkaisu->tallenna();
            Redirect::to('/group/' . $id, array('message' => 'Julkaistu'));
        } catch (ValidationException $ex) {
            Redirect::to('/group/' . $id, array('error_messages' => $ex->getErrors()));
        } catch (Exception $ex) {
            Redirect::to('/group/' . $id, array('error_messages' => array($ex->getMessage())));
        }
    }
    
    public static function invite_users($id) {
        $ryhma = Ryhma::hae($id);
        self::check_admin($ryhma->yllapitaja->id);
        
        $kayttajat = Kayttaja::kaikki();
        View::make('groups/invite.html', array('ryhma' => $ryhma, 'users' => $kayttajat));
    }
    
    public static function handle_invite_users($id) {
        $ryhma = Ryhma::hae($id);
        self::check_admin($ryhma->yllapitaja->id);
        
        try {
            foreach ($_POST['kayttajat'] as $kayttaja_id) {
                $kayttaja = Kayttaja::hae($kayttaja_id);
                $kutsu = Kutsu::hae($ryhma, $kayttaja);
                if ($kutsu) {
                    $kutsu->viesti = $_POST['viesti'];
                } else {
                    $kutsu = new Kutsu(array(
                        'ryhma' => $ryhma,
                        'kayttaja' => $kayttaja,
                        'viesti' => $_POST['viesti']
                    ));
                }
                $kutsu->tallenna();
            }
            Redirect::to('/group/' . $id, array('message' => 'Kutsut lähetetty'));
        } catch (Exception $ex) {
            Redirect::to('/group/' . $id, array('error_messages' => array('Kutsujen lähetys epäonnistui')));
        }
    }
    
    
    
    public static function accept_invitation($id) {
        self::check_logged_in();
        $ryhma = Ryhma::hae($id);
        $kayttaja = self::get_user_logged_in();
        
        $kutsu = Kutsu::hae($ryhma, $kayttaja);
        try {
            $ryhma->lisaaJasen($kayttaja);
            $kutsu->poista();
            Redirect::to('/', array('message' => 'Kutsu hyväksytty'));
        } catch (Exception $ex) {
            Redirect::to('/', array('error_messages' => array('Kutsun hyväksyminen epäonnistui')));
        }
    }
    
    public static function decline_invitation($id) {
        self::check_logged_in();
        $ryhma = Ryhma::hae($id);
        $kayttaja = self::get_user_logged_in();
        
        $kutsu = Kutsu::hae($ryhma, $kayttaja);
        try {
            $kutsu->poista();
            Redirect::to('/', array('message' => 'Kutsu hylätty'));
        } catch (Exception $ex) {
            Redirect::to('/', array('error_messages' => array('Kutsun hylkääminen epäonnistui')));
        }
    }
}
