<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function create(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'birthday' => 'required|date_format:Y-m-d',
                'email' => 'required|email|unique:users|max:255',
                'adress' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'password' => 'required|string|min:6',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
            }
        
            // Créer un nouvel utilisateur
            $validatedData=$validator->validated();
        
            $user = new User();
            $user->name = $validatedData['name'];
            $user->birthday = Carbon::createFromFormat('Y-m-d', $validatedData['birthday'])->toDateString();
            $user->email = $validatedData['email'];
            $user->adress = $validatedData["adress"];
            $user->phone = $validatedData["phone"];
            $user->password = bcrypt($validatedData['password']); // Hasher le mot de passe
            $user->save();
    
            // Retourner une réponse appropriée
            return "inscription effectuée";
            
            
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            return $e;
            throw $e;
        }
    }

    public function get_profil(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                
                'connexion_email' => 'required|email|max:255',
                'connexion_password' => 'required|string|min:6',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
            }
            // Créer un nouvel utilisateur
            $validatedData=$validator->validated();

            $user = User::where('email', $validatedData["connexion_email"])->first();
    
            if (!$user) {
                return response()->json(['message' => 'Email non trouvé'], 404);
            }
        
            // Vérifier si le mot de passe correspond
            if (!Hash::check($validatedData["connexion_password"], $user->password)) {
                return response()->json(['message' => 'Mot de passe incorrect'], 401);
            }
        
            // Si l'email et le mot de passe correspondent, retourner le token
            $user = Arr::except($user->toArray(), ['password']);
            return response()->json($user, 200);
            
            
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            return response()->json(['error' => $e->getMessage()], 404);
            throw $e;
        }
    }

    public function update_profil(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'user_token'=>'required|string|max:255',
                'name' => 'required|string|max:255',
                'birthday' => 'required|date_format:Y-m-d',
                'adress' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
            }
        
            // Créer un nouvel utilisateur
            $validatedData=$validator->validated();
        
            $user = User::where("user_token",$validatedData["user_token"])->first();
            $user->name = $validatedData['name'];
            $user->birthday = Carbon::createFromFormat('Y-m-d', $validatedData['birthday'])->toDateString();
            $user->adress = $validatedData["adress"];
            $user->phone = $validatedData["phone"];
            $user->save();
    
            // Retourner une réponse appropriée
            return response()->json(['message' => "sucess"], 200);
            
            
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            return response()->json(['message' => $e->getMessage()], 200);
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,)
    {
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
