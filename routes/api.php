<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

/**
 * 小程序接口
 */
//支付回调
Route::any('notify','Api\EntryController@notify');
Route::any('notify2','Api\EntryController@notify2');

//Route::group(['prefix'=>'','namespace'=>'Api','middleware'=>['mini.sign'] ],function(){
Route::group(['prefix'=>'','namespace'=>'Api','middleware'=>[] ],function(){
    Route::get('get_session_key','LoginController@get_session_key');
    Route::post('auth_login','LoginController@auth_login');
	Route::post('user_bind_mobile','LoginController@bind_phone');
	
    Route::get('trains','TrainController@trains');
    Route::get('trains_show/{id}','TrainController@show');
	Route::get('train_setting/{id}','TrainController@train_setting');
	
    Route::post('save_nursery_students','StudentsController@save_nursery_students');
    Route::get('nursery_students','StudentsController@nursery_students');
    Route::get('nursery_students_edit/{id}','StudentsController@nursery_students_edit');
    Route::post('nursery_students_update','StudentsController@nursery_students_update');
    Route::post('save_apply_students','StudentsController@save_apply_students');
    Route::get('apply_students_del/{id}','StudentsController@apply_students_del');
	Route::get('order_students/{id}','StudentsController@get_order_students');
    Route::post('get_not_order_students','StudentsController@get_not_order_students');
	Route::post('update_order_students','StudentsController@update_order_students');
	
    Route::post('save_order','EntryController@save_order');
    Route::post('go_pay','EntryController@go_pay');
    Route::get('get_orders','EntryController@get_orders');
    Route::get('get_order_by_phone','EntryController@get_order_by_phone');
    Route::get('cancel_order/{id}','EntryController@cancel_order');
	Route::get('del_order/{id}','EntryController@del_order');
    Route::get('order_detail/{id}','EntryController@order_detail');
	Route::get('activate_order/{id}','EntryController@activate_order');
	Route::post('order_pay_state','EntryController@order_pay_state');
	//证书
	Route::post('get_cert_by_phone','CertController@get_cert_by_phone');
	Route::post('student_cert','CertController@student_cert');
	Route::post('cert_detail','CertController@cert_detail');
	Route::get('order_cert/{order_id}','CertController@order_cert');
    Route::resource('cert','CertController');
	//公用
    Route::get('profess','CommonController@profess');
	//发送模板消息
    Route::get('send_template','EntryController@send_template');
    Route::post('check_contract','CheckController@check_contract');
    Route::get('send_code','CheckController@send_code');
    Route::post('check_code','CheckController@check_code');

    Route::post('upload_image','ImageUploadController@uploadImage');
});
