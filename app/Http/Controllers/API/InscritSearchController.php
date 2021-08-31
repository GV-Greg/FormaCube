<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Model\Inscrit;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

class InscritSearchController extends Controller
{
    public function index(Request $request)
    {
        $results = (new Search())
            ->registerModel(Inscrit::class, 'nom', 'prenom')
            ->search($request->input('query'));

        return response()->json($results);
    }
}
