<?php
/**
 * Created by PhpStorm.
 * User: i.lomtev
 * Date: 19.01.17
 * Time: 15:35
 */

use App\Task;
use Illuminate\Http\Request;

Route::get('/tasks', 'TaskController@index');
Route::post('/task', 'TaskController@save');
Route::delete('/task-destroy/{task}', 'TaskController@destroy');
Route::update('/task-update/{task}', 'TaskController@update');