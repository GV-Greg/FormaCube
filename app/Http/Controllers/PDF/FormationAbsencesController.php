<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Model\Formation;
use App\Model\Pouvsub;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class FormationAbsencesController extends Controller
{
    public function index(int $id)
    {
        $formation = Formation::findOrFail($id);

        $pdf = PDF::loadView('documents.formation.absences', compact('formation'));
        $name = 'formation-absences.pdf';

        return $pdf->download($name);
    }
}
