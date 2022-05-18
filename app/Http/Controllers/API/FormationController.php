<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FormationCollection;
use App\Http\Resources\FormationResource;
use App\Http\Resources\PouvsubInfosResource;
use App\Model\Formation;
use App\Model\FormationInscrit;
use App\Model\Inscrit;
use App\Model\Pouvsub;
use App\Model\PouvsubInfos;
use App\Model\Recrutement;
use App\Model\RecrutementInscrit;
use ArrayObject;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $formations = Formation::orderBy('id', 'DESC')->paginate(10);

        return FormationResource::collection($formations);
    }

    /**
     * @param $field
     * @param $search
     * @return FormationCollection
     */
    public function search($field, $search)
    {
        if($field === 'salle') {
            return new FormationCollection(Formation::whereHas('salle', function($query) use ($search) {
                $query->where('nom', 'LIKE', "%$search%");
            })->latest()->paginate(10));
        } else {
            return new FormationCollection(Formation::where($field,'LIKE',"%$search%")->latest()->paginate(10));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $formations = Formation::orderBy('date_debut', 'ASC')->get()->all();

        $listFormations = FormationResource::collection($formations);

        $stagiaires = [];
        $nbreStagiaires = [];

        if($formations != null) {
            for($x=0; $x < count($formations); $x++) {
                $tempStagiaire = [];
                $maxStagiaires = null;
                $temp = [];
                $etat = '';
                $inscrits= 0;
                $min = $formations[$x]->min_stagiaires;
                $max = $formations[$x]->max_stagiaires;
                $inscritsFormation = FormationInscrit::where('formation_id', $formations[$x]->id)->get()->all();
                $nbreStagiaire = FormationInscrit::where('formation_id', $formations[$x]->id)->count();
                $tempStagiaire[] = $nbreStagiaire;
                $maxStagiaires = $formations[$x]->max_stagiaires;
                $tempStagiaire[] = $maxStagiaires;
                $nbreStagiaires[] = $tempStagiaire;
                for($y=0; $y < count($inscritsFormation); $y++) {
                    $inscrits++;
                }
                $temp[] = $inscrits;
                if($inscrits < $min) {
                    $etat = 'Insuffisant';
                } else if ($inscrits >= $min && $inscrits < $max) {
                    $etat = 'Incomplet';
                } else if ($inscrits == $max) {
                    $etat = 'Complet';
                }
                $temp[] = $etat;
                $stagiaires[] = $temp;
            }
        }

        return response()->json([
            'formations' => $listFormations,
            'stagiaires' => $stagiaires,
            'nbreStagiaires' => $nbreStagiaires,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function others($id)
    {
        $others_formations = DB::table('formations')
            ->whereNotIn('formations.id', [$id])
            ->leftjoin('inscrit_formation', 'formations.id', '=', 'inscrit_formation.formation_id')
            ->select('formations.id', 'formations.nom', 'formations.session', DB::raw('count(inscrit_formation.inscrit_id) as nombre_inscrits'), 'formations.max_stagiaires')
            ->groupBy('formations.id')->get();

        return response()->json([
            'othersFormations' => $others_formations,
        ]);
    }

    /**
     * @return FormationResource
     */
    public function latest()
    {
        return new FormationResource(Formation::orderBy('id', 'DESC')->first());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return FormationResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nom' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'min_stagiaires' => 'required',
            'max_stagiaires' => 'required',
            'nbre_heures' => 'required',
            'salle_id' => 'required',
            'prix' => 'required',
            'statut' => 'required',
            'user_id' => 'required',
            'abreviation' => 'required',
            'duree_rgpd' => 'required'
        ]);

        $formation = new Formation();

        $formation->nom = $request->nom;
        $formation->session = $request->session;
        $formation->date_debut = $request->date_debut;
        $formation->date_fin = $request->date_fin;
        $formation->min_stagiaires = $request->min_stagiaires;
        $formation->max_stagiaires = $request->max_stagiaires;
        $formation->nbre_heures = $request->nbre_heures;
        $formation->salle_id = $request->salle_id;
        $formation->prix = $request->prix;
        $formation->commentaire_formation = $request->commentaire_formation;
        $formation->statut = $request->statut;
        $formation->user_id = $request->user_id;
        $formation->abreviation = $request->abreviation;
        $formation->duree_rgpd = $request->duree_rgpd;
        $formation->save();

        return new FormationResource($formation);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $formation = new FormationResource(Formation::findOrFail($id));
        $recrutements = Recrutement::where('formation_id', $id)->get()->all();

        $stagiaires = DB::table('inscrits')
            ->where('formation_id', $id)
            ->join('inscrit_formation', 'inscrits.id', '=', 'inscrit_formation.inscrit_id')
            ->leftjoin('users as users_rappel', 'users_rappel.id', '=', 'inscrit_formation.rappel_user_id')
            ->leftjoin( 'users as users_rdv', 'users_rdv.id', '=', 'inscrit_formation.rdv_user_id')
            ->select('inscrits.id', 'inscrits.prenom', 'inscrits.nom', 'inscrits.tel', 'inscrits.gsm', 'inscrits.email', 'inscrit_formation.date_rappel', 'inscrit_formation.rappel_user_id', 'users_rappel.firstname as rappel_user_prenom', 'inscrit_formation.rappel_resultat', 'users_rappel.lastname as rappel_user_nom', 'inscrit_formation.date_rdv', 'inscrit_formation.rdv_user_id', 'users_rdv.firstname as rdv_user_prenom', 'users_rdv.lastname as rdv_user_nom', 'inscrit_formation.validation_rdv')
            ->orderBy('inscrits.nom', 'ASC')
            ->get();

        return response()->json([
            'formation' => $formation,
            'recrutements' => $recrutements,
            'stagiaires' => $stagiaires,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return FormationResource
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nom' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'min_stagiaires' => 'required',
            'max_stagiaires' => 'required',
            'nbre_heures' => 'required',
            'salle_id' => 'required',
            'prix' => 'required',
            'statut' => 'required',
            'user_id' => 'required',
            'abreviation' => 'required',
            'duree_rgpd' => 'required'
        ]);

        $formation = Formation::findOrFail($id);

        $formation->nom = $request->nom;
        $formation->session = $request->session;
        $formation->date_debut = $request->date_debut;
        $formation->date_fin = $request->date_fin;
        $formation->min_stagiaires = $request->min_stagiaires;
        $formation->max_stagiaires = $request->max_stagiaires;
        $formation->nbre_heures = $request->nbre_heures;
        $formation->salle_id = $request->salle_id;
        $formation->prix = $request->prix;
        $formation->commentaire_formation = $request->commentaire_formation;
        $formation->statut = $request->statut;
        $formation->user_id = $request->user_id;
        $formation->abreviation = $request->abreviation;
        $formation->duree_rgpd = $request->duree_rgpd;
        $formation->save();

        return new FormationResource($formation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return FormationResource
     */
    public function destroy($id)
    {
        $formation = Formation::find($id);

        $formation->delete();

        return new FormationResource($formation);
    }

    /**
     * @param $id
     * @param $idInscrit
     * @return \Illuminate\Http\JsonResponse
     */
    public function addInscrit($id, $idInscrit)
    {
        $formation = Formation::find($id);
        $inscrit = Inscrit::find($idInscrit);
        $message = null;
        $error= null;
        $test = false;

        $date_ajout = date('Y-m-d');

        $formationsInscrit = FormationInscrit::where('inscrit_id', $idInscrit)->get()->all();

        for($i=0; $i < count($formationsInscrit); $i++) {
            if ($id == $formationsInscrit[$i]->formation_id) {
                $test = true;
            }
        }
        if($test == false) {
            $formation->inscrits()->attach($inscrit, ['date_ajout' => $date_ajout]);
            $message = 'Inscription effectuée';
        } else {
            $error = 'Déjà inscrit à cette formation';
        }

        return response()->json([
            'message' => $message,
            'error' => $error,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $idInscrit
     */
    public function updateInscrit(Request $request, $id, $idInscrit)
    {
        $formationInscrit = FormationInscrit::where('formation_id', $id)->where('inscrit_id', $idInscrit)->get()->first();
        $formationInscrit->save();

        return response()->json([
            'formationInscrit' => $formationInscrit,
        ]);
    }

    /**
     * @param $id
     * @param $idInscrit
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteInscrit($id, $idInscrit)
    {
        $formationInscrit = FormationInscrit::where('inscrit_id', $idInscrit)->where('formation_id', $id)->get()->first();

        $formationInscrit->delete();

        return response()->json([
            'message' => 'Inscription supprimée',
        ]);
    }

    /**
     * @param $idFormation
     * @param $idInscrit
     * @return \Illuminate\Http\JsonResponse
     */
    public function candidatToStagiaire($idRecrutement, $idFormation, $idInscrit)
    {

        $formation = Formation::find($idFormation);
        $inscrit = Inscrit::find($idInscrit);
        $test_exist = false;
        $message = null;
        $error= null;
        $date_ajout = date('Y-m-d');

        $formationsInscrit = FormationInscrit::where('inscrit_id', $idInscrit)->get()->all();

        for($i=0; $i < count($formationsInscrit); $i++) {
            if ($idFormation == $formationsInscrit[$i]->formation_id) {
                $test_exist = true;
            }
        }

        if($test_exist === false) {
            $match = ['recrutement_id' => $idRecrutement, 'inscrit_id' => $idInscrit];
            RecrutementInscrit::where($match)->update([
                'selection' => true
            ]);
            $formation->inscrits()->attach($inscrit, ['date_ajout' => $date_ajout]);
            $message = "$inscrit->prenom $inscrit->nom inscrit à $formation->abreviation";
        } else {
            $error = "ERROR : $inscrit->prenom $inscrit->nom déjà inscrit à $formation->abreviation";
        }

        return response()->json([
            'message' => $message,
            'error' => $error,
        ]);
    }
}
