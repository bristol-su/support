<?php

use Illuminate\Support\Facades\Route;

Route::get('/a/{activity_slug}/{module_instance_slug}', 'ModuleInstanceRedirectController@index');
Route::get('/p/{activity_slug}/{module_instance_slug}', 'ModuleInstanceRedirectController@index');