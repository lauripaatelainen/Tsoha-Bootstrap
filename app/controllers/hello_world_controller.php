<?php
class HelloWorldController extends BaseController {

    public static function index() {
        HelloWorldController::main();
    }

    public static function sandbox() {
        $kayttajat = Kayttaja::all();
        $kayttaja = Kayttaja::find(1);

        Kint::dump($kayttajat);
        Kint::dump($kayttaja);
    }

    public static function login() {
        View::make('suunnitelmat/login.html');
    }

    public static function register() {
        View::make('suunnitelmat/register.html');
    }

    public static function main() {
        View::make('suunnitelmat/main.html');
    }

    public static function create_group() {
        View::make('suunnitelmat/create_group.html');
    }

    public static function show_group() {
        View::make('suunnitelmat/show_group.html');
    }

    public static function edit_group() {
        View::make('suunnitelmat/edit_group.html');
    }

    public static function group_members() {
        View::make('suunnitelmat/group_members.html');
    }

    public static function edit_user() {
        View::make('suunnitelmat/edit_user.html');
    }

    public static function list_groups() {
        View::make('suunnitelmat/list_groups.html');
    }

    public static function list_users() {
        View::make('suunnitelmat/list_users.html');
    }


}
