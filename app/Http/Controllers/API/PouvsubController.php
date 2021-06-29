<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PouvsubCollection;
use App\Http\Resources\PouvsubResource;
use App\Model\Pouvsub;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class PouvsubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return PouvsubCollection
     */
    public function index()
    {
        return new PouvsubCollection(Pouvsub::orderBy('id', 'DESC')->paginate(10));
    }

    /**
     * @param $colonne
     * @param $search
     * @return PouvsubCollection
     */
    public function search($colonne, $search)
    {
        return new PouvsubCollection(Pouvsub::where($colonne,'LIKE',"%$search%")->latest()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return PouvsubResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nom' => 'required|string|min:2|max:191|unique:pouvsubs',
        ]);

        $pouvsub = new Pouvsub();

        $pouvsub->nom = $request->nom;
        $pouvsub->adresse = $request->adresse;
        $pouvsub->date_naissance = $request->date_naissance;
        $pouvsub->age = $request->age;
        $pouvsub->email = $request->email;
        $pouvsub->num_national = $request->num_national;
        $pouvsub->statut_legal = $request->statut_legal;
        $pouvsub->diplome = $request->diplome;
        $pouvsub->duree_chomage = $request->duree_chomage;
        $pouvsub->source_info = $request->source_info;
        $pouvsub->groupe_social = $request->groupe_social;
        $pouvsub->save();

        if($request->logo != '') {
            // Récupère le nom de l'image et se débarasse de toutes les autres infos.
            $imagename = time().'.' . explode('/', explode(':', substr($request->logo, 0, strpos($request->logo, ';')))[1])[1];
            // Enregistre le fichier dans le dossier 'logos'
            Image::make($request->logo)->save(public_path('images/logos/').$imagename);
            // Enregistre le nom du fichier dans la BDD
            $pouvsub->logo = $imagename;
            $pouvsub->save();
        }

        return new PouvsubResource($pouvsub);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return PouvsubResource
     */
    public function show($id)
    {
        return new PouvsubResource(Pouvsub::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return PouvsubResource
     */
    public function update(Request $request, $id)
    {
        $pouvsub = Pouvsub::findOrFail($id);

        $this->validate($request, [
            'nom' => 'required|string|min:2|max:191|unique:pouvsubs,nom,'.$pouvsub->id,
        ]);

        $pouvsub->nom = $request->nom;
        $pouvsub->adresse = $request->adresse;
        $pouvsub->date_naissance = $request->date_naissance;
        $pouvsub->age = $request->age;
        $pouvsub->email = $request->email;
        $pouvsub->num_national = $request->num_national;
        $pouvsub->statut_legal = $request->statut_legal;
        $pouvsub->diplome = $request->diplome;
        $pouvsub->duree_chomage = $request->duree_chomage;
        $pouvsub->source_info = $request->source_info;
        $pouvsub->groupe_social = $request->groupe_social;
        $pouvsub->save();

        if(!empty($request->logo)) {
            $currentLogo = $pouvsub->logo;
            if($request->logo != $currentLogo){
                // Récupère le nom de l'image et se débarasse de toutes les autres infos.
                $imagename = time().'.' . explode('/', explode(':', substr($request->logo, 0, strpos($request->logo, ';')))[1])[1];
                // Enregistre le fichier dans le dossier 'logos'
                Image::make($request->logo)->save(public_path('images/logos/').$imagename);
                // Push dans la BDD
                $pouvsub->logo = $imagename;
                $pouvsub->save();
                // Suppresion de l'ancien logo si un nouveau est uploadé
                $poursubLogo = public_path('images/logos/').$currentLogo;
                if(file_exists($poursubLogo)){
                    @unlink($poursubLogo);
                }
            }
        }

        return new PouvsubResource($pouvsub);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return PouvsubResource
     */
    public function destroy($id)
    {
        $pouvsub = Pouvsub::find($id);
        $poursubLogo = $pouvsub->logo;

        $pouvsubLink = public_path('images/logos/').$poursubLogo;
        if(file_exists($pouvsubLink)){
            @unlink($pouvsubLink);
        }
        $pouvsub->delete();

        return new PouvsubResource($pouvsub);
    }
}
