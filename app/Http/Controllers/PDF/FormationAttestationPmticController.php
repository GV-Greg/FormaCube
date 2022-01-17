<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Model\Formation;
use App\Model\Pouvsub;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormationAttestationPmticController extends Controller
{
    public function index(int $idFormation)
    {
        $formation = Formation::where('id', $idFormation)->with('inscrits')->get()->first();
        $today = Carbon::now();

        $pdf = PDF::loadView('documents.formation.pmtic', compact('formation', 'today'))->setPaper('A4', 'landscape');

        return $pdf->download();
    }
}
