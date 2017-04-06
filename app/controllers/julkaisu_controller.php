<?php

class JulkaisuController extends BaseController {
    
    public static function handle_post() {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $teksti = $_POST['teksti'];
        
        if (isset($_POST['return_path'])) {
            $return_path = $_POST['return_path'];
        } else {
            $return_path = '/';
        }
        
        try {
            $julkaisu = new Julkaisu(array(
                'kayttaja' => $kayttaja,
                'teksti' => $teksti,
                'aika' => date('Y-m-d H:i:s')
            ));
            $julkaisu->tallenna();
            Redirect::to($return_path, array('message' => 'Julkaistu'));
        } catch (ValidationException $ex) {
            Redirect::to($return_path, array('error_messages' => $ex->getMessages()));
        } catch (Exception $ex) {
            Redirect::to($return_path, array('error_messages' => array($ex->getMessage())));
        }
    }
    
    public static function handle_post_comment($post_id) {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $teksti = $_POST['teksti'];
        $julkaisu = Julkaisu::hae($post_id);
        if (isset($_POST['return_path'])) {
            $return_path = $_POST['return_path'];
        } else {
            $return_path = '/';
        }
        
        try {
            $kommentti = new Kommentti(array(
                'kayttaja' => $kayttaja,
                'julkaisu' => $julkaisu,
                'teksti' => $teksti,
                'aika' => date('Y-m-d H:i:s')
            ));
            $kommentti->tallenna();
            Redirect::to($return_path, array('message' => 'Kommentti julkaistu'));
        } catch (ValidationException $ex) {
            Redirect::to($return_path, array('error_messages' => $ex->getMessages()));
        } catch (Exception $ex) {
            Redirect::to($return_path, array('error_messages' => array($ex->getMessage())));
        }
    }
}
