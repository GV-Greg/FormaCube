<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Model\Formation;
use App\Model\Inscrit;
use App\Model\Pouvsub;
use App\Model\Recrutement;
use App\Model\RecrutementInscrit;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RecrutementListePresenceController extends Controller
{
    public function index($id) {
        $recrutement = Recrutement::find($id);
        $formation = Formation::where('id', $recrutement->formation_id)->get()->first();
        $pouvsub = Pouvsub::where('id', $formation->pouvsub_id)->get()->first();
        $listCandidats = RecrutementInscrit::where('recrutement_id', $recrutement->id)->get()->all();
        $candidats= [];

        foreach($listCandidats as $candidat) {
            $candidat = Inscrit::where('id', $candidat->inscrit_id)->get()->first();
            array_push($candidats, $candidat);
        }

        function build_sorter($key) {
            return function ($a, $b) use ($key) {
                return strnatcmp($a[$key], $b[$key]);
            };
        }

        usort($candidats, build_sorter('nom'));

        $pdf = PDF::loadView('documents.recrutement.presences', compact('recrutement', 'formation', 'pouvsub', 'candidats'));
        $date = $recrutement->date;
        $name = $date . '_recrutement-liste-presence.pdf';

        return $pdf->download($name);
    }
}
