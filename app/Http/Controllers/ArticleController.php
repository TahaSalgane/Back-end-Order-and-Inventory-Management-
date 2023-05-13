<?php

namespace App\Http\Controllers;

use App\Models\article;
use Exception;
use Illuminate\Http\Request;

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
            $article = article::create([
                'designationArticle' => $request->designationArticle
            ]) ;
            return response()->json([
                'articles added succesfully !' ,
                $article->id

            ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function editActicle(Request $request){
        try {

            $article = article::find($request->id)->update([
                'designationArticle' => $request->designationArticle
            ]) ;
            return response()->json([
                'message' => 'article updated succesfully !' ,
            ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }

    public function deleteArticle(Request $request){
        try {
            article::find($request->id)->delete() ;
            return response()->json([
                'article deleted succesfully !'
            ]) ;
        } catch (Exception $exp) {
            return response()->json([
                'error' => $exp->getMessage()
            ]) ;
        }
    }
}
