<?php
Route::get('/login', ['as' => 'login', 'uses' => 'PortalController@login']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'PortalController@logout']);