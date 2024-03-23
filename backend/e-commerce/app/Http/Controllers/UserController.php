<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    public function get_all(Request $request)
    {
        $articles = Article::select('nom', 'prix', 'short_description','img_path','token','options')->get();
        return $articles;
    }

    public function get_one(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'article_token' => 'required|string|max:255',
            
        ]);

        if ($validator->fails()) {
            return "invalide request data"; //Si pas de token on arrete la
        }
        $validatedData=$validator->validated();

        //On essaie de récupèrer l'id qui correspond au token
        $article = Article::where('token', $validatedData["article_token"])->first();

        if ($article) {
            // Si le token existe, récupérer l'ID correspondant
            unset($article->token);
            
            return $article;

        } else {
            // Si le token fourni n'existe pas, on renvoit une erreur 404
            return response()->json(['message' => 'aucun article correspondant'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
