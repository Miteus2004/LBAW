<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AnswerNotification;
use App\Models\AnswerCommentNotification;
class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index()
    {
        $user = auth()->user();

        $answerNotifications = $user->answerNotifications()->with('answer.question')->get();

        $commentNotifications = $user->commentNotifications()->with('comment.answer.question')->get();

        return view('notifications.index', [
            'answerNotifications' => $answerNotifications,
            'commentNotifications' => $commentNotifications,
        ]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Request $request)
    {
        if ($request->type === 'answer') {
            $notification = AnswerNotification::findOrFail($request->id);
        } else {
            $notification = AnswerCommentNotification::findOrFail($request->id);
        }

        $this->authorize('delete', $notification);

        $notification->delete();

        return redirect()->back();
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll()
    {
        $user = auth()->user();

        $user->answerNotifications()->delete();
        $user->commentNotifications()->delete();

        return redirect()->back();
    }
}