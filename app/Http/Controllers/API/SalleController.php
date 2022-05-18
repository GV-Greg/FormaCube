<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalleCollection;
use App\Http\Resources\SalleResource;
use App\Model\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $salles = Salle::orderBy('id', 'ASC')->paginate(10);

        return SalleResource::collection($salles);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $salles = Salle::all();

        return response()->json([
            'salles' => $salles,
        ]);
    }

    /**
     * @param $field
     * @param $search
     * @return SalleCollection
     */
    public function search($field, $search)
    {
        return new SalleCollection(Salle::where($field,'LIKE',"%$search%")->latest()->paginate(10));
    }

    /**
     * @param Request $request
     * @return SalleResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nom' => 'required|unique:salles',
        ]);

        $salle = new Salle();

        $salle->nom = $request->nom;
        $salle->save();

        return new SalleResource($salle);
    }

    /**
     * @param Request $request
     * @param $id
     * @return SalleResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $salle = Salle::findOrFail($id);

        $this->validate($request, [
            'nom' => 'required|string|min:2|max:191|unique:salles,nom,'.$salle->id,
        ]);

        $salle->nom = $request->nom;
        $salle->save();

        return new SalleResource($salle);
    }

    /**
     * @param $id
     * @return SalleResource
     */
    public function destroy($id)
    {
        $salle = Salle::find($id);
        $salle->delete();

        return new SalleResource($salle);
    }
}
