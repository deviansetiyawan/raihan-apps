<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DashboardController::index');
$routes->get('/dashboard', 'DashboardController::index');

$routes->get('/dashboard/upload', 'DocumentController::uploadForm', ['as' => 'documents.upload']);
$routes->post('/dashboard/upload', 'DocumentController::store', ['as' => 'documents.store']);
$routes->get('/dashboard/documents/check-code', 'DocumentController::checkCode', ['as' => 'documents.check_code']);
$routes->get('/dashboard/documents/(:num)/view', 'DocumentController::view/$1', ['as' => 'documents.view']);
$routes->get('/dashboard/documents/(:num)/preview', 'DocumentController::preview/$1', ['as' => 'documents.preview']);
$routes->get('/dashboard/documents/(:num)/download', 'DocumentController::download/$1', ['as' => 'documents.download']);
$routes->get('/dashboard/documents/(:num)/edit', 'DocumentController::edit/$1', ['as' => 'documents.edit']);
$routes->post('/dashboard/documents/(:num)/update', 'DocumentController::update/$1', ['as' => 'documents.update']);
$routes->post('/dashboard/documents/(:num)/delete', 'DocumentController::delete/$1', ['as' => 'documents.delete']);

$routes->get('/kamus-istilah', 'OperationalTermController::index', ['as' => 'terms.index']);
$routes->get('/kamus-istilah/tambah', 'OperationalTermController::create', ['as' => 'terms.create']);
$routes->post('/kamus-istilah', 'OperationalTermController::store', ['as' => 'terms.store']);
$routes->get('/kamus-istilah/(:num)/edit', 'OperationalTermController::edit/$1', ['as' => 'terms.edit']);
$routes->post('/kamus-istilah/(:num)/update', 'OperationalTermController::update/$1', ['as' => 'terms.update']);
$routes->post('/kamus-istilah/(:num)/delete', 'OperationalTermController::delete/$1', ['as' => 'terms.delete']);