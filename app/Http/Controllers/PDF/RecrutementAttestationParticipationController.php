<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Model\Formation;
use App\Model\Inscrit;
use App\Model\Recrutement;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class RecrutementAttestationParticipationController extends Controller
{
    /**
     * @param int $idRecrutement
     * @param int $idCandidat
     * @return mixed
     */
    public function index(int $idRecrutement, int $idCandidat)
    {
        $recrutement = Recrutement::find($idRecrutement);
        $formation = Formation::where('id', $recrutement->formation_id)->get()->first();
        $candidat = Inscrit::find($idCandidat);
        $date = Carbon::now()->isoFormat('D MMMM Y');

        $pdf = PDF::loadView('documents.recrutement.participation', compact('recrutement', 'formation', 'candidat', 'date'));

        return $pdf->download();
    }
}
