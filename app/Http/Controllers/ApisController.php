<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Customers;
use App\Models\Products;
use Illuminate\Http\Request;

class ApisController extends Controller
{
    public function index( string $action, string $encodedUserID, string $name, string $searchTerm){
        $returnData = [];

        $decodedUserId = base64_decode($encodedUserID);

        if($action == "get"){
            if($name == "customer-by-id"){
                
                $customerId = base64_decode($searchTerm);
                $customer = Customers::where('id', $customerId)
                ->where('user_id', $decodedUserId)
                ->first();
                if ($customer) {
                    return response()->json([
                        'id' => $customer->id,
                        'name' => $customer->name,
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Customer not found.'
                    ], 404);
                }
            }else if ($name == "category-by-id") {
                $categoryId = base64_decode($searchTerm);
                $category = Categories::where('id', $categoryId)
                ->first();
                if ($category) {
                    return response()->json([
                        'id' => $category->id,
                        'name' => $category->name,
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Category not found.'
                    ], 404);
                }

            }
        }

        return response()->json([]);
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
