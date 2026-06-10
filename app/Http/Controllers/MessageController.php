<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageReply;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // ── User: submit contact form ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'nullable|string|max:150',
            'body'    => 'required|string|max:3000',
        ]);

        $data['user_id']   = auth('web')->id();
        $data['user_read']  = true;
        $data['admin_read'] = false;

        Message::create($data);

        return back()->with('success', 'Your message has been sent! We\'ll get back to you soon.');
    }

    // ── User: view own messages list ──────────────────────────────────────────
    public function userIndex()
    {
        $messages = Message::where('user_id', auth('web')->id())
            ->withCount('replies')
            ->with('replies')
            ->latest()
            ->get();

        return view('messages.index', compact('messages'));
    }

    // ── User: view single thread ──────────────────────────────────────────────
    public function userThread(Message $message)
    {
        abort_unless($message->user_id === auth('web')->id(), 403);

        // Mark unread reply badge as cleared
        if ($message->has_unread_reply) {
            $message->update(['has_unread_reply' => false]);
        }

        $message->load('replies');

        return view('messages.thread', compact('message'));
    }

    // ── User: reply in thread ─────────────────────────────────────────────────
    public function userReply(Request $request, Message $message)
    {
        abort_unless($message->user_id === auth('web')->id(), 403);

        $request->validate(['body' => 'required|string|max:3000']);

        MessageReply::create([
            'message_id' => $message->id,
            'user_id'    => auth('web')->id(),
            'is_admin'   => false,
            'body'       => $request->body,
        ]);

        // Mark as unread for admin
        $message->update(['admin_read' => false]);

        return back()->with('success', 'Reply sent.');
    }

    // ── Admin: list all messages ──────────────────────────────────────────────
    public function adminIndex(Request $request)
    {
        $query = Message::with('user')->latest();

        if ($request->filled('filter')) {
            match ($request->filter) {
                'unread'   => $query->where('admin_read', false),
                'replied'  => $query->whereHas('replies', fn($q) => $q->where('is_admin', true)),
                default    => null,
            };
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%")
                ->orWhere('subject', 'like', "%$s%"));
        }

        $messages     = $query->paginate(20)->withQueryString();
        $unreadCount  = Message::where('admin_read', false)->count();

        return view('admin.messages', compact('messages', 'unreadCount'));
    }

    // ── Admin: view + reply in thread ─────────────────────────────────────────
    public function adminThread(Message $message)
    {
        $message->update(['admin_read' => true]);
        $message->load('replies.user');

        return view('admin.message-thread', compact('message'));
    }

    // ── Admin: reply ──────────────────────────────────────────────────────────
    public function adminReply(Request $request, Message $message)
    {
        $request->validate(['body' => 'required|string|max:3000']);

        MessageReply::create([
            'message_id' => $message->id,
            'user_id'    => null,
            'is_admin'   => true,
            'body'       => $request->body,
        ]);

        // Notify user of unread reply
        $message->update([
            'admin_read'       => true,
            'has_unread_reply' => true,
        ]);

        return back()->with('success', 'Reply sent to user.');
    }

    // ── Admin: delete message ─────────────────────────────────────────────────
    public function adminDestroy(Message $message)
    {
        $message->delete();
        return back()->with('success', 'Message deleted.');
    }
}
