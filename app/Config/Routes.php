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
$routes->set404Override(function () {
	return view('templates/404_');
});
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index', ['filter' => 'RoleFilter']);

$db = \Config\Database::connect();
$builder = $db->table('route');
$builder->join('permission', 'permission.permID = route.permID', 'inner');
$builder->select([
	'route.*',
	'permission.name'
]);
$builder->where([
	'route.deleted_at' => null,
	'route.status' => 1
]);
$all = $builder->get()->getResultArray();


// dd($all);
$routes->get('/auth', 'Auth::index', ['filter' => 'LoginFilter']);
$routes->get('/login', 'Auth::index', ['filter' => 'LoginFilter']);
$routes->get('/logout', 'Auth::logout');
foreach ($all as $r) {
	if ($r['httpMethod'] == 'get') {
		$routes->get($r['url'], $r['contMeth'], ['filter' => 'RoleFilter:' . $r['name']]);
	} elseif ($r['httpMethod'] == 'post') {
		$routes->post($r['url'], $r['contMeth'], ['filter' => 'RoleFilter:' . $r['name']]);
	} elseif ($r['httpMethod'] == 'put') {
		$routes->put($r['url'], $r['contMeth'], ['filter' => 'RoleFilter:' . $r['name']]);
	} elseif ($r['httpMethod'] == 'delete') {
		$routes->delete($r['url'], $r['contMeth'], ['filter' => 'RoleFilter:' . $r['name']]);
	}
}
// $routes->get('/(:any)', '', ['filter' => 'RoleFilter']);



/*
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
