<?php

namespace App\Http\Controllers\Api\Admin;

use App\Events\ConversationSaved;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConversationRequest;
use App\Http\Requests\UpdateConversationRequest;
use App\Models\Conversation;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conversation = Conversation::with(['sentBy'])->latest()->paginate();

        if ($conversation->isEmpty()) {
            return ApiResponse::error([], 'conversations not found', 404);
        }

        return ApiResponse::success($conversation, 'successful', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConversationRequest $request)
    {
        $data = $request->validated();
        $data['sent_by'] = $request->user()?->id;
        $data['reason'] = $data['reason'] ?? 'reminder';
        $conversation = Conversation::create($data);

        if (!$conversation) {
            return ApiResponse::error([], 'Something went wrong!', 403);
        }

        // Dispatches the event
        event(new ConversationSaved($conversation));

        return ApiResponse::success($conversation, 'message sent!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        // Check if the membership exists
        if (!$conversation) {
            return ApiResponse::error([], 'Conversation not found', 404);
        }

        $conversation->load(['sentBy']);
        $conversation['users_sent_to'] = $conversation->sentTo();

        // Return success response
        return ApiResponse::success($conversation, 'Conversation retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateConversationRequest $request, Conversation $conversation)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        // Check if the conversation exists
        if (!$conversation) {
            return ApiResponse::error([], 'Conversation not found', 404);
        }

        // Delete the conversation
        $conversation->delete();

        // Return success response
        return ApiResponse::success([], 'Conversation deleted successfully.', 200);
    }
}
