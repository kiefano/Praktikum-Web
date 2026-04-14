<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('capstone', function() {
    return "<h1>Tugas Capstone 2: Routes</h1><p>Nama: Kiefano Danendra Azfa</p><p>NIM: A11.2024.1583</p>";
});

$routes->get('cek/(:num)', function($id) {
    return "Parameter ID yang kamu masukkan adalah: " . $id;
});