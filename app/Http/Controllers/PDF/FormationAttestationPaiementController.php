<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Model\Formation;
use App\Model\Inscrit;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormationAttestationPaiementController extends Controller
{
    public function index(int $idFormation)
    {
        $formation = Formation::where('id', $idFormation)->with('inscrits')->get()->first();

        $pdf = PDF::loadView('documents.formation.paiements', compact('formation'));

        return $pdf->download();
    }
}
