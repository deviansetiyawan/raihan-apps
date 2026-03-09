<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DashboardController::index');
$routes->get('/dashboard', 'DashboardController::index');

$routes->get('/dashboard/upload', 'DocumentController::uploadForm', ['as' => 'documents.upload']);
$routes->post('/dashboard/upload', 'DocumentController::store', ['as' => 'documents.store']);
$routes->get('/dashboard/documents/(:num)/download', 'DocumentController::download/$1', ['as' => 'documents.download']);

$routes->get('/kamus-istilah', 'OperationalTermController::index', ['as' => 'terms.index']);
$routes->get('/kamus-istilah/tambah', 'OperationalTermController::create', ['as' => 'terms.create']);
$routes->post('/kamus-istilah', 'OperationalTermController::store', ['as' => 'terms.store']);
