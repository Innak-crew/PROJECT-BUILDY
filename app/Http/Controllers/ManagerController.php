<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class ManagerController extends Controller
{
    private function pageDataToBeEmpty ($pageData) {
        if($pageData->isEmpty()) {$pageData = [];}
        return $pageData;
    }

    // Common method to get user data
    private function getUserData($sectionName, $title, $pageData = new stdClass())
    {
        $user = Auth::user();
        $userName = $user ? $user->name : 'Guest';
        $userId = $user ? $user->id : 'Guest';
        $totalPages = 1;

        $reminder = $user->reminders()
            ->where('is_completed', 0)
            ->orderBy('priority', 'asc')
            ->orderBy('reminder_time', 'asc')
            ->get();

        if($title == "Index"){
            $pageData->Schedules = $user->schedule()->get();
        }
       
        return [
            'title' => $title,
            'sectionName' => $sectionName,
            'userName' => $userName,
            'user' => $user,
            "pageData" => $pageData,
            'displayReminder' => $reminder
        ];
    }

     public function index()
    {
        $data = $this->getUserData('Dashboard', 'Index');
        return view('manager.index', $data);
    }

    public function profile()
   {
       $data = $this->getUserData('General', 'Profile');
       return view('manager.profile', $data);
   }

   public function remainder()
  {
      $data = $this->getUserData('Remainder', 'Set Remainder');
      return view('manager.remainder', $data);
  }



}
