<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Model\Formation;
use App\Model\Pouvsub;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class FormationAttestationDeplacementController extends Controller
{
    public function index(int $idFormation)
    {
        $formation = Formation::where('id', $idFormation)->with('inscrits')->get()->first();
        $date_courte = Carbon::now()->isoFormat('DD-MM-YY');
        $date_longue = Carbon::now()->isoFormat('D MMMM Y');

        $pdf = PDF::loadView('documents.formation.deplacement', compact('formation', 'date_courte', 'date_longue'));

        return $pdf->download();
    }
}
