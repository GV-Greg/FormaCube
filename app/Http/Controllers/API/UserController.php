<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProfile(Request $request, int $id)
    {
        $this->validate($request, [
            'firstname' => 'required|string|min:2|max:100',
            'lastname' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,'.$id,
            'password' => 'sometimes|required|min:8',
            'fonction' => 'max:100',
        ]);

        $user=User::findOrFail($id);

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        if(!empty($request->password)){
            $user->password = bcrypt($request['password']);
        }
        $user->fonction = $request->fonction;

        $user->save();

        return response()->json([
            'user' => $user,
            'message' => 'Profil édité'
        ]);
    }
}
