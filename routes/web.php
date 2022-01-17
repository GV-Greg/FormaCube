<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});

// Génération de documents pdf à télécharger pour les recrutements
Route::get('/PDF/recrutement/presences/{id}', 'PDF\RecrutementListePresenceController@index');
Route::get('/PDF/recrutement/participation/{recrutement}/{candidat}', 'PDF\RecrutementAttestationParticipationController@index');
Route::get('/PDF/recrutement/fiche/{recrutement}', 'PDF\RecrutementFicheSignaletiqueController@index');

// Génération de documents pdf à télécharger pour les formations
Route::get('/PDF/formation/presences/{id}', 'PDF\FormationListePresenceController@index');
Route::get('/PDF/formation/absences/{id}', 'PDF\FormationAbsencesController@index');
Route::get('/PDF/formation/participation/{formation}/{stagiaire}', 'PDF\FormationAttestationParticipationController@index');
Route::get('/PDF/formation/deplacement/{formation}', 'PDF\FormationAttestationDeplacementController@index');
Route::get('/PDF/formation/paiements/{formation}', 'PDF\FormationAttestationPaiementController@index');
Route::get('/PDF/formation/pmtic/{formation}', 'PDF\FormationAttestationPmticController@index');
