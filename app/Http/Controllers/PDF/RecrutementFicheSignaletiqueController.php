<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Model\Formation;
use App\Model\Pouvsub;
use App\Model\Recrutement;
use Barryvdh\DomPDF\Facade as PDF;

class RecrutementFicheSignaletiqueController extends Controller
{
    /**
     * @param int $idRecrutement
     * @param int $idCandidat
     * @return mixed
     */
    public function index(int $idRecrutement)
    {
        $recrutement = Recrutement::find($idRecrutement);
        $formation = Formation::where('id', $recrutement->formation_id)->get()->first();
        $pouvsub = Pouvsub::where('id', $formation->pouvsub_id)->get()->first();

        $pdf = PDF::loadView('documents.recrutement.fiche', compact('recrutement', 'formation', 'pouvsub'));

        return $pdf->download();
    }
}
