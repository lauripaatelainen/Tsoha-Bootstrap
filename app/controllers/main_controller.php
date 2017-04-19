<?php
class MainController extends BaseController {

    public static function index() {
        self::check_logged_in();
        $kayttaja = self::get_user_logged_in();
        $julkaisut = Julkaisu::kaikki_kayttajalle_nakyvat($kayttaja);
        
        View::make('main/index.html', array('julkaisut' => $julkaisut));
    }
}
