<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UsersController extends Controller
{
    public function all()
    {
        $users = User::orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'users' => $users
        ], 200);
    }

    /**
     * @param $field
     * @param $search
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($field, $search)
    {
        if($field==='nom') {
            $field = 'lastname';
        } else if($field==='prenom') {
            $field = 'firstname';
        }

        $users = User::where($field,'LIKE',"%$search%")->orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'users' => $users
        ], 200);
    }

    public function tuteurs()
    {
        $users = User::where('role', 'formateur')->get();

        return response()->json([
            'users' => $users
        ], 200);
    }

    public function admins()
    {
        $users = User::where('role', 'admin')->get();

        return response()->json([
            'users' => $users
        ], 200);
    }

    public function show(int $id)
    {
        $user = User::whereId($id)->first();

        return response()->json([
            'user' => $user
        ], 200);
    }

    public function new(CreateUserRequest $request)
    {
        if($request['avatar'] === 'Homme') {
            $avatar = 'man.png';
        } else if ($request['avatar'] === 'Femme') {
            $avatar = 'woman.png';
        } else {
            $avatar = 'user.png';
        }

        $user = User::create([
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'fonction' => $request['fonction'],
            'role' => $request['role'],
            'avatar' => $avatar,
        ]);

        return response()->json([
            'user' => $user
        ], 200);
    }

    public function edit(int $id) {
        $user = User::findOrFail($id);

        return response()->json([
            'user' => $user
        ], 200);
    }

    public function update(Request $request, int $id) {

        $this->validate($request, [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$id,
            'password' => 'sometimes|required|min:8',
            'role' => 'required',
        ]);

        $user= User::findOrFail($id);

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        if(!empty($request->password)){
            $user->password = bcrypt($request['password']);
        }
        $user->fonction = $request->fonction;
        $user->role = $request->role;

        if($request['avatar'] === 'Homme') {
            $user->avatar = 'man.png';
        } else if ($request['avatar'] === 'Femme') {
            $user->avatar = 'woman.png';
        } else {
            $user->avatar = 'user.png';
        }

        $user->save();

        return response()->json([
            'user' => $user
        ], 200);
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'message' => 'Utilisateur archivé!'
        ]);
    }

    public function archives()
    {
        $archives = User::onlyTrashed()->orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'archives' => $archives
        ], 200);
    }

    public function searchArchives($field, $search)
    {
        if($field==='nom') {
            $field = 'lastname';
        } else if($field==='prenom') {
            $field = 'firstname';
        }

        $archives = User::onlyTrashed()->where($field,'LIKE',"%$search%")->orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'archives' => $archives
        ], 200);
    }

    public function restore(int $id)
    {
        $user = User::withTrashed()->where('id', $id)->restore();

        return response()->json([
            'message' => 'Utilisateur restauré!'
        ]);
    }
}
