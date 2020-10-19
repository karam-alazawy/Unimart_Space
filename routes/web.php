
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	//users
	Route::get('/newUser',  'UserController@newUser');
	Route::get('/editUser/{id}', ['as' => 'user.edit', 'uses' => 'UserController@editUser']);
	Route::get('/users',  ['as' => 'user.index', 'uses' => 'UserController@users']);
	Route::get('/inactive',  ['as' => 'user.inactive', 'uses' => 'UserController@inactive']);
	Route::get('/activeUser/{id}', ['as' => 'user.activeUser', 'uses' => 'UserController@activeUser']);
	Route::post('newUser/', ['as' => 'user.add', 'uses' => 'UserController@addNewUser']);
	Route::get('userSubscription/', ['as' => 'user.userSubscription', 'uses' => 'UserController@userSubscription']);
	Route::get('addSubscription/', ['as' => 'user.addSubscription', 'uses' => 'UserController@addSubscription']);
	Route::post('addNewSubscription/', ['as' => 'user.addNewSubscription', 'uses' => 'UserController@addNewSubscription']);
	Route::post('renewalSubscription/', ['as' => 'user.renewalSubscription', 'uses' => 'UserController@renewalSubscription']);
	Route::get('subscriptions/', ['as' => 'user.subscriptions', 'uses' => 'UserController@subscriptions']);
	
	//office
	Route::get('/newOffice',  'OfficeController@newOffice');
	Route::get('/offices',  ['as' => 'office.index', 'uses' => 'OfficeController@offices']);
	Route::post('newOffice/', ['as' => 'office.add', 'uses' => 'OfficeController@addNewOffice']);
	Route::get('/edit/{id}', ['as' => 'office.edit', 'uses' => 'OfficeController@editOffice']);


	//rooms
	Route::get('/newRoom',  'RoomController@newRoom');
	Route::get('/rooms',  ['as' => 'room.index', 'uses' => 'RoomController@rooms']);
	Route::post('newRoom/', ['as' => 'room.add', 'uses' => 'RoomController@addNewRoom']);
	Route::get('getOffice/', ['as' => 'room.getOffice', 'uses' => 'RoomController@getOffice']);


	//tables
	Route::get('/newTable',  'TablesController@newTable');
	Route::get('/tables',  ['as' => 'table.index', 'uses' => 'TablesController@rooms']);
	Route::post('newTable/', ['as' => 'table.add', 'uses' => 'TablesController@addNewRoom']);


//	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);
});
 
