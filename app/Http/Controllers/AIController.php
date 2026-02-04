<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AIServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIController extends Controller
{
    protected AIServiceInterface $aiService;

    public function __construct(AIServiceInterface $aiService)
    {
        $this->aiService = $aiService;
    }

    public function ask(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $user = Auth::user();
        $roles = $user->getRoleNames()->implode(', ');
        $context = [
            'user_name' => $user->name,
            'active_unit' => session('active_unit_name', 'Genel'),
            'roles' => $roles ?: 'Standart Kullanıcı',
        ];

        $answer = $this->aiService->ask($request->message, $context);

        return response()->json(['answer' => $answer]);
    }
}