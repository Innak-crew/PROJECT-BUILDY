<?php

namespace App\Http\Controllers;

use App\Mail\SendLoginDetails;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use stdClass;

class AdminController extends Controller
{

    private function generatePassword($length = 12, $useUppercase = true, $useLowercase = true, $useNumbers = true, $useSymbols = true) {
        $characters = '';
      
        if ($useUppercase) {
          $characters .= strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, floor($length / 4)));
        }
      
        if ($useLowercase) {
          $characters .= strtolower(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, floor($length / 4)));
        }
      
        if ($useNumbers) {
          $characters .= substr(str_shuffle('0123456789'), 0, floor($length / 4));
        }
      
        if ($useSymbols) {
          $characters .= substr(str_shuffle('!@#$%&?'), 0, floor($length / 4));
        }
      
        $len = strlen($characters);
        if ($len < $length) {
          $characters .= str_shuffle(substr($characters, 0, $len) . ($useUppercase ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '') . ($useLowercase ? 'abcdefghijklmnopqrstuvwxyz' : '') . ($useNumbers ? '0123456789' : '') . ($useSymbols ? '!@#$%^&*()~-_=+{};:,<.>/?': ''));
        }
      
        return substr(str_shuffle($characters), 0, $length);
      }

    private function pageDataToBeEmpty ($pageData) {
        if($pageData->isEmpty()) {$pageData = [];}
        return $pageData;
    }

    // Common method to get user data
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

        if($sectionName == "Dashboard"){
            $pageData->Schedules = $user->schedule()->get();
        }else if ($title == "List Reminder"){
            $pageData->Reminders = $user->reminders()->orderBy('created_at', 'desc')->get();
        }
       
        return [
            'title' => $title,
            'sectionName' => $sectionName,
            'userName' => $userName,
            'userId' => $userId,
            "user" => $user,
            'pageData' => $pageData,
            'displayReminder' => $reminder
        ];
    }

     public function index()
    {
        $data = $this->getUserData('Dashboard', 'Index');
        return view('admin.index', $data);
    }

    public function add_user()
    {
        $data = $this->getUserData('Users', 'Add User');
        return view('admin.add-user', $data);
    }

    public function user_store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'role' => ['required', 'regex:/^(admin|manager)$/i']
        ]);

        // If validation fails, redirect back with errors and input data
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate a random password for the new user
        $password = $this->generatePassword();

        // Create a new user with the provided data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($password),
        ]);

        // Send an email with login details to the new user
        try {
            Mail::to($request->email)->send(new SendLoginDetails($request->name, $request->email, $password));
            return back()->with('message', 'Mail sent successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to send mail. Please try again later.');
        }
    }

    public function reminder()
    {
        $data = $this->getUserData('Reminder', 'Set Reminder');
        return view('admin.reminder.index', $data);
    }

    public function reminder_list()
    {
        $data = $this->getUserData('Reminder', 'List Reminder');
        return view('admin.reminder.list', $data);
    }

    public function reminder_view(string $encodedId) 
    {
        $decodedId = base64_decode($encodedId); 
        try {
            $reminder = Auth::user()->reminders()->findOrFail($decodedId);
            $data = $this->getUserData('Reminder', 'View Reminder', $reminder);
            $user_id = Auth::id();
            if($user_id != $reminder->user_id){
                abort(403, 'You can only view reminders you created.');
            }
            return view('admin.reminder.view', $data);
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Customer not found'); 
        }
    }

    public function reminder_edit(string $encodedId) 
    {
        $decodedId = base64_decode($encodedId); 
        try {
            $reminder = Auth::user()->reminders()->findOrFail($decodedId);
            try {
                $data = $this->getUserData('Reminder', 'Edit Reminder', $reminder);
              } catch (Exception $e) {
                return abort(500, 'An error occurred while processing your request.');
              }
            $user_id = Auth::id();
            if($user_id != $reminder->user_id){
                abort(403, 'You can only edit reminders you created.');
            }
            return view('admin.reminder.edit', $data);
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Customer not found'); 
        }
    }


}
