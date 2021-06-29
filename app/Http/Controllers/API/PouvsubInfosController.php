<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PouvsubInfosResource;
use App\Model\Formation;
use App\Model\PouvsubInfos;
use Illuminate\Http\Request;

class PouvsubInfosController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return PouvsubInfosResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'formation_id' => 'required',
            'pouvsub_id' => 'required',
            'adresse' => 'required',
            'date_naissance' => 'required',
            'age' => 'required',
            'email' => 'required',
            'num_national' => 'required',
            'statut_legal' => 'required',
            'diplome' => 'required',
            'duree_chomage' => 'required',
            'source_info' => 'required',
            'groupe_social' => 'required',
        ]);

        $pouvsubInfos = new PouvsubInfos();

        $pouvsubInfos->formation_id = $request->formation_id;
        $pouvsubInfos->pouvsub_id = $request->pouvsub_id;
        $pouvsubInfos->adresse = $request->adresse;
        $pouvsubInfos->date_naissance = $request->date_naissance;
        $pouvsubInfos->age = $request->age;
        $pouvsubInfos->email = $request->email;
        $pouvsubInfos->num_national = $request->num_national;
        $pouvsubInfos->statut_legal = $request->statut_legal;
        $pouvsubInfos->diplome = $request->diplome;
        $pouvsubInfos->duree_chomage = $request->duree_chomage;
        $pouvsubInfos->source_info = $request->source_info;
        $pouvsubInfos->groupe_social = $request->groupe_social;
        $pouvsubInfos->save();

        return new PouvsubInfosResource($pouvsubInfos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return PouvsubInfosResource
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'formation_id' => 'required',
            'pouvsub_id' => 'required',
            'adresse' => 'required',
            'date_naissance' => 'required',
            'age' => 'required',
            'email' => 'required',
            'num_national' => 'required',
            'statut_legal' => 'required',
            'diplome' => 'required',
            'duree_chomage' => 'required',
            'source_info' => 'required',
            'groupe_social' => 'required',
        ]);

        $pouvsubInfos = PouvsubInfos::findOrFail($id);

        $pouvsubInfos->formation_id = $request->formation_id;
        $pouvsubInfos->pouvsub_id = $request->pouvsub_id;
        $pouvsubInfos->adresse = $request->adresse;
        $pouvsubInfos->date_naissance = $request->date_naissance;
        $pouvsubInfos->age = $request->age;
        $pouvsubInfos->email = $request->email;
        $pouvsubInfos->num_national = $request->num_national;
        $pouvsubInfos->statut_legal = $request->statut_legal;
        $pouvsubInfos->diplome = $request->diplome;
        $pouvsubInfos->duree_chomage = $request->duree_chomage;
        $pouvsubInfos->source_info = $request->source_info;
        $pouvsubInfos->groupe_social = $request->groupe_social;
        $pouvsubInfos->save();

        return new PouvsubInfosResource($pouvsubInfos);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getInfos($id)
    {
        $infos = PouvsubInfos::where('formation_id', $id)->get()->first();

        return new PouvsubInfosResource($infos);
    }
}
