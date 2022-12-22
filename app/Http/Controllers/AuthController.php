<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $userdata = $request ->validate([
            "name" => ["required", "string", "min:4", "max:255"],
            "email"=> ["required", "email", "unique:users,email"],
            "first_name" => ["required", "string", "min:4", "max:255"],
            "city" => ["required", "string", "min:2", "max:255"],
            "country" => ["required", "string", "min:4", "max:255"],
            "username" => ["required", "string", "min:4", "max:255"],
            "password" => ["required", "string", "min:4", "max:20", "confirmed"]
           ]);
           $user = User::create([
            "name" =>$userdata["name"],
            "email" =>$userdata["email"],
            "first_name" =>$userdata["first_name"],
            "city" =>$userdata["city"],
            "country" =>$userdata["country"],
            "username" =>$userdata["username"],
            "password" =>bcrypt($userdata["password"])
            
           ]);

           $token = $user->createToken('myAppToken')->plainTextToken;

           $response = [
            'user' =>$user, 
            'token' =>$token,
            'message'=>'Utilisateur enregistré avec succès.'
           ];
           return response($response, 201);
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
        return User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = User::find($id); 
        $user->update($request->all());
        return [
            'message'=>'Utilisateur modifié avec succès'
        ];
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
        User::destroy($id);
        return [
            'message'=>'Utilisateur supprimé avec succès'
        ];
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message'=>'Déconnexion'
        ];
    }

    public function login(Request $request){
        // faire un choix entre l'email et le username 
        $userdata = $request ->validate([
            "username"=> ["required", "string"],
            "password" => ["required", "string"]
           ]);

           //Verification de l'email 
           $user = User::where('username',$userdata['username'])->first();

           //Vérification du mot de passe 
           if(!$user || !Hash::check($userdata['password'], $user->password)){
            return response([
                'message'=> 'Bad credentials'
            ],401);
           }

           $token = $user->createToken('myAppToken')->plainTextToken;

           $response = [
            'user' =>$user, 
            'token' =>$token,
            'message'=>'Utilisateur connecté avec succès.'
           ];
           return response($response, 201);
    }
}   

