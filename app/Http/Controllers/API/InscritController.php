<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\InscritCollection;
use App\Http\Resources\InscritResource;
use App\Model\Formation;
use App\Model\FormationInscrit;
use App\Model\Inscrit;
use App\Model\InscritTag;
use App\Model\Log;
use App\Model\Recrutement;
use App\Model\RecrutementInscrit;
use App\Model\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscritController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $inscrits = Inscrit::orderBy('id', 'DESC')->paginate(10);

        return InscritResource::collection($inscrits);
    }

    /**
     * @param $field
     * @param $search
     * @return InscritCollection
     */
    public function search($field, $search): InscritCollection
    {
        return new InscritCollection(Inscrit::where($field,'LIKE',"%$search%")->latest()->paginate(10));
    }


    /**
     * @return InscritResource
     */
    public function latest(): InscritResource
    {
        return new InscritResource(Inscrit::orderBy('id', 'DESC')->first());
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|InscritResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse|InscritResource
    {
        $this->validate($request, [
            'nom' => 'required',
            'prenom' => 'required',
        ]);

        $search = false;

        if($request->email != null) {
            $search = Inscrit::where('email', $request->email)->exists();
        }

        if($search === false) {
            $inscrit = new Inscrit();
            $inscrit->nom = $request->nom;
            $inscrit->prenom = $request->prenom;
            $inscrit->genre = $request->genre;
            $inscrit->date_naissance = $request->date_naissance;
            $inscrit->rue = $request->rue;
            $inscrit->numero = $request->numero;
            $inscrit->boite = $request->boite;
            $inscrit->ville_id = $request->ville_id;
            $inscrit->email = $request->email;
            $inscrit->tel = $request->tel;
            $inscrit->gsm = $request->gsm;
            $inscrit->commentaire_inscrit = $request->commentaire_inscrit;
            $inscrit->prospect = $request->prospect;
            $inscrit->newsletter = $request->newsletter;
            $inscrit->save();
        } else {
            $inscrit = Inscrit::where('email', $request->email)->get()->first();
        }

        // Si inscrit est un prospect, création des tags, s'il y en a, lié à l'inscrit
        if($request->prospect === 1) {
            $tags =  $request->tags;
            if($tags !== []) {
                for($i=0; $i < count($tags); $i++) {
                    $tag = Tag::where('tag', '=', $tags[$i])->get();
                    if($search === false) {
                        $inscrit->tags()->attach($tag);
                    } else {
                        $inscrit->tags()->syncWithoutDetaching($tag);
                    }
                }
            }
        }

        $date_ajout = date('Y-m-d');

        $idrecrutement =  $request->recrutement;
        if($idrecrutement !== null) {
            $recrutement = Recrutement::find($idrecrutement);
            if($search === false) {
                $inscrit->recrutements()->attach($recrutement, ['date_ajout' => $date_ajout]);
            } else {
                $searchRecrutement = RecrutementInscrit::where(['recrutement_id' => $idrecrutement, 'inscrit_id' => $inscrit->id])->exists();
                if($searchRecrutement === false) {
                    $inscrit->recrutements()->attach($recrutement, ['date_ajout' => $date_ajout]);
                }
            }
        }

        $idformation =  $request->formation;
        if($idformation !== null) {
            $formation = Formation::find($idformation);
            if($search === false) {
                $inscrit->formations()->attach($formation, ['date_ajout' => $date_ajout]);
            } else {
                $searchFormation = FormationInscrit::where(['formation_id' => $idformation, 'inscrit_id' => $inscrit->id])->exists();
                if($searchFormation === false) {
                    $inscrit->formations()->attach($formation, ['date_ajout' => $date_ajout]);
                }
            }
        }

        if($search === false) {
            // Ajout de log de création de la fiche
            $last_inscrit = DB::table('inscrits')->latest()->first();
            $this->addLog(true, false, $last_inscrit->id, $request->current_user, 'création de la fiche');

            return new InscritResource($inscrit);
        } else {
            $tags =  $request->tags;
            if($tags != null) {
                $tagsString = implode(", ", $tags);;
                $this->addLog(false, true, $inscrit->id, $request->current_user, "tag(s) '$tagsString' ajouté(s)");
            }

            return response()->json([
                'inscrit' => $inscrit,
                'message' => 'Personne existante !'
            ]);
        }
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $inscrit = new InscritResource(Inscrit::where('id', '=', $id)->with('tags')->with('recrutements')->with('formations')->get()->first());

        $listFormations = Formation::all();

        for($i=0; $i < count($inscrit->recrutements); $i++) {
            for($x=0; $x < count($listFormations); $x++) {
                if($inscrit->recrutements[$i]['formation_id'] === $listFormations[$x]['id']) {
                    $inscrit->recrutements[$i]['formation_nom'] = $listFormations[$x]['nom'];
                }
            }
        }

        return response()->json([
            'inscrit' => $inscrit,
            'tags' => $inscrit->tags,
            'recrutements' => $inscrit->recrutements,
            'formations' => $inscrit->formations,
        ]);
    }


    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(int $id): \Illuminate\Http\JsonResponse
    {
        $inscrit = new InscritResource(Inscrit::where('id', '=', $id)->with('tags')->with('recrutements')->with('formations')->get()->first());

        $listFormations = Formation::all();

        for($i=0; $i < count($inscrit->recrutements); $i++) {
            for($x=0; $x < count($listFormations); $x++) {
                if($inscrit->recrutements[$i]['formation_id'] === $listFormations[$x]['id']) {
                    $inscrit->recrutements[$i]['formation_nom'] = $listFormations[$x]['nom'];
                }
            }
        }

        return response()->json([
            'inscrit' => $inscrit,
            'tags' => $inscrit->tags,
            'recrutements' => $inscrit->recrutements,
            'formations' => $inscrit->formations,
        ]);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return InscritResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): InscritResource
    {
        $this->validate($request, [
            'nom' => 'required',
            'prenom' => 'required',
        ]);

        $inscrit = Inscrit::findOrFail($id);

        $modifications = '';
        $modif_compteur = 0;
        $modif_adresse = false;

        if($request->nom && $inscrit->nom != $request->nom) {
            $modifications .= 'nom';
            $modif_compteur++;
        }
        if($request->prenom && $inscrit->prenom != $request->prenom) {
            if($modif_compteur > 0){
                $modifications .= ', prénom';
            } else {
                $modifications .= 'prénom';
            }
            $modif_compteur++;
        }
        if($request->genre && $inscrit->genre != $request->genre) {
            if($modif_compteur > 0){
                $modifications .= ', genre';
            } else {
                $modifications .= 'genre';
            }
            $modif_compteur++;
        }
        if($request->date_naissance && $inscrit->date_naissance != $request->date_naissance) {
            if($modif_compteur > 0){
                $modifications .= ', date de naissance';
            } else {
                $modifications .= 'date de naissance';
            }
            $modif_compteur++;
        }
        if($request->rue  && $inscrit->rue != $request->rue) {
            $modif_adresse = true;
        }
        if($request->numero  && $inscrit->numero != $request->numero) {
            $modif_adresse = true;
        }
        if($request->boite  && $inscrit->boite != $request->boite) {
            $modif_adresse = true;
        }
        if($request->ville_id  && $inscrit->ville_id != $request->ville_id) {
            $modif_adresse = true;
        }
        if($modif_adresse === true) {
            if($modif_compteur > 0){
                $modifications .= ', adresse';
            } else {
                $modifications .= 'adresse';
            }
            $modif_compteur++;
        }
        if($request->email && $inscrit->email != $request->email) {
            if($modif_compteur > 0){
                $modifications .= ', email';
            } else {
                $modifications .= 'email';
            }
            $modif_compteur++;
        }
        if($request->tel && $inscrit->tel != $request->tel) {
            if($modif_compteur > 0){
                $modifications .= ', n° tél';
            } else {
                $modifications .= 'n° tél';
            }
            $modif_compteur++;
        }
        if($request->gsm && $inscrit->gsm != $request->gsm) {
            if($modif_compteur > 0){
                $modifications .= ', n° gsm';
            } else {
                $modifications .= 'n° gsm';
            }
            $modif_compteur++;
        }
        if($request->newsletter && $inscrit->newsletter != $request->newsletter) {
            if($modif_compteur > 0){
                $modifications .= ', newsletter';
            } else {
                $modifications .= 'newsletter';
            }
            $modif_compteur++;
        }
        if($request->commentaire_inscrit && $inscrit->commentaire_inscrit != $request->commentaire_inscrit) {
            if($modif_compteur > 0){
                $modifications .= ', commentaire';
            } else {
                $modifications .= 'commentaire';
            }
            $modif_compteur++;
        }

        $inscrit->nom = $request->nom;
        $inscrit->prenom = $request->prenom;
        $inscrit->genre = $request->genre;
        $inscrit->date_naissance = $request->date_naissance;
        $inscrit->rue = $request->rue;
        $inscrit->numero = $request->numero;
        $inscrit->boite = $request->boite;
        $inscrit->ville_id = $request->ville_id;
        $inscrit->email = $request->email;
        $inscrit->tel = $request->tel;
        $inscrit->gsm = $request->gsm;
        $inscrit->commentaire_inscrit = $request->commentaire_inscrit;
        $inscrit->prospect = $request->prospect;
        $inscrit->newsletter = $request->newsletter;
        $inscrit->save();

        // Ajout du log
        if($modif_compteur > 0) {
            if($modif_compteur === 1) {
                $this->addLog(false, true, $id, $request->current_user, "$modifications modifié");
            } else {
                $this->addLog(false, true, $id, $request->current_user, "$modifications modifiés");
            }
        }

        return new InscritResource($inscrit);
    }


    /**
     * @param $id
     * @return InscritResource
     */
    public function destroy($id): InscritResource
    {
        $inscrit = Inscrit::find($id);
        $inscrit->delete();

        return new InscritResource($inscrit);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function prospects(): \Illuminate\Http\JsonResponse
    {
        $prospects = Inscrit::with('tags')
            ->where('prospect', 1)
            ->select('id', 'prenom', 'nom')
            ->orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'prospects' => $prospects,
        ]);
    }


    /**
     * @param $search
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProspects($search): \Illuminate\Http\JsonResponse
    {
        $searchTags = explode(",", $search);
        $tags = [];
        for($i=0; $i < count($searchTags); $i++) {
            $tag = Tag::where('tag', $searchTags[$i])->select('id')->get()->first();
            array_push($tags, $tag);
        }

        $inscrits_tags = [];
        for($x=0; $x < count($tags); $x++) {
            $inscrit_tag = InscritTag::where('tag_id', $tags[$x]->id)->get();
            if(!$inscrit_tag->isEmpty()) {
                array_push($inscrits_tags, $inscrit_tag);
            }
        }

        if(!empty($inscrits_tags)) {
            $inscrits = [];
            for($y=0; $y < count($inscrits_tags); $y++) {
                if(count($inscrits_tags[$y]) > 0) {
                    for($z=0; $z < count($inscrits_tags[$y]); $z++) {
                        $inscrit = $inscrits_tags[$y][$z];
                        array_push($inscrits, $inscrit);
                    }
                } else {
                    $inscrit = $inscrits_tags[$y];
                }
                array_push($inscrits, $inscrit);
            }
            $inscrits_unique = array_unique($inscrits);

            $new_inscrits_unique = array_values($inscrits_unique);

            $list_inscrits = [];
            for($w=0; $w < count($new_inscrits_unique); $w++) {
                $list_inscrit = Inscrit::with('tags')
                    ->where(['prospect' => 1, 'id' => $new_inscrits_unique[$w]->inscrit_id])
                    ->select('id', 'prenom', 'nom', 'email', 'tel', 'gsm')
                    ->get()->first();
                if($list_inscrit != null) {
                    array_push($list_inscrits, $list_inscrit);
                }
            }

            $list_inscrits_unique = array_unique($list_inscrits);
            $new_list_inscrits_unique = array_values($list_inscrits_unique);
        } else {
            $new_list_inscrits_unique = null;
        }

        return response()->json([
            'prospects' => $new_list_inscrits_unique,
        ], 200);
    }

    /**
     * @param $id
     * @return InscritResource
     */
    public function modifStatut($id) {
        $inscrit = Inscrit::find($id);

        $inscrit->prospect = 1;
        $inscrit->save();

        return new InscritResource($inscrit);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSearch($id): \Illuminate\Http\JsonResponse
    {
        $inscrit = new InscritResource(Inscrit::findOrFail($id));

        return response()->json($inscrit);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function addTags(Request $request, $id): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $inscrit = Inscrit::find($id);

        $inscritFullname = $inscrit->prenom . ' ' . $inscrit->nom;

        $tags =  $request->tags;
        $tagsAll = Tag::all();
        $tagsID = [];

        for($i=0; $i<count($tags); $i++) {
            for($x=0; $x < count($tagsAll); $x++) {
                if($tags[$i] === $tagsAll[$x]['tag']) {
                    array_push($tagsID, $tagsAll[$x]['id']);
                }
            }
        }

        $inscrit->tags()->syncWithoutDetaching($tagsID);
        $inscrit->prospect = 1;
        $inscrit->save();

        // ajout de log d'ajout de tags
        $tagsString = implode(", ", $tags);;
        $this->addLog(false, true, $id, $request->current_user, "tag(s) '$tagsString' ajouté(s)");

        return response("Tag(s) rajouté(s) à $inscritFullname", 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function addTagsAfterRecrutement(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $inscritFullname = $request->prenom . ' ' . $request->nom;
        $response = '';

        $inscrit = Inscrit::find($request->inscrit_id);
        $tags = $request->tags;
        $tagsAll = Tag::all();
        $tagsID = [];

        for($i=0; $i<count($tags); $i++) {
            for($x=0; $x < count($tagsAll); $x++) {
                if($tags[$i] === $tagsAll[$x]['tag']) {
                    array_push($tagsID, $tagsAll[$x]['id']);
                }
            }
        }

        $tagsString = implode(', ', $tags);

        if($inscrit->tags() === null && $request->traitement === true) {
            $response = "Tag(s) rajouté(s) à $inscritFullname";
            $this->addLog(false, true, $request->inscrit_id, $request->currentUser, "tag(s) '$tagsString' ajouté(s) après un recrutement");
        } else if($inscrit->tags() !== null && $request->traitement === true) {
            $inscrit->tags()->sync($tagsID);
            $response = "Tag(s) de $inscritFullname modifié(s)";
            $this->addLog(false, true, $request->inscrit_id, $request->currentUser, "tag(s) modifié(s) après un recrutement");
        } else if($inscrit->tags() !== null && $request->traitement === false) {
            $response = "Aucune modification à $inscritFullname";
        } else if($request->traitement === false) {
            $response = "Aucun tag renseigné";
        }

        return response($response, 200);
    }

    /**
     * @param $idInscrit
     * @param $idTag
     * @param $idCurrentUser
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTags($idInscrit, $idTag, $idCurrentUser): \Illuminate\Http\JsonResponse
    {
        $match = ['inscrit_id' => $idInscrit, 'tag_id' => $idTag];
        $inscritTag = InscritTag::where($match)->with('tag')->first();

        // ajout de log de suppression du tag
        $tag = $inscritTag->tag->tag;
        $this->addLog(false, true, $idInscrit, $idCurrentUser, "tag '$tag' supprimé");

        $inscritTag->delete();

        return response()->json([
            'message' => "tag $tag supprimé",
        ], 200);
    }


    /**
     * @param Request $request
     * @param $idInscrit
     * @param $idFormation
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeRdv(Request $request, $idInscrit, $idFormation): \Illuminate\Http\JsonResponse
    {
            $match = ['formation_id' => $idFormation, 'inscrit_id' => $idInscrit];

            FormationInscrit::where($match)
                ->update([
                    'date_rdv' => $request->date_rdv,
                    'rdv_user_id' => $request->rdv_user_id,
                    'validation_rdv' => false
                ]);

            return response()->json([
                "message" => "Rendez-vous sauvegardé"
            ], 200);
    }

    /**
     * @param int $idInscrit
     * @param int $idFormation
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateRdv(int $idInscrit, int $idFormation): \Illuminate\Http\JsonResponse
    {
        $match = ['formation_id' => $idFormation, 'inscrit_id' => $idInscrit];

        FormationInscrit::where($match)
            ->update([
                'validation_rdv' => true,
            ]);

        return response()->json([
            "message" => "Rendez-vous validé",
        ], 200);
    }

    /**
     * @param int $idInscrit
     * @param int $idFormation
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRdv(int $idInscrit, int $idFormation): \Illuminate\Http\JsonResponse
    {

        $match = ['formation_id' => $idFormation, 'inscrit_id' => $idInscrit];

        FormationInscrit::where($match)
            ->update([
                'date_rdv' => null,
                'rdv_user_id' => null,
                'validation_rdv' => null,
            ]);

        return response()->json([
            "message" => "Rendez-vous supprimé",
        ], 200);
    }


    /**
     * @param Request $request
     * @param $idInscrit
     * @param $idFormation
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeRappel(Request $request, $idInscrit, $idFormation): \Illuminate\Http\JsonResponse
    {
        $date_rappel = $request->date_rappel;
        $rappel_user_id = $request->rappel_user_id;
        $match = ['formation_id' => $idFormation, 'inscrit_id' => $idInscrit];
        FormationInscrit::where($match)
            ->update([
                'date_rappel' => $date_rappel,
                'rappel_user_id' => $rappel_user_id,
                'rappel_resultat' => $request->rappel_resultat,
            ]);

        return response()->json([
            "Rappel effectué pour $idInscrit, le $date_rappel par $rappel_user_id",
        ], 200);
    }


    /**
     * @param $idInscrit
     * @param $idFormation
     * @param $idNewFormation
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function reportStagiaire($idInscrit, $idFormation, $idNewFormation): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $date_ajout = date('Y-m-d');
            $match = ['formation_id' => $idFormation, 'inscrit_id' => $idInscrit];
            FormationInscrit::where($match)
                ->update([
                    'formation_id' => $idNewFormation,
                    'date_ajout' => $date_ajout,
                    'date_rappel' => null,
                    'rappel_user_id' => null,
                    'rappel_resultat' => null,
                    'date_rdv' => null,
                    'rdv_user_id' => null,
                    'validation_rdv' => null,
                ]);

            return response("Stagiaire $idInscrit inscrit à la formation : $idNewFormation", 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @param $id
     * @param $idRecrutement
     * @param $idNewRecrutement
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function reportCandidat($id, $idRecrutement, $idNewRecrutement): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $date_ajout = date('Y-m-d');
            $match = ['recrutement_id' => $idRecrutement, 'inscrit_id' => $id];
            RecrutementInscrit::where($match)
                ->update([
                    'recrutement_id' => $idNewRecrutement,
                    'date_ajout' => $date_ajout,
                ]);

            return response("Candidat $id inscrit au recrutement : $idRecrutement", 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @param $idInscrit
     * @param $traitement
     * @param $prospect
     * @return \Illuminate\Http\JsonResponse
     */
    public function candidatToProspect($idInscrit, $traitement, $prospect): \Illuminate\Http\JsonResponse
    {
        $inscrit = Inscrit::where('id', $idInscrit)->get()->first();

        $prenom = $inscrit->prenom;
        $nom = $inscrit->nom;

        if(($inscrit->prospect == 1 && $traitement == 'false' && $prospect == 1) || ($inscrit->prospect == 1 && $traitement == 'false' && $prospect == 0)  || ($inscrit->prospect == 1 && $traitement == 'true' && $prospect == 1)) {
            $resultat = "reste prospect";
        } else if($inscrit->prospect == 0 && $traitement == 'true' && $prospect == 1) {
            $inscrit->prospect = 1;
            $resultat = "devient prospect";
            $inscrit->save();
        } else if($inscrit->prospect == 0 && $traitement == 'false' && $prospect == 0) {
            $inscrit->prospect = 0;
            $resultat = "ne devient pas prospect";
            $inscrit->save();
        } else if($inscrit->prospect == 1 && $traitement == 'true' && $prospect == 0) {
            $inscrit->prospect = 0;
            $resultat = "n'est plus prospect";
            $inscrit->save();
        }

        return response()->json([
            'message' => "$prenom $nom $resultat",
            'inscrit' => $inscrit
        ], 200);
    }

    /**
     * @param $creation
     * @param $update
     * @param $idInscrit
     * @param $idCurrentUser
     * @param $informations
     */
    public function addLog($creation, $update, $idInscrit, $idCurrentUser, $informations)
    {
        $date_ajout = date('Y-m-d');
        $modification = new Log();
        $modification->creation = $creation;
        $modification->update = $update;
        $modification->informations = $informations;
        $modification->date = $date_ajout;
        $modification->inscrit_id = $idInscrit;
        $modification->user_id = $idCurrentUser;
        $modification->save();
    }
}
