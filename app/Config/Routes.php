<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Konfigurasi routing aplikasi Lokasi Kuliner & Review Jajanan.
 * Filter 'auth' memastikan user sudah login.
 * Filter 'role:admin' memastikan hanya admin yang bisa mengakses.
 */

$routes->get('/', 'Home::index');

// Serve uploaded images
$routes->get('uploads/(:any)', 'Home::serveUpload/$1');

// API endpoint (publik)
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->get('kuliner', 'KulinerApiController::index');
    // Public API: places listing & details
    $routes->get('places', 'PlacesController::index');
    $routes->get('places/(:num)', 'PlacesController::show/$1');

    // Auth
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/logout', 'AuthController::logout');

    // Protected write endpoints (require api token)
    $routes->post('places', 'PlacesController::create', ['filter' => 'apiauth']);
    $routes->post('places/(:num)/reviews', 'PlacesController::createReview/$1', ['filter' => 'apiauth']);
});

// =============================================================================
//  PUBLIC ROUTES (tanpa filter)
// =============================================================================

// --- CREATE (POST) ---
$routes->post('login', 'AuthController::login');
$routes->post('register', 'AuthController::register');

// --- READ (GET) ---
$routes->get('login', 'AuthController::loginForm');
$routes->get('register', 'AuthController::registerForm');
$routes->get('logout', 'AuthController::logout');
$routes->get('places', 'PlaceController::index');
$routes->get('places/(:num)', 'PlaceController::show/$1');

// =============================================================================
//  CONTRIBUTOR ROUTES (filter: auth)
// =============================================================================
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    // ---------------------------------------
    //  CREATE (POST)
    // ---------------------------------------
    $routes->get('places/create', 'PlaceController::create');                // form
    $routes->post('places', 'PlaceController::store');                      // simpan
    $routes->post('places/(:num)/reviews', 'ReviewController::store/$1');
    $routes->post('places/(:num)/favorite', 'FavoriteController::toggle/$1');

    // ---------------------------------------
    //  READ (GET)
    // ---------------------------------------
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('geocode', 'PlaceController::geocode');
    $routes->get('places/(:num)/edit', 'PlaceController::edit/$1');
    $routes->get('reviews/(:num)/edit', 'ReviewController::edit/$1');
    $routes->get('favorites', 'FavoriteController::index');
    $routes->get('notifications', 'NotificationController::index');

    // ---------------------------------------
    //  UPDATE (POST / PUT)
    // ---------------------------------------
    $routes->post('places/(:num)/update', 'PlaceController::update/$1');
    $routes->post('places/(:num)/mark-closed', 'PlaceController::markClosed/$1');
    $routes->post('reviews/(:num)/update', 'ReviewController::update/$1');
    $routes->post('notifications/read', 'NotificationController::markAllRead');

    // ---------------------------------------
    //  DELETE (POST / DELETE)
    // ---------------------------------------
    $routes->post('places/(:num)/delete', 'PlaceController::delete/$1');
    $routes->post('reviews/(:num)/delete', 'ReviewController::delete/$1');
});

// =============================================================================
//  ADMIN ROUTES (filter: role:admin)
// =============================================================================
$routes->group('admin', ['filter' => 'role:admin'], static function ($routes) {

    // ---------------------------------------
    //  CREATE (GET form + POST store)
    // ---------------------------------------
    $routes->get('places/create', 'Admin\PlaceController::create');
    $routes->post('places', 'Admin\PlaceController::store');
    $routes->get('categories/create', 'Admin\CategoryController::create');
    $routes->post('categories', 'Admin\CategoryController::store');
    $routes->get('tags/create', 'Admin\TagController::create');
    $routes->post('tags', 'Admin\TagController::store');

    // ---------------------------------------
    //  READ (GET)
    // ---------------------------------------
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('places', 'Admin\PlaceController::index');
    $routes->get('places/pending', 'Admin\PlaceController::pending');
    $routes->get('places/(:num)/edit', 'Admin\PlaceController::edit/$1');
    $routes->get('categories', 'Admin\CategoryController::index');
    $routes->get('categories/(:num)/edit', 'Admin\CategoryController::edit/$1');
    $routes->get('tags', 'Admin\TagController::index');
    $routes->get('tags/(:num)/edit', 'Admin\TagController::edit/$1');
    $routes->get('users', 'Admin\UserController::index');
    $routes->get('reviews', 'Admin\ReviewController::index');

    // ---------------------------------------
    //  UPDATE (POST / PUT)
    // ---------------------------------------
    $routes->post('places/(:num)/update', 'Admin\PlaceController::update/$1');
    $routes->post('places/(:num)/approve', 'Admin\PlaceController::approve/$1');
    $routes->post('places/(:num)/reject', 'Admin\PlaceController::reject/$1');
    $routes->post('places/(:num)/approve-closed', 'Admin\PlaceController::approveClosed/$1');
    $routes->post('places/(:num)/reject-closed', 'Admin\PlaceController::rejectClosed/$1');
    $routes->put('categories/(:num)', 'Admin\CategoryController::update/$1');
    $routes->put('tags/(:num)', 'Admin\TagController::update/$1');

    // ---------------------------------------
    //  DELETE (POST / DELETE)
    // ---------------------------------------
    $routes->post('places/(:num)/delete', 'Admin\PlaceController::delete/$1');
    $routes->delete('categories/(:num)', 'Admin\CategoryController::delete/$1');
    $routes->delete('tags/(:num)', 'Admin\TagController::delete/$1');
    $routes->delete('users/(:num)', 'Admin\UserController::delete/$1');
    $routes->post('reviews/(:num)/delete', 'Admin\ReviewController::delete/$1');
});
