<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Model\Formation;
use App\Model\Inscrit;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class FormationAttestationParticipationController extends Controller
{
    public function index(int $idFormation, int $idStagiaire)
    {
        $formation = Formation::find($idFormation);
        $stagiaire = Inscrit::find($idStagiaire);
        $date_courte = Carbon::now()->isoFormat('DD-MM-YY');
        $date_longue = Carbon::now()->isoFormat('D MMMM Y');

        $pdf = PDF::loadView('documents.formation.participation', compact('formation', 'stagiaire', 'date_courte', 'date_longue'));

        return $pdf->download();
    }
}
