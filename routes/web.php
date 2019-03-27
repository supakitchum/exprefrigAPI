<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/key', function(){
    return str_random(32);
});

//RESTful API
$router->get('/get', 'Api\ExprefrigController@getItem');

$router->get('/get/all/myDevice/{uid}', 'Api\ExprefrigController@getAllMyDevice');

$router->post('/get/user', 'Api\ExprefrigController@getMember');

$router->get('/get/app/{uid}','Api\ExprefrigController@getByApplication');

$router->get('/get/app/device/uid={uid}&rid={rid}','Api\ExprefrigController@getDevice');

$router->get('/myDevice/{uid}', 'Api\ExprefrigController@getMyDevice');

$router->get('/get/device/{pv_key}', 'Api\ExprefrigController@getByDevice');

$router->post('/rm/item', 'Api\ExprefrigController@deleteItem');

$router->post('/add/image/{pv_key}', 'Api\ExprefrigController@addImage');

$router->post('/refrigerator/add', 'Api\ExprefrigController@addRefrigerator');

$router->post('/refrigerator/addItem', 'Api\ExprefrigController@addItem');

$router->post('/refrigerator/del', 'Api\ExprefrigController@delRefrigerator');

$router->post('/post/register', 'Api\ExprefrigController@register');

$router->post('/post/login', 'Api\ExprefrigController@login');

$router->put('/put/{table}/{id}', 'Api\ExprefrigController@putItem');

$router->put('/activated', 'Api\ExprefrigController@activated');

$router->put('/update/{table}/{id}', 'Api\ExprefrigController@updateItem');

$router->delete('/delete/{id}', 'Api\ExprefrigController@deleteRefrigerator');