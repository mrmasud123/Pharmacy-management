<?php

namespace App\Http\Controllers\Admin;

use App\Ai\Agents\AiChatbot;
use App\Ai\Tools\ListAllMedicineTools;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use Laravel\Ai\Agent;
use Laravel\Ai\Facades\AI;

class AIChatbotController extends Controller
{
    public function index(){
        return view('admin.chatbot.chat');
    }

    public function continueChat(Request $request)
    {
        $validatedData = $request->validate([
            'prompt' => 'required'
        ]);

        try{
            $response= (new AiChatbot())->prompt($validatedData['prompt']);

            return response()->json([
                'received' =>(string) $response,
                'all' => $request->all()
            ]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
