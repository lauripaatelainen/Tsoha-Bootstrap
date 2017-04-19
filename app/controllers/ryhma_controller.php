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
        View::make('groups/members.html', array('ryhma' => $ryhma));
    }
}
