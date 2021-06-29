<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Model\Formation;
use App\Model\FormationInscrit;
use App\Model\Inscrit;
use App\Model\Pouvsub;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class FormationListePresenceController extends Controller
{
    public function index($id) {
        $formation = Formation::find($id);
        $pouvsub = Pouvsub::where('id', $formation->pouvsub_id)->get()->first();
        $listStagiaires = FormationInscrit::where('formation_id', $formation->id)->get()->all();
        $stagiaires = [];

        foreach($listStagiaires as $stagiaire) {
            $stagiaire = Inscrit::where('id', $stagiaire->inscrit_id)->get()->first();
            array_push($stagiaires, $stagiaire);
        }

        // fonction de tri des stagiaires par nom
        function build_sorter($key) {
            return function ($a, $b) use ($key) {
                return strnatcmp($a[$key], $b[$key]);
            };
        }

        usort($stagiaires, build_sorter('nom'));

        $pdf = PDF::loadView('documents.formation.presences', compact('formation', 'pouvsub', 'stagiaires'));
        $name = 'formation-liste-presence.pdf';

        return $pdf->download($name);
    }
}
