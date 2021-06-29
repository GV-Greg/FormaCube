<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecrutementCollection;
use App\Http\Resources\RecrutementResource;
use App\Model\Formation;
use App\Model\Inscrit;
use App\Model\RecrutementInscrit;
use Illuminate\Http\Request;
use App\Model\Recrutement;
use Illuminate\Support\Facades\DB;


class RecrutementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $recrutements = Recrutement::orderBy('id', 'DESC')->paginate(10);

        return RecrutementResource::collection($recrutements);
    }

    /**
     * @param $field
     * @param $search
     * @return RecrutementCollection
     */
    public function search($field, $search)
    {
        return new RecrutementCollection(Recrutement::where($field,'LIKE',"%$search%")->latest()->paginate(10));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $recrutements = Recrutement::orderBy('date', 'ASC')->get()->all();

        $listRecrutements = RecrutementResource::collection($recrutements);
        $candidats = [];

        for($x=0; $x < count($recrutements); $x++) {
            $inscrits= 0;
            $inscritsRecrutement = RecrutementInscrit::where('recrutement_id', $recrutements[$x]->id)->get()->all();
            for($y=0; $y < count($inscritsRecrutement); $y++) {
                $inscrits++;
            }
            array_push($candidats, $inscrits);
        }

        return response()->json([
            'recrutements' => $listRecrutements,
            'candidats' => $candidats,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function futurs()
    {
        $today = date('Y-m-d');
        $recrutements = Recrutement::where('date', '>=', $today)->orderBy('date', 'ASC')->get()->all();

        $listRecrutements = RecrutementResource::collection($recrutements);
        $candidats = [];

        for($x=0; $x < count($recrutements); $x++) {
            $inscrits= 0;
            $inscritsRecrutement = RecrutementInscrit::where('recrutement_id', $recrutements[$x]->id)->get()->all();
            for($y=0; $y < count($inscritsRecrutement); $y++) {
                $inscrits++;
            }
            array_push($candidats, $inscrits);
        }

        return response()->json([
            'recrutements' => $listRecrutements,
            'candidats' => $candidats,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function others($idRecrutement, $idFormation)
    {
        $others_recrutements = DB::table('recrutements')
            ->whereNotIn('recrutements.id', [$idRecrutement])
            ->where('formation_id', $idFormation)
            ->select('id', 'date')
            ->orderBy('date','ASC')
            ->get();

        return response()->json([
            'othersRecrutements' => $others_recrutements,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RecrutementResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'formation_id' => 'required',
            'date' => 'required',
        ]);

        $recrutement = new Recrutement();

        $recrutement->formation_id = $request->formation_id;
        $recrutement->date = $request->date;
        $recrutement->save();

        return new RecrutementResource($recrutement);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $recrutement = new RecrutementResource(Recrutement::findOrFail($id));
        $formation = Formation::where('id', $recrutement->formation_id)->with('inscrits')->get()->first();
        $listCandidats = RecrutementInscrit::where('recrutement_id', $id)->orderBy('date_ajout', 'ASC')->get()->all();
        $candidats = [];

        for($i=0; $i<count($listCandidats); $i++) {
            $inscrit = Inscrit::where('id', $listCandidats[$i]->inscrit_id)->get()->first();
            array_push($candidats, $inscrit);
        }

        function build_sorter($key) {
            return function ($a, $b) use ($key) {
                return strnatcmp($a[$key], $b[$key]);
            };
        }

        usort($candidats, build_sorter('nom'));

        return response()->json([
            'recrutement' => $recrutement,
            'formation' => $formation,
            'candidats' => $candidats,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return RecrutementResource
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'formation_id' => 'required',
            'date' => 'required',
        ]);

        $recrutement = Recrutement::findOrFail($id);

        $recrutement->formation_id = $request->formation_id;
        $recrutement->date = $request->date;
        $recrutement->save();

        return new RecrutementResource($recrutement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RecrutementResource
     */
    public function destroy($id)
    {
        $recrutement = Recrutement::find($id);

        $recrutement->delete();

        return new RecrutementResource($recrutement);
    }

    public function addInscrit($id, $idInscrit)
    {
        $recrutement = Recrutement::find($id);
        $inscrit = Inscrit::find($idInscrit);
        $message = null;
        $error= null;
        $test = false;

        $date_ajout = date('Y-m-d');

        $recrutementsInscrit = RecrutementInscrit::where('inscrit_id', $idInscrit)->get()->all();

        for($i=0; $i < count($recrutementsInscrit); $i++) {
            if ($id == $recrutementsInscrit[$i]->recrutement_id) {
                $test = true;
            }
        }
        if($test == false) {
            $recrutement->inscrits()->attach($inscrit, ['date_ajout' => $date_ajout]);
            $message = 'Inscription effectuée';
        } else {
            $error = 'Déjà inscrit à cette formation';
        }

        return response()->json([
            'message' => $message,
            'error' => $error,
        ]);
    }

    public function deleteInscrit($id, $idInscrit)
    {
        $recrutementInscrit = RecrutementInscrit::where('inscrit_id', $idInscrit)->where('recrutement_id', $id)->get()->first();

        $recrutementInscrit->delete();

        return response()->json([
            'message' => 'Inscription supprimée',
        ]);
    }

    public function recrutementsOfFormation($idFormation)
    {
        $recrutements = Recrutement::where('formation_id', $idFormation)->orderBy('date', 'ASC')->get()->all();
        $listCandidats = [];
        for($i=0; $i < count($recrutements); $i++) {
            $candidats = DB::table('inscrits')->where('recrutement_id', $recrutements[$i]->id)
                ->join('inscrit_recrutement', 'inscrits.id', '=', 'inscrit_recrutement.inscrit_id')->select('inscrit_recrutement.id as inscrit_recrutement_id', 'inscrit_recrutement.recrutement_id', 'inscrits.id as inscrit_id', 'inscrits.prenom', 'inscrits.nom')->get();

            array_push($listCandidats, $candidats);
        }

        return response()->json([
            'recrutements' => $recrutements,
            'candidats' => $listCandidats,
        ]);
    }

    public function updateSelection($id)
    {
        Recrutement::where('id', $id)
            ->update([
                'selection' => 1,
            ]);

        return response(['message' => 'Sélection du recrutement effectuée'], 200);
    }

    public function updateProspection($id)
    {
        Recrutement::where('id', $id)
            ->update([
                'prospection' => 1,
            ]);

        return response(['message' => 'Prospection du recrutement effectuée'], 200);
    }

    public function selectionCandidatsNotSelected($id)
    {
        $match = ['recrutement_id' => $id, 'selection' => false];
        $listCandidats = RecrutementInscrit::where($match)->orderBy('date_ajout', 'ASC')->get()->all();
        $candidatsNotSelected = [];

        for($i=0; $i<count($listCandidats); $i++) {
            $inscrit = Inscrit::where('id', $listCandidats[$i]->inscrit_id)->with('tags')->get()->first();
            array_push($candidatsNotSelected, $inscrit);
        }

        return response()->json([
            'candidatsNotSelected' => $candidatsNotSelected,
        ]);
    }
}
