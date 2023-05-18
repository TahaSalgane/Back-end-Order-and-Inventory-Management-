<?php

namespace App\Http\Controllers;

use App\Models\article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function getArticles(){
        try {
            return response()->json([
                'articles' => article::all()
                ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function addArticle(Request $request){
        try {
            $authUser = Auth::user() ;
            if($authUser->role == 'magasinier'){

                $article = article::create([
                    'designationArticle' => $request->designationArticle
                ]) ;
                return response()->json([
                    "articles" =>$article
                ]);
            }
            return response()->json([
                'error' => 'Only magasinier can add articles'
            ],400) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function editActicle(Request $request){
        try {
            $authUser = Auth::user() ;
            if($authUser->role == 'magasinier'){
                $article = article::find($request->id)->update([
                    'designationArticle' => $request->designationArticle
                ]);
                return response()->json([
                    'articlesUpdated' => $article,
                ]) ;
            }
            return response()->json([
                'error' => 'Only magasinier can do this action'
            ],400) ;

        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function deleteArticle($id)
    {
        try {
            $authUser = Auth::user();
            if ($authUser->role == 'magasinier') {
                $article = Article::find($id);
                if ($article) {
                    $article->delete();
                    $articles = Article::all();
                    return response()->json([
                        'articles' => $articles,
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Article not found',
                    ], 404);
                }
            }
            return response()->json([
                'error' => 'Only magasinier can delete articles',
            ], 403);
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage(),
            ]);
        }
    }
    
}
