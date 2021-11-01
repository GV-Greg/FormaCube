<?php

use App\Http\Controllers\PDF\FormationAbsencesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});
//
//Auth::routes(['register' => false]);

//Route::get('/app', 'AppController@index')->name('app');
//
//Route::get('/{any}', function () {
//    return redirect('app')->with('status', 'Page non trouvée');
//})->where('any', '.*');
//
//Route::get('{ any }', function () {
//    return view('main');
//})->where('any', '.*');

// Génération de documents pdf à télécharger
Route::get('/PDF/recrutement/presences/{id}', 'PDF\RecrutementListePresenceController@index');
Route::get('/PDF/formation/presences/{id}', 'PDF\FormationListePresenceController@index');
Route::get('/PDF/formation/absences/{id}', 'PDF\FormationAbsencesController@index');
