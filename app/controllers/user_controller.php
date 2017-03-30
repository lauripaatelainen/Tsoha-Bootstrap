<?php
class UserController extends BaseController {
    public static function list_users() {
        $users = Kayttaja::all();
        View::make('users/list.html', array('users' => $users));
    }
    
    
}