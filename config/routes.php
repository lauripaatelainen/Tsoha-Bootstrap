<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/main', function() {
    HelloWorldController::main();
});

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/register', function() {
    HelloWorldController::register();
});

$routes->get('/create_group', function() {
    HelloWorldController::create_group();
});

$routes->get('/group', function() {
    HelloWorldController::list_groups();
});

$routes->get('/group/1', function() {
    HelloWorldController::show_group();
});

$routes->get('/group/1/edit', function() {
    HelloWorldController::edit_group();
});

$routes->get('/group/1/members', function() {
    HelloWorldController::group_members();
});

$routes->get('/user', function() {
    UserController::list_users();
});

$routes->get('/user/:id', function($id) {
    UserController::show_user($id);
});

$routes->get('/user/:id/edit', function($id) {
    UserController::edit_user($id);
});

$routes->get('/user/:id/delete', function($id) {
    UserController::delete_user($id);
});