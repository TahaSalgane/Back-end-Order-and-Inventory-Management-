<?php

namespace App\Http\Controllers;

use App\Models\article;
use App\Models\articlesClass;
use App\Models\classe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ClasseController extends Controller
{
     public function getClasses(){
        try {
            $classes = classe::get();
            return response()->json([
                 $classes,

                ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function addClasse(Request $request){
        try {
            classe::create([
                'nomSalle' => $request->nomSalle ,
                'type' => $request->type
            ]) ;
            return response()->json([
                'classe added succesfully !' ,

            ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function editClasse(Request $request){
        try {

            $classe = classe::find($request->id)->update([
                $request->all()
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

    public function deleteClasse(Request $request){
        try {
            classe::find($request->id)->delete() ;
            return response()->json([
                'classe deleted succesfully !'
            ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }
    public function articlesClasse(Request $request){

        $articlesClasse = articlesClass::where('classe_id',$request->classe_id)->get()->pluck('articles') ;
        return response()->json([
            'articles ' => json_decode($articlesClasse)
        ]) ;

    }

    public function addArticlesOnClasse(Request $request){
        try {

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
                        'Article added with success !'
                    ]) ;
                }

                // case 2 : false => create new article inside the classe_articles class
                $articlesList[] = $request->article ;
                $articlesClass->update([
                    'articles' => $articlesList
                    ]) ;
                return response()->json([
                    'Article added with success into emty classe articles !'
                ]) ;
            }
            // if the there is no articles_classe class => ceate new articles_class and put the article iside
            else{
                $articleToadd[] = $request->article ;
                $related = $classe->articles()->create([
                    'articles' => $articleToadd
                ]) ;
                return response()->json([
                    'message' => 'New class has been added : '.$request->classe_id ,
                    'with the following articles '.$related
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
    public function deleteArticlesOnClasse(Request $request){
        try {
            $articleId = 5;

            $articleClass = articlesClass::where('classe_id', $request->classe_id)
                ->whereJsonContains('articles', ['id' => $articleId])
                ->first();

            if ($articleClass) {
                $articles = collect($articleClass->articles)
                    ->reject(function ($article) use ($articleId) {
                        return $article['id'] == $articleId;
                    });

                $articleClass->update(['articles' => json_decode($articles)]);
                return response()->json([
                    'article deleted successfuly'
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
