<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Model\Villes;
use Illuminate\Http\Request;

class VilleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $villes = Villes::all();

        return response()->json([
            'villes' => $villes
        ], 200);
    }
}
