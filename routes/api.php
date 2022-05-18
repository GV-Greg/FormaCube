<?php

use Illuminate\Support\Facades\Route;

/*  Gestion de l'authentification */
Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::get('getme', 'AuthController@me');
});

/*  Si authentifié ! */
Route::group(['middelware' => 'jwt.auth'], function ($router) {
    /*  Gestion de la modification d'un profil utilisateur par un administrateur */
    Route::put('profil/edit/{id}', 'API\UserController@updateProfile');

    /*  Gestion des utilisateurs  */
    Route::prefix('users')->group( function() {
        Route::get('', 'UsersController@all');
        Route::get('/tuteurs', 'UsersController@tuteurs');
        Route::get('/admins', 'UsersController@admins');
        Route::get('/archives', 'UsersController@archives');
        Route::post('/create', 'UsersController@new');
        Route::get('/edit/{id}', 'UsersController@edit');
        Route::put('/edit/{id}', 'UsersController@update');
        Route::get('/restore/{id}', 'UsersController@restore');
        Route::get('/{id}', 'UsersController@show');
        Route::delete('/{id}', 'UsersController@destroy');
    });
    /*  Recherche pour les salles  */
    Route::get('search/users/{colonne}/{search}', 'UsersController@search');
    Route::get('search/users/archives/{colonne}/{search}', 'UsersController@searchArchives');

    /*  Gestion des salles  */
    Route::prefix('salles')->group( function() {
        Route::get('', 'API\SalleController@index')->name('salles.index');
        Route::get('/all', 'API\SalleController@all')->name('salles.all');
        Route::post('/create', 'API\SalleController@store')->name('salles.create');
        Route::put('/edit/{id}', 'API\SalleController@update')->name('salles.update');
        Route::delete('/{id}', 'API\SalleController@destroy')->name('salles.destroy');
    });
    /*  Recherche pour les salles  */
    Route::get('search/salles/{colonne}/{search}', 'API\SalleController@search');

    /*  Gestion des formations  */
    Route::prefix('formations')->group( function() {
        Route::get('', 'API\FormationController@index')->name('formations.index');
        Route::get('/all', 'API\FormationController@all')->name('formations.all');
        Route::get('/others/{id}', 'API\FormationController@others')->name('formations.others');
        Route::get('/latest', 'API\FormationController@latest')->name('formations.latest');
        Route::post('/create', 'API\FormationController@store')->name('formations.store');
        Route::put('/edit/{id}', 'API\FormationController@update')->name('formations.update');
        Route::get('/show/{id}', 'API\FormationController@show')->name('formations.show');
        Route::delete('/{id}', 'API\FormationController@destroy')->name('formations.destroy');
        Route::post('/addInscrit/{formation}/{inscrit}', 'API\FormationController@addInscrit')->name('formations.addInscrit');
        Route::post('/updateInscrit/{formation}/{inscrit}', 'API\FormationController@updateInscrit')->name('formations.updateInscrit');
        Route::delete('/deleteInscrit/{formation}/{inscrit}', 'API\FormationController@deleteInscrit')->name('formations.deleteInscrit');
        Route::post('/candidatToStagiaire/{recrutement}/{formation}/{inscrit}', 'API\FormationController@candidatToStagiaire')->name('formations.candidatToStagiaire');
    });
    /*  Recherche pour les formations  */
    Route::get('search/formations/{colonne}/{search}', 'API\FormationController@search');

    /*  Gestion des recrutements  */
    Route::prefix('recrutements')->group( function() {
        Route::get('', 'API\RecrutementController@index')->name('recrutements.index');
        Route::get('/all', 'API\RecrutementController@all')->name('recrutements.all');
        Route::get('/futurs', 'API\RecrutementController@futurs')->name('recrutements.futurs');
        Route::get('/others/{id}/{formation}', 'API\RecrutementController@others')->name('recrutements.others');
        Route::post('', 'API\RecrutementController@store')->name('recrutements.store');
        Route::put('/{id}', 'API\RecrutementController@update')->name('recrutements.update');
        Route::get('/show/{id}', 'API\RecrutementController@show')->name('recrutements.show');
        Route::delete('/{id}', 'API\RecrutementController@destroy')->name('recrutements.destroy');
        Route::post('/addInscrit/{recrutement}/{inscrit}', 'API\RecrutementController@addInscrit')->name('recrutements.addInscrit');
        Route::delete('/deleteInscrit/{recrutement}/{inscrit}', 'API\RecrutementController@deleteInscrit')->name('recrutements.deleteInscrit');
        Route::get('/recrutementsFormation/{id}', 'API\RecrutementController@recrutementsOfFormation')->name('recrutements.recrutementsOfFormation');
        Route::get('/selection/{id}', 'API\RecrutementController@selectionCandidatsNotSelected')->name('recrutements.selectionCandidatsNotSelected');
        Route::put('/selection/{id}', 'API\RecrutementController@updateSelection')->name('recrutements.updateSelection');
        Route::put('/prospection/{id}', 'API\RecrutementController@updateProspection')->name('recrutements.updateProspection');
    });
    /*  Recherche pour les recrutements  */
    Route::get('search/recrutements/{colonne}/{search}', 'API\RecrutementController@search');

    /*  Gestion des inscrits  */
    Route::prefix('inscrits')->group( function() {
        Route::get('', 'API\InscritController@index')->name('inscrits.index');
        Route::get('/latest', 'API\InscritController@latest')->name('inscrits.latest');
        Route::post('/create', 'API\InscritController@store')->name('inscrit.store');
        Route::put('/statut/{id}', 'API\InscritController@modifStatut')->name('statut-inscrit.update');
        Route::get('/edit/{id}', 'API\InscritController@edit')->name('inscrit.edit');
        Route::put('/update/{id}', 'API\InscritController@update')->name('inscrit.update');
        Route::get('/show/{id}', 'API\InscritController@show')->name('inscrit.show');
        Route::get('search', 'API\InscritSearchController@index');
        Route::delete('/{id}', 'API\InscritController@destroy')->name('inscrit.destroy');
        Route::post('/addTags/{id}', 'API\InscritController@addTags')->name('inscrits.addtags');
        Route::post('/addTagsAfterRecrutement', 'API\InscritController@addTagsAfterRecrutement')->name('recrutements.addInscritAfterRecrutement');
        Route::delete('/deleteTags/{id}/{tag}/{currentUser}', 'API\InscritController@deleteTags')->name('inscrits.deletetags');
        Route::post('/rdv/{idInscrit}/{idFormation}', 'API\InscritController@storeRdv')->name('inscrits.storeRdv');
        Route::put('/rdv/{idInscrit}/{idFormation}', 'API\InscritController@validateRdv')->name('inscrits.validateRdv');
        Route::delete('/rdv/{idInscrit}/{idFormation}', 'API\InscritController@deleteRdv')->name('inscrits.deleteRdv');
        Route::put('/rappel/{idInscrit}/{idFormation}', 'API\InscritController@storeRappel')->name('inscrits.store-rappel');
        Route::put('/formation/{idInscrit}/{idFormation}/{idNewFormation}', 'API\InscritController@reportStagiaire')->name('inscrits.report-stagiaire');
        Route::put('/recrutement/{id}/{idRecrutement}/{idNewRecrutement}', 'API\InscritController@reportCandidat')->name('inscrits.report-candidat');
        Route::put('/candidatToProspect/{id}/{traitement}/{prospect}', 'API\InscritController@candidatToProspect')->name('inscrits.candidatToProspect');
        Route::get('/prospects', 'API\InscritController@prospects')->name('prospects.index');
    });
    /*  Recherche pour les inscrits ou les prospects  */
    Route::get('search/inscrits/{colonne}/{search}', 'API\InscritController@search');
    Route::get('search/inscrits/prospects/tags/{search}', 'API\InscritController@searchProspects');

    /*  Liste des villes  */
    Route::prefix('villes')->group( function() {
        Route::get('', 'API\VilleController@index')->name('villes.index');
    });

    /*  Gestion des tags  */
    Route::prefix('tags')->group( function() {
        Route::get('', 'API\TagController@index')->name('tags.index');
        Route::get('/all', 'API\TagController@all')->name('tags.all');
        Route::post('/create', 'API\TagController@store')->name('tags.store');
        Route::put('/edit/{id}', 'API\TagController@update')->name('tags.update');
        Route::delete('/{id}', 'API\TagController@destroy')->name('tags.destroy');
    });
    /*  Recherche pour les tags  */
    Route::get('search/tags/{colonne}/{search}', 'API\TagController@search');

    /*  Gestion des logs  */
    Route::prefix('logs')->group( function() {
        Route::get('/show/{id}', 'API\LogController@show')->name('logs.show');
    });

    /*  Test de Récupération de 10 contacts de la liste de la newsletter via l'API de Send in blue  */
    Route::get('contacts', 'SendInBlueController@contacts');

    /* Exports de fichiers excel */
    Route::prefix('excel')->group( function() {
        Route::get('/formation-participants/{id}', 'API\ExportsExcelController@formationListeParticipants')->name('excel.formation.participants');
    });
});
