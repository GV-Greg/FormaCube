<?php

namespace App\Http\Controllers\API;

use App\Exports\ParticipantsExport;
use App\Exports\ProspectsExport;
use App\Exports\SuiviExport;
use App\Http\Controllers\Controller;
use App\Model\Formation;
use Maatwebsite\Excel\Excel;

class ExportsExcelController extends Controller
{
    /**
     * @param int $id
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function formationListeParticipants(int $id)
    {
        $formation = Formation::where('id', $id)->get()->first();

        return (new ParticipantsExport($formation))->download($formation->abreviation . '-participants', Excel::XLSX);
    }

    public function formationSuiviStagiaires(int $id)
    {
        $formation = Formation::where('id', $id)->get()->first();

        return (new SuiviExport($formation))->download('DOC_336 DE Cefora - Suivi données stagiaires - ' . $formation->nom, Excel::XLSX);
    }
}
