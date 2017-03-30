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
    KayttajaController::list_users();
});

$routes->get('/user/:id', function($id) {
    KayttajaController::show_user($id);
});

$routes->get('/user/:id/edit', function($id) {
    KayttajaController::edit_user($id);
});

$routes->post('/user/:id/edit', function($id) {
    KayttajaController::handle_edit_user($id);
});

$routes->get('/user/:id/delete', function($id) {
    KayttajaController::delete_user($id);
});

$routes->post('/user/:id/delete', function($id) {
    KayttajaController::handle_delete_user($id);
});

$routes->get('/register', function() {
    KayttajaController::register();
});

$routes->post('/register', function() {
    KayttajaController::handle_register();
});