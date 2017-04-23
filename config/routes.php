<?php

$routes->get('/', function() {
    MainController::index();
});

$routes->get('/login', function() {
    KayttajaController::login();
});

$routes->post('/login', function() {
    KayttajaController::handle_login();
});

$routes->post('/logout', function() {
    KayttajaController::handle_logout();
});

$routes->get('/create_group', function() {
    RyhmaController::create_group();
});

$routes->post('/create_group', function() {
    RyhmaController::handle_create_group();
});

$routes->get('/group', function() {
    RyhmaController::list_groups();
});

$routes->get('/group/:id', function($id) {
    RyhmaController::show_group($id);
});

$routes->get('/group/:id/edit', function($id) {
    RyhmaController::edit_group($id);
});

$routes->post('/group/:id/edit', function($id) {
    RyhmaController::handle_edit_group($id);
});

$routes->get('/group/:id/delete', function($id) {
    RyhmaController::delete_group($id);
});

$routes->post('/group/:id/delete', function($id) {
    RyhmaController::handle_delete_group($id);
});

$routes->get('/group/:id/members', function($id) {
    RyhmaController::group_members($id);
});

$routes->post('/group/:id/remove_member', function($id) {
    RyhmaController::remove_member($id);
});

$routes->post('/group/:id/join', function($id) {
    RyhmaController::join_group($id);
});

$routes->post('/group/:id/request_join', function($id) {
    RyhmaController::request_join($id);
});

$routes->post('/group/:id/cancel_request_join', function($id) {
    RyhmaController::cancel_request_join($id);
});

$routes->post('/group/:id/accept_request', function($id) {
    RyhmaController::accept_request($id); 
});

$routes->post('/group/:id/decline_request', function($id) {
    RyhmaController::decline_request($id);
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

$routes->post('/post', function() {
    JulkaisuController::handle_post();
});

$routes->post('/post/:id/comments', function($id) {
    JulkaisuController::handle_post_comment($id);
});

$routes->post('/post/:id/like', function($id) {
    JulkaisuController::handle_like($id);
});

$routes->post('/post/:id/unlike', function($id) {
    JulkaisuController::handle_unlike($id);
});