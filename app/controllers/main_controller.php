<?php
class MainController extends BaseController {

    public static function index() {
        self::check_logged_in();
        
        $julkaisut = Julkaisu::kaikki_julkiset();
        
        View::make('main/index.html', array('julkaisut' => $julkaisut));
    }
}
