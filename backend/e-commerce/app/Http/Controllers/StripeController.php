<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

use App\Models\Article;
use App\Models\User;
use App\Models\Panier;


class StripeController extends Controller
{
    public function pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'USER-TOKEN' => 'required|string|max:255',
            
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
        }
    
        // Créer un nouvel utilisateur
        $validatedData=$validator->validated();
        Stripe::setApiKey(env('SECRET_STRIPE_KEY'));

        // URL de votre domaine
        $YOUR_DOMAIN = env('APP_URL');

        // Créer une session de paiement
        $checkout_session = Session::create([
            
            'line_items' => [[
                'price' => 'price_1P6TLeHXPy8dk2GGrYPVFjnO',
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success',
            'cancel_url' => $YOUR_DOMAIN . '/cancel',
        ]);

        
        // Rediriger vers l'URL de la session de paiement
        //return redirect()->away($checkout_session->url);
        return response()->json(['message' => "success","url"=>$checkout_session->url], 200);
    }

    public function import_all_products()
        {
            try {
                // Récupérer tous les articles de votre base de données
                $articles = Article::select("nom","prix","short_description","img_path")->get();

                // Créer un client Stripe
                $stripe = new \Stripe\StripeClient(env('SECRET_STRIPE_KEY'));

                // Boucler à travers chaque article pour créer un produit Stripe et son prix
                foreach($articles as $article) {
                    // Créer le produit Stripe
                    $product = $stripe->products->create([
                        'name' => $article["nom"],
                        'description' => $article["short_description"],
                        //'images' => [$article["img_path"]],
                        // Vous pouvez également inclure d'autres paramètres comme 'type', 'attributes', etc.
                    ]);

                    // Créer le prix associé avec le produit créé
                    $price = $stripe->prices->create([
                        'unit_amount' => $article["prix"], // Le prix doit être en centimes
                        'currency' => 'eur', // Définir la devise
                        'product' => $product->id, // Spécifier l'ID du produit associé
                    ]);
                }

                return response()->json(['message' =>"Success"], 200);

            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 404);
            }
        }

        public function success()
        {
            try {
                return view("success");

            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 404);
            }
        }

        public function cancel()
        {
            try {
                return view("success");

            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 404);
            }
        }

    
    
}
