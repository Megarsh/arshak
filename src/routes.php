<?php

Route::resource('content/menu',	 							'Arshak\Menumanager\MenuController');
Route::post('content/menu/order',	 						'Arshak\Menumanager\MenuController@saveOrder');
Route::post('content/menu/onoff',	 						'Arshak\Menumanager\MenuController@onoff');
