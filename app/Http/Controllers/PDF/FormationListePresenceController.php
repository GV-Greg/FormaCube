<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Model\Formation;
use App\Model\FormationInscrit;
use App\Model\Inscrit;
use Barryvdh\DomPDF\Facade as PDF;

class FormationListePresenceController extends Controller
{
    public function index(int $id) {
        $formation = Formation::find($id);
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

        $pdf = PDF::loadView('documents.formation.presences', compact('formation', 'stagiaires'));
        $name = 'formation-liste-presence.pdf';

        return $pdf->download($name);
    }
}
