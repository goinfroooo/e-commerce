<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Article;
use App\Models\Panier;
use App\Models\Stock;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PanierController extends Controller
{

    public function add(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'user_token' => 'required|string|max:255',
                'article_token' => 'required|string|max:255',
                'qte'=> 'required|integer',
                
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
            }
        
            // Créer un nouvel utilisateur
            $validatedData=$validator->validated();
        
            $user = User::where("user_token",$validatedData['user_token'])->first();
            $article = Article::where("token",$validatedData['article_token'])->first();

            if (!$user) {
                return response()->json(['error' => "token utilisateur invalide"], 404);
            }
            else if (!$article) {
                return response()->json(['error' => "article introuvable"], 404);
            }
            else {
                $stock = Stock::where("article_id",$article->id)->first();
                $qte_in_cart=0;
                $panier = Panier::where("article_id",$article->id)->get();
                foreach ($panier as $item) {
                    $qte_in_cart+= $item->qte;
                
                    // Faites ce que vous avez besoin de faire avec chaque élément ici
                }

                if ($validatedData["qte"]>$stock->qte-$qte_in_cart){
                    return response()->json(['error' => "stock insuffisant"], 404);
                }
                else {

                    $cart = Panier::where('user_id',$user->id)
                    ->where('article_id',$article->id)
                    ->first();

                    if ($cart === null) {
                        $cart=new Panier(); 
                        $cart->user_id = $user->id;
                        $cart->article_id = $article->id;
                        $cart->qte=$validatedData["qte"];
                        $cart->save();
                        return response()->json(['message' => "sucess",'status' => "created"], 200);

                    } else {
                        $cart->qte=$cart->qte+$validatedData["qte"];
                        $cart->save();
                        return response()->json(['message' => "sucess",'status' => "updated"], 200);
                    }
                    
                }
            }

        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            return response()->json(['error' => $e->getMessage()], 404);;
            throw $e;
        }
    }

}
