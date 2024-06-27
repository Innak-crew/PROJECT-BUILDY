<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Designs;
use App\Models\Labour;
use App\Models\Log;
use App\Models\Orders;
use App\Models\QuantityUnits;
use App\Models\Reminders;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $user = User::find(Auth::id());
        $userName = $user ? $user->name : 'Guest';
        $userId = $user ? $user->id : 'Guest';
        $totalPages = 1;

        $reminder = $user->reminders()
            ->where('is_completed', 0)
            ->orderBy('priority', 'asc')
            ->orderBy('reminder_time', 'asc')
            ->get();


        if($title == "Index"){
            $publicSchedules = Schedule::where('visibility', 'public')->get();
            $managerSchedules = Schedule::where('visibility', 'manager')->get();
            $userSchedules = $user->schedule()->get();
            $pageData->Schedules = $publicSchedules->merge($userSchedules);
            $pageData->Schedules = $pageData->Schedules->merge($managerSchedules);
        }else if ($title == "List Reminder"){
            $pageData->reminders = $user->reminders()->orderBy('created_at', 'desc')->get();
        }else if ($title == "New Design"){
            $pageData->QuantityUnits = QuantityUnits::all();
        }else if ($title == "Gallery" || $title == "List Designs"){
            $pageData = Designs::all();
        }else if ($title == "Quantity Units"){
            $pageData->QuantityUnits = QuantityUnits::all();
        }else if ($title == "Report" ){
            $pageData->orders = Orders::where('user_id',$userId);
            $pageData->customers = Customers::where('user_id',$userId)->get();
        }else if ($title == "New Order"){
            $pageData->QuantityUnits = QuantityUnits::all();
        }else if ($title == "List Orders"){
            $pageData->Orders = Orders::where('user_id',$user->id)->with('orderItems')->get();
        }
       
        // dd($pageData->reminders);
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
        return view('manager.index', $data);
    }

    public function profile()
   {
       $data = $this->getUserData('General', 'Profile');
       return view('manager.profile', $data);
   }

   public function invoice()
  {
      $data = $this->getUserData('General', 'Invoice');
      return view('manager.invoice', $data);
  }

   public function reminder()
   {
       $data = $this->getUserData('Reminder', 'Set Reminder');
       return view('manager.reminder.index', $data);
   }

   public function reminder_list()
   {
       $data = $this->getUserData('Reminder', 'List Reminder');
       return view('manager.reminder.list', $data);
   }

   public function reminder_view(string $encodedId) 
   {
       $decodedId = base64_decode($encodedId); 
       try {
           $reminder = User::find(Auth::id())->reminders()->findOrFail($decodedId);
           $data = $this->getUserData('Reminder', 'View Reminder', $reminder);
           $user_id = Auth::id();
           if($user_id != $reminder->user_id){
               abort(403, 'You can only view reminders you created.');
           }
           return view('manager.reminder.view', $data);
       } catch (ModelNotFoundException $e) {
           return abort(404, 'Customer not found'); 
       }
   }

   public function reminder_edit(string $encodedId, Request $request) 
   {
       $decodedId = base64_decode($encodedId); 
       try {
            $user = User::find(Auth::id());
            $reminder = $user->reminders()->findOrFail($decodedId);
           try {
               $data = $this->getUserData('Reminder', 'Edit Reminder', $reminder);
             } catch (Exception $e) {
               return abort(500, 'An error occurred while processing your request.');
             }
           $user_id = Auth::id();
           if($user_id != $reminder->user_id){
            Log::create([
                'message' => 'Unauthorized operation by ' . $user->email . ' while trying to view product.',
                'level' => 'warning',
                'type' => 'security',
                'ip_address' => $request->ip(),
                'context' => 'web',
                'source' => 'view_product',
                'extra_info' => json_encode(['user_agent' => $request->header('User-Agent')])
            ]);
               abort(403, 'You can only edit reminders you created.');
           }
           return view('manager.reminder.edit', $data);
       } catch (ModelNotFoundException $e) {
           return abort(404, 'Customer not found'); 
       }
   }

   public function Gallery()
   {
       $data = $this->getUserData('General', 'Gallery');
       return view('manager.gallery', $data);
   }

   public function newDesign()
   {
       $data = $this->getUserData('Designs', 'New Design');
       return view('manager.design.add', $data);
   }

   public function listDesign()
   {
       $data = $this->getUserData('Designs', 'List Designs');
       return view('manager.design.list', $data);
   }

   public function viewDesign(string $encodedId) 
   {
       $decodedId = base64_decode($encodedId);

       try {
           $pageData = new stdClass();
           $pageData->design = Designs::findOrFail($decodedId);
           $pageData->QuantityUnits = QuantityUnits::all();
           $data = $this->getUserData('Designs', 'View Design', $pageData);
           return view('manager.design.view', $data);
       } catch (ModelNotFoundException $e) {
           return abort(404, 'Order not found'); 
       }
   }

   public function editDesign(string $encodedId) 
   {
       $decodedId = base64_decode($encodedId);

       try {
           $pageData = new stdClass();
           $pageData->design = Designs::findOrFail($decodedId);
           $pageData->QuantityUnits = QuantityUnits::all();
           $data = $this->getUserData('Designs', 'Edit Design', $pageData);
           return view('manager.design.edit', $data);
       } catch (ModelNotFoundException $e) {
           return abort(404, 'Order not found'); 
       }
   }

   public function QuantityUnits()
   {
       $data = $this->getUserData('General', 'Quantity Units');
       return view('manager.quantity-units.unit', $data);
   }

   public function QuantityUnitsAdd()
   {
       $data = $this->getUserData('General', 'Quantity Units');
       return view('manager.quantity-units.add', $data);
   }

   public function QuantityUnitsEdit(string $encodedId)
   {
       $decodedId = base64_decode($encodedId); 
       $pageData = new stdClass();
       try {
           $pageData->QuantityUnits = QuantityUnits::all();
           $pageData->ChangedQuantityUnit = QuantityUnits::findOrFail($decodedId);
           try {
               $data = $this->getUserData('General', 'Quantity Units', $pageData);
             } catch (Exception $e) {
               return abort(500, 'An error occurred while processing your request.');
             }
           return view('manager.quantity-units.edit', $data);
       } catch (ModelNotFoundException $e) {
           return abort(404, 'Quantity Unit not found'); 
       }
   }

   public function Report()
   {
       $data = $this->getUserData('General', 'Report');
       return view('manager.report', $data);
   }

   public function newOrder()
   {
       $data = $this->getUserData('Orders', 'New Order');
       return view('manager.orders.store', $data);
   }

   public function listOrder()
   {
       $data = $this->getUserData('Orders', 'List Orders');
       return view('manager.orders.list', $data);
   }

   public function viewOrder(string $encodedId) 
   {
       $decodedId = base64_decode($encodedId);

       try {
           $order = Orders::findOrFail($decodedId);

            if ($order->user_id != Auth::id()) {
                abort(403, 'You can only view orders you created or if an admin gave access.');
            }

           $pageData = new stdClass();
           $pageData->order = $order;
           $labourData=[];

           $labours = Labour::where('order_id', $order->id)->orderBy('date')->get();
           foreach ($labours as $labour) {
               $date = Carbon::parse($labour->date)->format('Y-m-d');
               if (!isset($labourData[$date])) {
                   $labourData[$date] = [];
               }
               
               $labourData[$date][] = [
                   'labor_category_id' => $labour->labor_category_id,
                   'labor_category_name' => $labour->category->name,
                   'number_of_labors' => $labour->number_of_labors,
                   'per_labor_amount' => $labour->per_labor_amount,
                   'total_amount' => $labour->total_amount,
                   'date' => Carbon::parse($labour->date)->format('jS F Y'),
                   'edit_link' => route('manager.order.Labours',['encodedOrderId'=> $encodedId,"date"=>$date])
               ];
           }

           $pageData->labours = $labourData;
           $pageData->follow_up = Reminders::where('order_id',$order->id)->orderBy('reminder_time')->get();

           $data = $this->getUserData('Orders', 'View Order', $pageData);
           return view('manager.orders.view', $data);
       } catch (ModelNotFoundException $e) {
           return abort(404, 'Order not found'); 
       }
   }

   public function editOrder(string $encodedId) 
   {
       $decodedId = base64_decode($encodedId);

       try {
           $pageData = new stdClass();
           $pageData->order = Orders::findOrFail($decodedId);
           if ($pageData->order->user_id != Auth::id()) {
                abort(403, 'You can only edit orders you created or if an admin gave access.');
           }
           $pageData->QuantityUnits = QuantityUnits::all();
           $data = $this->getUserData('Orders', 'Edit Order', $pageData);
           return view('manager.orders.update', $data);
       } catch (ModelNotFoundException $e) {
           return abort(404, 'Order not found'); 
       }
   }

   public function showLabours($encodedOrderId, $date = null)
   {
       // Decode the order ID
       $decodedOrderId = base64_decode($encodedOrderId);

       try {
           $pageData = new stdClass();
           $pageData->order = Orders::findOrFail($decodedOrderId);
           if ($pageData->order->user_id != Auth::id()) {
                abort(403, 'You can only add or update labors for a orders you created or if an admin gave access.');
            }
           $date = $date ?? Carbon::today()->toDateString();
           $pageData->date = $date;
           $pageData->labours = Labour::where('order_id', $decodedOrderId)->where('date', $date)->get();
           $data = $this->getUserData('Orders', 'Show Labours', $pageData);
           return view('manager.orders.workers', $data);
       } catch (ModelNotFoundException $e) {
           return abort(404, 'Order not found'); 
       }
   }




}
