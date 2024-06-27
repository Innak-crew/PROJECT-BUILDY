<?php

namespace App\Http\Controllers;

use App\Mail\SendLoginDetails;
use App\Models\Customers;
use App\Models\Designs;
use App\Models\Labour;
use App\Models\Log;
use App\Models\Orders;
use App\Models\QuantityUnits;
use App\Models\Reminders;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $user = User::find(Auth::id());
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
        }else if ($title == "Quantity Units"){
            $pageData->QuantityUnits = QuantityUnits::all();
        }else if ($title == "New Product"){
            $pageData->QuantityUnits = QuantityUnits::all();
        }else if ($title == "New Order"){
            $pageData->QuantityUnits = QuantityUnits::all();
            $pageData->managers = User::where('role','manager')->get();
        }else if ($title == "List Product"){
            $pageData->Products = $user->products()->get();
        }else if ($title == "List Orders"){
            $pageData->Orders = Orders::with('orderItems')->get();
        }else if ($title == "Invoice"){
            $pageData = Orders::orderByDesc('created_at')->with('orderItems')->get();
        }else if ($title == "New Design"){
            $pageData->QuantityUnits = QuantityUnits::all();
        }else if ($title == "Gallery" || $title == "List Designs"){
            $pageData = Designs::all();
        }else if ($title == "Report" ){
            $pageData->orders = Orders::all();
            $pageData->users = User::where('role','!=',"developer")->get();
            $pageData->customers = Customers::all();
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

    public function QuantityUnits()
    {
        $data = $this->getUserData('General', 'Quantity Units');
        return view('admin.quantity-units.unit', $data);
    }

    public function QuantityUnitsAdd()
    {
        $data = $this->getUserData('General', 'Quantity Units');
        return view('admin.quantity-units.add', $data);
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
            return view('admin.quantity-units.edit', $data);
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Quantity Unit not found'); 
        }
    }

    public function newProduct()
    {
        $data = $this->getUserData('Products', 'New Product');
        return view('admin.product.add', $data);
    }

    public function listProduct()
    {
        $data = $this->getUserData('Products', 'List Product');
        return view('admin.product.list', $data);
    }

    public function viewProduct(string $encodedId, Request $request) 
    {
        $decodedId = base64_decode($encodedId); 
        try {
            $user = User::find(Auth::id());
            $product = $user->products()->findOrFail($decodedId);
            $data = $this->getUserData('Products', 'View Product', $product);
            $user_id = Auth::id();
            if($user_id != $product->user_id){
                Log::create([
                    'message' => 'Unauthorized operation by ' . $user->email . ' while trying to view product.',
                    'level' => 'warning',
                    'type' => 'security',
                    'ip_address' => $request->ip(),
                    'context' => 'web',
                    'source' => 'view_product',
                    'extra_info' => json_encode(['user_agent' => $request->header('User-Agent')])
                ]);
                abort(403, 'You can only view product you created.');
            }
            return view('admin.product.view', $data);
        } catch (ModelNotFoundException $e) {
            
            return abort(404, 'Customer not found'); 
        }
    }

    public function editProduct(string $encodedId, Request $request)
    {
        $decodedId = base64_decode($encodedId);
        try {
            $user = User::find(Auth::id());
            $product = $user->products()->findOrFail($decodedId);
    
            if ($user->id != $product->user_id) {
                Log::create([
                    'message' => 'Unauthorized operation by ' . $user->email . ' while trying to edit product.',
                    'level' => 'warning',
                    'type' => 'security',
                    'ip_address' => $request->ip(),
                    'context' => 'web',
                    'source' => 'edit_product',
                    'extra_info' => json_encode(['user_agent' => $request->header('User-Agent')])
                ]);
                abort(403, 'You can only edit a product you created.');
            }
    
            $pageData = new stdClass();
            $pageData->product = $product;
            $pageData->QuantityUnits = QuantityUnits::all();
            $data = $this->getUserData('Products', 'Edit Product', $pageData);
    
            return view('admin.product.edit', $data);
        } catch (ModelNotFoundException $e) {
            
            return abort(404, 'Product not found');
        }
    }

    public function newOrder()
    {
        $data = $this->getUserData('Orders', 'New Order');
        return view('admin.orders.store', $data);
    }

    public function listOrder()
    {
        $data = $this->getUserData('Orders', 'List Orders');
        return view('admin.orders.list', $data);
    }

    public function viewOrder(string $encodedId) 
    {
        $decodedId = base64_decode($encodedId);

        try {
            $order = Orders::findOrFail($decodedId);

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
                    'edit_link' => route('admin.order.Labours',['encodedOrderId'=> $encodedId,"date"=>$date])
                ];
            }

            $pageData->labours = $labourData;
            $pageData->follow_up = Reminders::where('order_id',$order->id)->orderBy('reminder_time')->get();

            $data = $this->getUserData('Orders', 'View Order', $pageData);
            return view('admin.orders.view', $data);
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Order not found'); 
        }
    }

    public function editOrder(string $encodedId) 
    {
        $decodedId = base64_decode($encodedId);

        try {
            $pageData = new stdClass();
            $pageData->QuantityUnits = QuantityUnits::all();
            $pageData->order = Orders::findOrFail($decodedId);
            $pageData->managers = User::where('role','manager')->get();
            $data = $this->getUserData('Orders', 'Edit Order', $pageData);
            return view('admin.orders.update', $data);
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
            $date = $date ?? Carbon::today()->toDateString();
            $pageData->date = $date;
            $pageData->labours = Labour::where('order_id', $decodedOrderId)->where('date', $date)->get();
            $data = $this->getUserData('Orders', 'Show Labours', $pageData);
            return view('admin.orders.workers', $data);
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Order not found'); 
        }
    }

    public function invoiceShow()
    {
        $data = $this->getUserData('Invoice', 'Invoice');
        return view('admin.invoice.index', $data);
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

        
        DB::beginTransaction();

         // Send an email with login details to the new user
        try {

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($password),
            ]);

       
            Mail::to($request->email)->send(new SendLoginDetails($request->name, $request->email, $password));
            DB::commit();

            return back()->with('message', 'Mail sent successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::create([
                'message' => 'Failed to send mail.',
                'level' => 'danger',
                'type' => 'error',
                'ip_address' => $request->ip(),
                'context' => 'web',
                'source' => 'create_user',
                'extra_info' => json_encode(['user_agent' => $request->header('User-Agent'),"error"=>$e])
            ]);
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
            $reminder = User::find(Auth::id())->reminders()->findOrFail($decodedId);
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
                    'message' => 'Unauthorized operation by ' . $user->email . ' while trying to edit reminders',
                    'level' => 'warning',
                    'type' => 'security',
                    'ip_address' => $request->ip(),
                    'context' => 'web',
                    'source' => 'edit_reminder',
                    'extra_info' => json_encode(['user_agent' => $request->header('User-Agent')])
                ]);
                abort(403, 'You can only edit reminders you created.');
            }
            return view('admin.reminder.edit', $data);
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Customer not found'); 
        }
    }

    public function Gallery()
    {
        $data = $this->getUserData('General', 'Gallery');
        return view('admin.gallery', $data);
    }

    public function newDesign()
    {
        $data = $this->getUserData('Designs', 'New Design');
        return view('admin.design.add', $data);
    }

    public function listDesign()
    {
        $data = $this->getUserData('Designs', 'List Designs');
        return view('admin.design.list', $data);
    }

    public function viewDesign(string $encodedId) 
    {
        $decodedId = base64_decode($encodedId);

        try {
            $pageData = new stdClass();
            $pageData->design = Designs::findOrFail($decodedId);
            $pageData->QuantityUnits = QuantityUnits::all();
            $data = $this->getUserData('Designs', 'View Design', $pageData);
            return view('admin.design.view', $data);
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
            return view('admin.design.edit', $data);
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Order not found'); 
        }
    }

    public function Report()
    {
        $data = $this->getUserData('General', 'Report');
        return view('admin.report', $data);
    }

}
