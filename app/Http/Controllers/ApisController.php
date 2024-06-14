<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Customers;
use App\Models\Products;
use Illuminate\Http\Request;

class ApisController extends Controller
{
    public function index( string $action, string $name, string $searchTerm){
        $returnData = [];

        if($action == "get"){
            if($name == "customer"){
                if($searchTerm == "all"){
                    $returnData = Customers::all();
                }
                else if($searchTerm == "name"){
                    $returnData = Customers::where('name', 'LIKE', '%'. $searchTerm . '%')->get();
                }
            }
        }else if($action == "search"){
            if($name == "customer"){
                if($searchTerm != null){
                    $returnData = Customers::where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('email', 'LIKE', '%' . $searchTerm . '%') 
                ->orWhere('phone', 'LIKE', '%' . $searchTerm . '%') 
                ->orWhere('address', 'LIKE', '%' . $searchTerm . '%') 
                ->get();
                }
            }else if ($name == "products"){

            }
        }

        return response()->json($returnData);
    }

    public function Search( string $encodedUserID, string $name, string $searchTerm){
        $returnData = [];

        $decodedUserId = base64_decode($encodedUserID);

        if($name == "products"){
            $returnData = Products::where('user_id', $decodedUserId)
            ->Where('name', 'LIKE', '%' . $searchTerm . '%') 
            ->get();
        }else if ($name == "customers"){
            $returnData = Customers::where('user_id', $decodedUserId)
            ->Where('name', 'LIKE', '%' . $searchTerm . '%') 
            ->orWhere('email', 'LIKE', '%' . $searchTerm . '%') 
            ->orWhere('phone', 'LIKE', '%' . $searchTerm . '%') 
            ->orWhere('address', 'LIKE', '%' . $searchTerm . '%') 
            ->get();
        }else if ($name == "categories") {
            $returnData = Categories::whereNull('parent_id')
                ->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->get();
        } else if ($name == "subcategories") {
            $returnData = Categories::whereNotNull('parent_id')
                ->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->get();
        }

        return response()->json($returnData);
    }

}
