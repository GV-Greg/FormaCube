<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\InscritInfosResource;
use App\Model\PouvsubInfos;
use Illuminate\Http\Request;

class InscritInfosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @return InscritInfosResource
     */
    public function latest()
    {
        return new InscritInfosResource(PouvsubInfos::orderBy('id', 'DESC')->first());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return InscritInfosResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'pouvsub_info_id' => 'required',
            'inscrit_id' => 'required',
        ]);

        $inscritInfos = new PouvsubInfos();

        $inscritInfos->pouvsub_info_id = $request->pouvsub_info_id;
        $inscritInfos->inscrit_id = $request->inscrit_id;
        $inscritInfos->age = $request->age;
        $inscritInfos->num_national = $request->num_national;
        $inscritInfos->statut_legal = $request->statut_legal;
        $inscritInfos->diplome = $request->diplome;
        $inscritInfos->duree_chomage = $request->duree_chomage;
        $inscritInfos->source_info = $request->source_info;
        $inscritInfos->groupe_social = $request->groupe_social;
        $inscritInfos->save();

        return new InscritInfosResource($inscritInfos);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return InscritInfosResource
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'pouvsub_info_id' => 'required',
            'inscrit_id' => 'required',
        ]);

        $inscritInfos = PouvsubInfos::findOrFail($id);

        $inscritInfos->pouvsub_info_id = $request->pouvsub_info_id;
        $inscritInfos->inscrit_id = $request->inscrit_id;
        $inscritInfos->age = $request->age;
        $inscritInfos->num_national = $request->num_national;
        $inscritInfos->statut_legal = $request->statut_legal;
        $inscritInfos->diplome = $request->diplome;
        $inscritInfos->duree_chomage = $request->duree_chomage;
        $inscritInfos->source_info = $request->source_info;
        $inscritInfos->groupe_social = $request->groupe_social;
        $inscritInfos->save();

        return new InscritInfosResource($inscritInfos);
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
}
