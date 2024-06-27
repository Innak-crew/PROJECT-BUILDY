<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeveloperController extends Controller
{
    public function Logs(){
        $logs = Log::orderByDesc('id')->get();
        return view('dev.logs', compact('logs'));
    }
    public function viewLog($id){
        $log = Log::find($id);
        dd(json_decode($log->first()),json_decode($log->extra_info));
        return view('dev.logs', compact('log'));
    }
    public function deleteLog($id){
        try {
            $log = Log::find($id);
            $log->delete();
            return back();
        } catch (\Exception $e) {
            Log::error('Failed to delete log: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to delete log",'error'=> $e], 500);
        }
    }

    public function clearCache(Request $request){
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('optimize');
            return response()->json(["status" => "success", "message" => "Cache cleared successfully"]);
        } catch (\Exception $e) {
            Log::create([
                'message' => 'Failed to clear cache.',
                'level' => 'warning',
                'type' => 'error',
                'ip_address' => $request->ip(),
                'context' => 'web',
                'source' => 'clear_cache',
                'extra_info' => json_encode(['user_agent' => $request->header('User-Agent'),'error'=>$e])
            ]);
            return response()->json(["status" => "error", "message" => "Failed to clear cache","error"=>$e], 500);
        }
    }
}
