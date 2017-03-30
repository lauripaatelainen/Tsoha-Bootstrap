<?php
class UserController extends BaseController {
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
}