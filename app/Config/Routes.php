<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Home
$routes->get('/', 'Home::index', ['filter' => 'role:admin,user']);

// Admin Side
$routes->get('/admin', 'Admin::index', ['filter' => 'role:admin']);
$routes->get('/admin/index', 'Admin::index', ['filter' => 'role:admin']);

$routes->get('/admin/(:any)', 'Admin::detail/$1', ['filter' => 'role:admin']);

$routes->get('/exam', 'Admin::exam', ['filter' => 'role:admin']);
$routes->get('/admin/exam', 'Admin::exam', ['filter' => 'role:admin']);

$routes->get('/exam/(:any)', 'Admin::exam_detail/$1', ['filter' => 'role:admin']);
$routes->get('/admin/exam/(:any)', 'Admin::exam_detail/$1', ['filter' => 'role:admin']);

$routes->get('/question', 'Admin::question', ['filter' => 'role:admin']);
$routes->get('/admin/question', 'Admin::question', ['filter' => 'role:admin']);

$routes->get('/user_enroll', 'Admin::user_enroll', ['filter' => 'role:admin']);
$routes->get('/admin/user_enroll', 'Admin::user_enroll', ['filter' => 'role:admin']);

$routes->get('/user_exam_result', 'Admin::user_exam_result', ['filter' => 'role:admin']);
$routes->get('/admin/user_exam_result', 'Admin::user_exam_result', ['filter' => 'role:admin']);

$routes->get('/admin_exam_result', 'Admin::admin_exam_result', ['filter' => 'role:admin']);
$routes->get('/admin/admin_exam_result', 'Admin::admin_exam_result', ['filter' => 'role:admin']);

// User Side
$routes->get('/user/(:num)', 'User::update/$1', ['filter' => 'role:admin,user']);

$routes->get('/update_password/(:num)', 'User::update_password/$1', ['filter' => 'role:admin,user']);

$routes->get('/exam_detail/(:num)/(:any)', 'User::exam_detail/$1/$2', ['filter' => 'role:admin,user']);
$routes->get('/user/exam_detail/(:num)/(:any)', 'User::exam_detail/$1/$2', ['filter' => 'role:admin,user']);

$routes->get('/exam_list', 'User::exam_list', ['filter' => 'role:user']);
$routes->get('/user/exam_list', 'User::exam_list', ['filter' => 'role:user']);

$routes->get('/exam_view/(:any)', 'User::exam_view/$1', ['filter' => 'role:user']);
$routes->get('/user/exam_view/(:any)', 'User::exam_view/$1', ['filter' => 'role:user']);

$routes->get('/exam_result/(:any)', 'User::exam_result/$1', ['filter' => 'role:user']);
$routes->get('/user/exam_result/(:any)', 'User::exam_result/$1', ['filter' => 'role:user']);




/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
