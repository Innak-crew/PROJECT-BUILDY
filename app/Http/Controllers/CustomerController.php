<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use stdClass;

class CustomerController extends Controller
{
    private function getUserData(string $sectionName, string $title, object $pageData = new stdClass()): array
    {
        $user = Auth::user();
        $userName = $user ? $user->name : 'Guest';
        $userId = $user ? $user->id : 'Guest';
        $reminder = $user->reminders()
            ->where('is_completed', 0)
            ->orderBy('priority', 'asc')
            ->orderBy('reminder_time', 'asc')
            ->get();

        if ($title === "Index") {
            $pageData->Schedules = $user->schedule()->get();
        }else if ($title == "List Customer"){
            $pageData->Customers =  $user->customers()->get();
        }


        return [
            'title' => $title,
            'sectionName' => $sectionName,
            'userName' => $userName,
            'user' => $user,
            'pageData' => $pageData,
            'displayReminder' => $reminder
        ];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:customers,email',
            'phone' => 'required|min:10',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user_id = Auth::id();

        try {
            Customers::create([
                "user_id" => $user_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            return back()->with('message', 'Customer created successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create customer. Please try again later.');
        }
    }

    public function update(Request $request, string $encodedId)
    {
        $decodedId = base64_decode($encodedId); 
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('customers')->ignore($decodedId),
            ],
            'phone' => 'required|min:10',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        
        try {
            $customer = Customers::find($decodedId);
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->save();
            return back()->with('message', 'Customer updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update customer. Please try again later.');
        }
    }

    public function destroy(string $encodedId) 
    {
        $decodedId = base64_decode($encodedId); 
        try {
            $customer = Customers::findOrFail($decodedId);
            $customer->delete();
            return  back()->with('message', 'Customer deteled successfully.');
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Customer not found'); 
        }
    }

    public function add()
    {
        $data = $this->getUserData('Customer', 'Add Customer');
        return view('manager.customer.store', $data);
    }

    public function list()
    {

        $data = $this->getUserData('Customer', 'List Customer');
        return view('manager.customer.list', $data);
    }

    public function view(string $encodedId) 
    {
        $decodedId = base64_decode($encodedId); 
        try {
            $customer = Auth::user()->customers()->findOrFail($decodedId);
            $data = $this->getUserData('Customer', 'View Customer', $customer);
            return view('manager.customer.view', $data);
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Customer not found'); 
        }
    }

    public function edit(string $encodedId) 
    {
        $decodedId = base64_decode($encodedId); 
        try {
            $customer = Auth::user()->customers()->findOrFail($decodedId);
            $data = $this->getUserData('Customer', 'Edit Customer', $customer);
            return view('manager.customer.edit', $data);
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Customer not found'); 
        }
    }
}
