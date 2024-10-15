<?php

Route::get('getByServicioId/{configuracionServicio}', 'ServicioEspacioController@getByServicioId');
Route::get('getMercancia/{date}', 'MercanciaController@getMercanciaByDate');
Route::get('getVisitaSetting', 'VisitaAllowController@get');
Route::get('getConfiguracionPatio/{id}', 'configuracionPatioController@getByServicioId');
Route::post('saveSolicitud', 'solicitudController@store');
Route::get('getPendingRequest/{bl}', 'solicitudController@getPendingRequest');
Route::post('cancelSolicitudActive', 'solicitudController@cancelSolicitudByBl');

Route::post('availableSpace', 'ServicioEspacioController@getAvailableSpace');



Route::post('login', 'Auth\AuthController@login');
Route::post('login/social', 'Auth\SocialLoginController@index');
Route::post('logout', 'Auth\AuthController@logout');


Route::get('impedimento/{documento}/{tipo}',"impedimentoController@impedimentos");
Route::post('getContainerByBl',"contenedoresController@getContenedores");
Route::get('cancel/solicitud/{hash}/{open?}',"solicitudController@cancelSolicitud");
Route::get("close/current/tab",function( Request $request){
    echo '<script>window.close();</script>';
        return '<script>window.location.href="javascript:window.close()"</script>';
      
});
Route::get('store/cancel/solicitud/{hash}',"solicitudController@storeCancelSolicitud");
//Route::post('visita',"VisitaController@createMeeting");
Route::post('visita',"impedimentoController@createMeeting");


Route::get('solicitud/cambio/condicion/{hash}',"solicitudController@cambiarCondicion");
Route::post('solicitud/store/cambio/condicion', [
    'as' => 'solicitud.cambiar_condicion',
    'uses' => 'solicitudController@storeCambio'
    ]);

Route::get('solicitud/cambio/acceso/zona/verificacion/{hash}',
    [
    'as' => 'solicitud.acceso_zona_verificacion',
    'uses' => 'solicitudController@cambiarAccesoZonaVerificacion'
    ]);

Route::post('solicitud/store/cambio/acceso/zona/verificacion', [
    'as' => 'solicitud.cambiar_acceso_zona_verificacion',
    'uses' => 'solicitudController@saveAccesoZonaVerificacion'
    ]);


if (settings('reg_enabled')) {
    Route::post('register', 'Auth\RegistrationController@index');
    Route::post('register/verify-email/{token}', 'Auth\RegistrationController@verifyEmail');
}

if (settings('forgot_password')) {
    Route::post('password/remind', 'Auth\Password\RemindController@index');
    Route::post('password/reset', 'Auth\Password\ResetController@index');
}

Route::get('stats', 'StatsController@index');

Route::get('me', 'Profile\DetailsController@index');
Route::patch('me/details', 'Profile\DetailsController@update');
Route::patch('me/details/auth', 'Profile\AuthDetailsController@update');
Route::put('me/avatar', 'Profile\AvatarController@update');
Route::delete('me/avatar', 'Profile\AvatarController@destroy');
Route::put('me/avatar/external', 'Profile\AvatarController@updateExternal');
Route::get('me/sessions', 'Profile\SessionsController@index');

if (settings('2fa.enabled')) {
    Route::put('me/2fa', 'Profile\TwoFactorController@update');
    Route::delete('me/2fa', 'Profile\TwoFactorController@destroy');
}

Route::resource('users', 'Users\UsersController', [
    'except' => 'create'
]);

Route::put('users/{user}/avatar', 'Users\AvatarController@update');
Route::put('users/{user}/avatar/external', 'Users\AvatarController@updateExternal');
Route::delete('users/{user}/avatar', 'Users\AvatarController@destroy');

if (settings('2fa.enabled')) {
    Route::put('users/{user}/2fa', 'Users\TwoFactorController@update');
    Route::delete('users/{user}/2fa', 'Users\TwoFactorController@destroy');
}

Route::get('users/{user}/activity', 'Users\ActivityController@index');
Route::get('users/{user}/sessions', 'Users\SessionsController@index');

Route::get('/sessions/{session}', 'SessionsController@show');
Route::delete('/sessions/{session}', 'SessionsController@destroy');

Route::get('/activity', 'ActivityController@index');

Route::resource('roles', 'Authorization\RolesController', [
    'except' => 'create'
]);
Route::get("roles/{role}/permissions", 'Authorization\RolePermissionsController@show');
Route::put("roles/{role}/permissions", 'Authorization\RolePermissionsController@update');

Route::resource('permissions', 'Authorization\PermissionsController', [
    'except' => 'create'
]);

Route::get('/settings', 'SettingsController@index');

Route::get('/countries', 'CountriesController@index');




