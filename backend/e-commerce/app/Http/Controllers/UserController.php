<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Mail\inscription;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

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
            $token_email = Uuid::uuid4()->toString();
            $appUrl = env('APP_URL');
            $url = $appUrl .':8000'.'/user/activate/';

    
            $activationLink = $url .urlencode($validatedData["email"])."/". $token_email; 

            // Envoi de l'e-mail d'activation
            Mail::to($validatedData["email"])->send(new inscription($activationLink));
    
        
            $user = new User();
            $user->name = $validatedData['name'];
            $user->birthday = Carbon::createFromFormat('Y-m-d', $validatedData['birthday'])->toDateString();
            $user->email = $validatedData['email'];
            $user->mail_token = $token_email;
            $user->adress = $validatedData["adress"];
            $user->phone = $validatedData["phone"];
            $user->password = bcrypt($validatedData['password']); // Hasher le mot de passe
            $user->save();
    
            // Retourner une réponse appropriée
            return response()->json(['message' =>"sucess"], 200);
            
            
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            return response()->json(['error' => $e->getMessage()], 404);
            throw $e;
        }
    }

    public function get_connected(Request $request)
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
    public function validate($email,$token)
    {
        
        try {
            $user= User::where("email",$email)->where("mail_token",$token)->first();
            if($user) {
                if ($user->email_verified_at != NULL ) {
                    return response()->json(['message' => "acount already verified"], 200);
                }
                else {
                    $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
                    $user->save();
                    return response()->json(['message' => "acount activated"], 200);
                }
            }
            else {
                return response()->json(['message' => "no user account corresponding"], 200);
            }
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            return response()->json(['error' => $e->getMessage()], 404);
            throw $e;
        }
    }

}
