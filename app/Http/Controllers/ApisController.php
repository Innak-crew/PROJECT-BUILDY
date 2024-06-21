<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Customers;
use App\Models\Designs;
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

    public function search(string $encodedUserID, string $name, string $searchTerm)
    {
        $returnData = [];
        $decodedUserId = base64_decode($encodedUserID);
    
        switch ($name) {
    
            case 'customers':
                $returnData = Customers::where('user_id', $decodedUserId)
                    ->where(function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                              ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
                              ->orWhere('phone', 'LIKE', '%' . $searchTerm . '%')
                              ->orWhere('address', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->get();
                break;
    
            case 'categories':
                $returnData = Categories::whereNull('parent_id')
                    ->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->get();
                break;
    
            case 'subcategories':
                $categoryParam = request()->input('category');
                if ($categoryParam) {
                    $category = Categories::where('name', 'LIKE', '%' . $categoryParam . '%')
                        ->whereNull('parent_id')
                        ->first();
                    
                    if ($category) {
                        $returnData = Categories::where('parent_id', $category->id)
                            ->where('name', 'LIKE', '%' . $searchTerm . '%')
                            ->get();
                    } else {
                        $returnData = Categories::whereNotNull('parent_id')
                            ->where('name', 'LIKE', '%' . $searchTerm . '%')
                            ->get();
                    }
                } else {
                    $returnData = Categories::whereNotNull('parent_id')
                        ->where('name', 'LIKE', '%' . $searchTerm . '%')
                        ->get();
                }
                break;
    
            case 'designs':
                $categoryParam = request()->input('category');
                $subcategoryParam = request()->input('subcategory');
                $searchKeyParam = request()->input('searchKey');
    
                $designsQuery = Designs::query();
    
                if ($categoryParam) {
                    $category = Categories::where('name', 'LIKE', '%' . $categoryParam . '%')
                        ->whereNull('parent_id')
                        ->first();
    
                    if ($category) {
                        $subcategories = Categories::where('parent_id', $category->id)->get()->pluck('id');
                        $designsQuery->whereIn('category_id', $subcategories);
                    }
                }
    
                if ($subcategoryParam) {
                    $subcategory = Categories::where('name', 'LIKE', '%' . $subcategoryParam . '%')
                        ->whereNotNull('parent_id')
                        ->first();
    
                    if ($subcategory) {
                        $designsQuery->where('category_id', $subcategory->id);
                    }
                }
    
                if ($searchKeyParam != 'undefined') {
                    $designsQuery->where('name', 'LIKE', '%' . $searchKeyParam . '%');
                }
    
                if ($searchTerm === 'all') {
                    $returnData = $designsQuery->get();
                } else {
                    $returnData = $designsQuery->find($searchTerm);
                }
                break;
    
            default:
                return response()->json(['error' => 'Invalid search type'], 400);
        }
    
        return response()->json($returnData);
    }
    
}
