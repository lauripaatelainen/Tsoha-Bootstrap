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
    HelloWorldController::search_groups();
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

$routes->get('/user/1', function() {
    HelloWorldController::show_user();
});

$routes->get('/user/1/edit', function() {
    HelloWorldController::edit_user();
});

