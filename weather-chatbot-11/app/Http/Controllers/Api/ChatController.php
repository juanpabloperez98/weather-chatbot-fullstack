<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Throwable;

class ChatController extends Controller
{
    public function __construct(private readonly ChatService $chat)
    {
    }
    
    public function send(StoreMessageRequest $request): JsonResponse
    {
        try {
            $conversation = $request->input('conversation_id')
                ? Conversation::findOrFail($request->integer('conversation_id'))
                : Conversation::create(['title' => 'Chat nuevo']);

            $assistantMsg = $this->chat->handleUserMessage(
                $conversation,
                $request->string('text')->toString(),
                $request->input('location')
            );

            return response()->json([
                'conversation' => new ConversationResource($conversation->load('messages')),
                'message' => new MessageResource($assistantMsg),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error interno del servidor',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    public function history(): JsonResponse
    {
        try {
            $conversations = Conversation::withCount('messages')
                ->orderBy('updated_at', 'desc')
                ->get(['id', 'title', 'updated_at', 'created_at']);

            return response()->json([
                'success' => true,
                'conversations' => $conversations,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo historial de conversaciones',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $conversation = Conversation::with('messages')->findOrFail($id);

            return response()->json([
                'success' => true,
                'conversation' => new ConversationResource($conversation),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo la conversación',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}