<?php
Route::get('/login', ['as' => 'redirecionarLogin', 'uses' => 'PortalController@login']);
Route::get('/logout', ['as' => 'redirecionarLogout', 'uses' => 'PortalController@logout']);