<?php
//Routes

//home
$app->get('/', 'HomeController:home')->setName('home');
$app->post('/contact', 'HomeController:contact')->setName('home.contact');

//admin
$app->get('/admin', 'AdminController:index')->setName('admin');

//songs
$app->get('/admin/songs', 'AdminController:songView')->setName('admin.songs');
$app->get('/admin/add-song', 'AdminController:addSongView')->setName('admin.addSongView');
$app->post('/admin/add-song', 'AdminController:addSong')->setName('admin.addSong');
$app->get('/admin/edit-song/{id}', 'AdminController:editSongView')->setName('admin.editSongView');
$app->post('/admin/editSong/{id}', 'AdminController:editSong')->setName('admin.editSong');
$app->get('/admin/delete-song/{id}', 'AdminController:deleteSong')->setName('admin.deleteSong');

//kits
$app->get('/admin/kits', 'AdminController:kitView')->setName('admin.kits');
$app->get('/admin/add-kit', 'AdminController:addKitView')->setName('admin.addKitView');
$app->post('/admin/add-kit', 'AdminController:addKit')->setName('admin.addKit');
$app->get('/admin/edit-kit/{id}', 'AdminController:editKitView')->setName('admin.editKitView');
$app->post('/admin/editKit/{id}', 'AdminController:editKit')->setName('admin.editKit');
$app->get('/admin/delete-kit/{id}', 'AdminController:deleteKit')->setName('admin.deleteKit');

//Messages
$app->get('/admin/messages', 'AdminController:messageView')->setName('admin.messages');
$app->get('/admin/messages/{id}', 'AdminController:viewMessage')->setName('admin.viewMessage');
$app->get('/admin/message/delete-message/{id}', 'AdminController:deleteMessage')->setName('admin.deleteMessage');

//Service
$app->get('/services', 'ServiceController:index')->setName('service.index');
$app->post('/services', 'ServiceController:service');

//Admin Services
$app->get('/admin/services', 'AdminController:serviceView')->setName('admin.services');
$app->get('/admin/services/{id}', 'AdminController:viewService')->setName('admin.viewService');
$app->get('/admin/services/delete-service/{id}', 'AdminController:deleteService')->setName('admin.deleteService');

//Admin Orders
$app->get('/admin/orders', 'AdminController:orderView')->setName('admin.orders');
$app->get('/admin/orders/delete-order/{id}', 'AdminController:deleteOrder')->setName('admin.deleteOrder');


$app->get('/cart', 'CartController:index')->setName('cart.index');
$app->get('/cart/add/{cart}/{id}', 'SessionStorage:addToStorage')->setName('session.addToStorage');
$app->get('/cart/delete/{cart}/{id}', 'SessionStorage:deleteFromStorage')->setName('session.deleteFromStorage');
$app->post('/cart/email', 'CartController:cartEmail')->setName('cart.email');

//confirmed
$app->get('/confirm[/{tx}]', 'ConfirmController:index');