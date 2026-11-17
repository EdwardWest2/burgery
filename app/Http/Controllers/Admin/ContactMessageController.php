<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $messages = ContactMessage::query()
            ->when($search !== '', function ($q) use ($search) {
                $like = '%'.addcslashes($search, '%_\\').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('subject', 'like', $like)
                    ->orWhere('message', 'like', $like));
            })
            ->when(array_key_exists($status, ContactMessage::STATUSES), fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.messages.index', [
            'messages' => $messages,
            'filters' => compact('search', 'status'),
        ]);
    }

    public function show(ContactMessage $message)
    {
        if ($message->status === 'new') {
            $message->update(['status' => 'read']);
        }

        return view('admin.messages.show', ['message' => $message]);
    }

    public function update(Request $request, ContactMessage $message)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(ContactMessage::STATUSES))],
        ]);

        $message->update($data);

        return back()->with('success', 'Status pesan diperbarui.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Pesan dihapus.');
    }
}
