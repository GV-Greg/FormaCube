<?php

namespace App\Http\Controllers\API;

use App\Exports\ParticipantsExport;
use App\Exports\ProspectsExport;
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

        return (new ParticipantsExport($formation))->download($formation->abreviation . '-participants.xlsx', Excel::XLSX);
    }
}
