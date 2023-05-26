<?php

namespace App\Http\Controllers;

use App\Models\article;
use App\Models\articlesClass;
use App\Models\classe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ClasseController extends Controller
{
     public function getClasses($etablissment){
        // return response()->json($etablissment) ;
        try {
            // return response()->json([
            //     $etablissment
            // ]) ;
            $authUser = Auth::user() ;
            if ($authUser->role != 'directeur etablissement') {
                $classes = classe::where('etablissement', $etablissment)->get() ;
                return response()->json([
                     'classes'=> $classes
                    ]) ;
            }
            if($authUser->etablissement != $etablissment){
                return response()->json([
                    'back' => true ,
                    'error' => 'You can not access to this classe !'
                ]
                ) ;
            }
            $classes = classe::where('etablissement', $authUser->etablissement)->get() ;
            return response()->json([
                 'classes'=> $classes?$classes:[]
                ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function addClasse(Request $request){
        try {
            // return response()->json($request->all()) ;
            $classeId = classe::insertGetId([
                'nomSalle' => $request->nomSalle ,
                'etablissement' => $request->etablissement ,
                'type' => $request->type
            ]) ;
            $classe = classe::find($classeId) ;
            return response()->json([
                'message' =>'classe added succesfully !' ,
                'classe'  => $classe

            ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function editClasse(Request $request){
        try {
            // return response()->json($request->all() ) ;
            $classe = classe::find($request->id)->update([
                'nomSalle' => $request->nomSalle ,
                'type' => $request->type
            ]) ;
            return response()->json([
                'message' => 'classe updated succesfully !' ,
                $classe
            ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function deleteClasse($id){
        try {
            classe::find($id)->delete() ;
            return response()->json([
                'message' => 'classe deleted succesfully !'
            ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }
    public function articlesClasse($id,$etab){

        $existedClasse = classe::find($id) ;
        $articlesClasse = articlesClass::where('classe_id',$id)->get()->pluck('articles') ;
        $avaliableArticles = article::all() ;

        if(!$existedClasse){
            return response()->json([
                'error' => 'No classe much !' ,
                'back' => true
            ]) ;
        }
        if(Auth::user()->role == 'directeur etablissement'){
            if(Auth::user()->etablissement != $etab){
                return response()->json([
                    'back' => true ,
                    'error' => 'You can not access to these articles !'
                ]) ;
            }
        }
        if(count($articlesClasse)>0){
            return response()->json([
                'articles' => json_decode($articlesClasse),
                'avaliableArticles' => $avaliableArticles
            ]) ;
        }
        return response()->json([
            'message' => 'no articles in this classe' ,
            'avaliableArticles' => $avaliableArticles ,
            'articles' => []
        ]) ;

    }

    public function addArticlesOnClasse(Request $request){
        try {
            $avaliableArticles = article::all() ;
            $classe = classe::find($request->classe_id) ;

            $articlesClass = articlesClass::where('classe_id',$request->classe_id)->first() ;

            if($articlesClass){
                $articlesList = $articlesClass->articles ;
            // check if there is articles inside the classe that much our requested article
                // case 1 : true

                if (!empty(json_decode(articlesClass::where('classe_id',$request->classe_id)
                    ->whereJsonContains('articles',['id'=> $request->article['id']])
                    ->get('articles')))) {
                    // Increment the quentity of the article
                    $articles = collect($articlesList)->map(function ($article) use ($request) {
                        if ($article['id'] == $request->article['id']) {
                            $article['qte']+=$request->article['qte'];
                        }
                        return $article;
                    });
                    $articlesClass->update([
                        'articles' => $articles
                    ]) ;
                    return response()->json([
                        'quatity incremented with '.$request->article['qte'].' successfully  !'
                    ]) ;

                }
                // case 2 : false

            // check if there is other articles
                // case 1 : true
                if(!empty($articlesClass->articles)){
                    $articlesList[] = $request->article ;
                    $articlesClass->update([
                        'articles' => $articlesList
                        ]) ;
                    return response()->json([
                        'message'=> 'Article added with success !' ,
                        'article' => $request->article ,
                        'avaliableArticles' => $avaliableArticles
                    ]) ;
                }

                // case 2 : false => create new article inside the classe_articles class
                $articlesList[] = $request->article ;
                $articlesClass->update([
                    'articles' => $articlesList ,


                    ]) ;
                return response()->json([
                    'message'=>'Article added with success into emty classe articles !' ,
                    'avaliableArticles' => $avaliableArticles ,
                    'article' => $request->article
                ]) ;
            }
            // if the there is no articles_classe class => ceate new articles_class and put the article iside
            else{
                $articleToadd[] = $request->article ;
                $related = $classe->articles()->create([
                    'articles' => $articleToadd
                ]) ;
                return response()->json([
                    'message' => 'new classeArticles has been added with success' ,
                    'article' => $request->article ,
                    'avaliableArticles' => $avaliableArticles
                ]) ;
            }

        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }
    public function editArticlesOnClasse(Request $request){
        try {
            $articlesClass = articlesClass::where('classe_id',$request->classe_id)->first() ;
            $articlesList = $articlesClass->articles ;

            $articles = collect($articlesList)->map(function ($article) use ($request) {
                if ($article['id'] == $request->article['id']) {
                    $article['qte'] = $request->article['qte'];
                }
                return $article;
            });
            // comment
            $articlesClass->update([
                'articles' => $articles
            ]) ;

            return response()->json([
                "article qte updated successfully from editArticleClasse !"
            ]) ;
        } catch (Exception $exp) {
            return response()->json([
                $exp->getMessage()
            ]) ;
        }

    }
    public function deleteArticlesOnClasse($classeId,$articleId){
        try {
            $articleClass = articlesClass::where('classe_id', $classeId)->first() ;
            $Carticles = ($articleClass->articles);
            $matchingArticle = collect($Carticles)->firstWhere('id', $articleId);

            // return response()->json(
            //     [
            //         'match'=>$matchingArticle ,
            //         'classeId' => $classeId ,
            //         'articleId' => $articleId
            //     ]
            // ) ;

            if ($matchingArticle) {
                $articles = collect($articleClass->articles)
                    ->reject(function ($article) use ($articleId) {
                        return $article['id'] == $articleId;
                    });
                if(count($articles)>1){
                    $articleClass->update(['articles' => json_decode($articles)]);
                }else{
                    $articleClass->update(['articles' => [] ]);
                }
                return response()->json([
                    'message'=>'article deleted successfuly',
                    ]) ;
            }else{
                return response()->json([
                    'error' => 'articles doesn\' exist '
                ]) ;
            }
        } catch (Exception $exp) {
            return response()->json([
                $exp->getMessage()
            ]) ;
        }
    }
}
