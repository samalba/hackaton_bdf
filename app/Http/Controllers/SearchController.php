<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SearchController extends Controller
{
    public function index(Request $request) {
        return view('welcome');
    }

    public function search1(Request $request) {
        
        if($request->ajax())
        {
            if (preg_match('/\s/', $request->name)) {
                $output = '';
                if ($request->name == NULL) {
                    if ($request->etablissement == NULL && $request->competence != NULL) {
                        //Faire recherche avec uniquement filtre compétence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence == NULL) {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                        ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence
                        $sql = DB::table('user')
                            ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                            ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->etablissement == NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre compétence + filtre nom 
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })  
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence == NULL) {
                        //Faire recherche avec filtre etablissement + filtre nom
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })  
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })
                                ->distinct()  
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre nom
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })  
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            else {
                $output = '';
                if ($request->name == NULL) {
                    if ($request->etablissement == NULL && $request->competence != NULL) {
                        //Faire recherche avec uniquement filtre compétence
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence == NULL) {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->etablissement == NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre compétence + filtre nom 
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence == NULL) {
                        //Faire recherche avec filtre etablissement + filtre nom
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.prenom_user', 'like', '%' . $request->name.'%');
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.nom_user', 'like','%' . $request->name.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where(function($q) use ($request) {
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.prenom_user', 'like','%' .  $request->name.'%')
                                    ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre nom
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                ->orWhere('user.nom_user', 'like', '%' . $request->name.'%')
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            $total_row = $sql->count();
            if($total_row > 0)
            {  
                if (isset($sqq)) {
                    foreach($sql as $row)
                    {
                        foreach ($sqq as $sqqq) {
                            $output = '';
                            $link = "/profile/" . $row->uniqid;
                            $output = "<div class='round'> <p class='text'> <span class='material-icons icon'> perm_identity </span>
                            <a class='profile' href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . " </a> </p>
                                    <p class='text text1'>
                                        <span class='material-icons icon'>
                                            school
                                        </span>
                                            " . $sqqq->nom_etablissement . " </p>
                                </div>";
                            echo $output;
                        }
                    }
                }
                else {
                    foreach($sql as $row)
                    {
                        $output = '';
                        $link = "/profile/" . $row->uniqid;
                        $output = "<div class='round'> <p class='text'> <span class='material-icons icon'> perm_identity </span>
                        <a class='profile' href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . " </a> </p>
                                <p class='text text1'>
                                    <span class='material-icons icon'>
                                        school
                                    </span>
                                        " . $row->nom_etablissement . " </p>
                            </div>";
                        echo $output;
                    }
                }
            }
            else {
                echo "Personne ne peux être trouvé !";
            }
        }
    }

    public function search2(Request $request) {
        
        if($request->ajax())
        {
            if (preg_match('/\s/', $request->name)) {
                $output = '';
                if ($request->etablissement == NULL) {
                    if ($request->name == NULL && $request->competence != NULL) {
                        //Faire recherche avec uniquement filtre compétence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence == NULL) {
                        //Faire recherche avec uniquement filtre name
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre name AND filtre compétence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->name == NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre compétence + filtre etablissement 
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence == NULL) {
                        //Faire recherche avec filtre name + filtre etablissement
                        $sql = DB::table('user')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                }) 
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                            ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                }) 
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')                        
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            else {
                $output = '';
                if ($request->etablissement == NULL) {
                    if ($request->name == NULL && $request->competence != NULL) {
                        //Faire recherche avec uniquement filtre compétence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence == NULL) {
                        //Faire recherche avec uniquement filtre name
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                ->orWhere('user.nom_user', 'like', '%' . $request->name.'%')
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre name AND filtre compétence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('user.prenom_user', 'like','%' .  $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->name == NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre compétence + filtre etablissement 
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence == NULL) {
                        //Faire recherche avec filtre name + filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.prenom_user', 'like', '%' . $request->name.'%');
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.nom_user', 'like', '%' . $request->name.'%');
                                })
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where(function($q) use ($request) {
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->distinct()
                                //IL manque WHERE ETABLISSEMENT WHERE COMPETENCE (WHERE PRENOM OR WHERE NAME)
                                //->orWhere('user.nom_user', 'like', $request->name.'%')
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')                        
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            $total_row = $sql->count();
            if($total_row > 0)
            {
                foreach($sql as $row)
                {
                    $output = '';
                    $link = "/profile/" . $row->uniqid;
                    $output = "<div class='round'> <p class='text'> <span class='material-icons icon'> perm_identity </span>
                    <a class='profile' href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . " </a> </p>
                            <p class='text text1'>
                                <span class='material-icons icon'>
                                    school
                                </span>
                                    " . $row->nom_etablissement . " </p>
                        </div>";
                    echo $output;
                }
            }
            else {
                echo "Personne ne peux être trouvé !";
            }
        }
    }

    public function search3(Request $request) {
        if($request->ajax())
        {
            if (preg_match('/\s/', $request->name)) {
                $output = '';
                if ($request->competence == NULL) {
                    if ($request->name == NULL && $request->etablissement != NULL) {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')                        
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement == NULL) {
                        //Faire recherche avec uniquement filtre name
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre name AND filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })                    
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->name == NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre compétence + filtre etablissement 
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement == NULL) {
                        //Faire recherche avec filtre name + filtre competence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                }) 
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                }) 
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre competence
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%')
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            else {
                $output = '';
                if ($request->competence == NULL) {
                    if ($request->name == NULL && $request->etablissement != NULL) {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')                        
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement == NULL) {
                        //Faire recherche avec uniquement filtre name
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                ->orWhere('user.nom_user', 'like', '%' . $request->name.'%')
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre name AND filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                    ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%');
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('user.nom_user', 'like', $request->name.'%')
                                    ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%');
                                })                       
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->name == NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre compétence + filtre etablissement 
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement == NULL) {
                        //Faire recherche avec filtre name + filtre competence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                }) 
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                                    ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                    ->join('etablissement', function ($join) {
                                        $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                            ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                            ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                            ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                        })
                                ->where(function($q) use ($request) {
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                }) 
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre competence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            $total_row = $sql->count();
            if($total_row > 0)
            {
                foreach($sql as $row)
                {
                    $output = '';
                    $link = "/profile/" . $row->uniqid;
                    $output = "<div class='round'> <p class='text'> <span class='material-icons icon'> perm_identity </span>
                    <a class='profile' href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . " </a> </p>
                            <p class='text text1'>
                                <span class='material-icons icon'>
                                    school
                                </span>
                                    " . $row->nom_etablissement . " </p>
                        </div>";
                    //$output = "<div class='round'> <a class='profile' href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . ", University: " . $row->nom_etablissement ."</a> </div>";
                    echo $output;
                }
            }
            else {
                echo "Personne ne peux être trouvé !";
            }
        }
    }

    public function profile(Request $request, $id) {
        $query = DB::table('user')
            ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
            ->where('user.uniqid', '=', $id)
            ->get();
        $article = DB::table('article')
            ->join('user', 'article.id_auteur', '=', 'user.uniqid')
            ->where('user.uniqid', '=', $id)
            ->get();
        $etablissement = DB::table('user')
            ->join('etablissement', function ($join) {
            $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
            })
            ->where('user.uniqid', '=', $id)
            ->get(['etablissement.nom_etablissement']);
        $ind = 0;
        $string = '';
        while ($ind < count($article) - 1) {
            $string = $string . $article[$ind]->JEL_1.' | '.$article[$ind + 1]->JEL_1.' | ';
            $ind = $ind + 1;
        }
        $str = implode(' | ',array_unique(explode(' | ', $string)));
        if (count($query) == 0)
            return redirect('/');
        $data = array (
            'infos' => $query,
            'article' => $article,
            'str' => $str,
            'etablissement' => $etablissement,
        );
        return view ('profile')->with($data);
    }

    public function profilesearch(Request $request) {
        $article = DB::table('article')
        ->join('user', 'article.id_auteur', '=', 'user.uniqid')
        ->where('user.uniqid', '=', $request->id)
        ->where(function($q) use ($request) {
            $q->where('article.name_paper', 'like', '%' . $request->competence.'%')
            ->orWhere('article.JEL_1', 'like', '%' . $request->competence.'%')
            ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
            ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
            ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%')
            ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%');
        })
        ->get();
        $total_row = $article->count();
            if($total_row > 0)
            {
                foreach($article as $row)
                {
                    $output = '';
                    $link = "/profile/" . $row->uniqid;
                    $output =  "<p> 
                    <span class='material-icons icon_article'>
                        menu_book
                        </span>
                    <span class='title_article'> <a target='blank' href=" . $row->link_paper . ">" . $row->name_paper . "</a></span> </p>
                <p class='information'> 
                    <span class='material-icons icon_article '>
                        info
                        </span>
                    <span class='title_article'>" . $row->JEL_1 . "<strong> & </strong>" . $row->JEL_2 . "<strong> & </strong>" . $row->JEL_3 . "</span> </p>";
                    echo ($output);
                }
            }
            else {
                $output = "Aucun article trouvé !";
                echo ($output);
            }
        }
}
