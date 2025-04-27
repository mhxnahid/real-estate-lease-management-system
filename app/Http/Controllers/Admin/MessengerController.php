<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\MessengerTopic;
use App\Http\Controllers\Controller;
use App\Services\BasicSvc;


class MessengerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Auth::user()->topics()->with('receiver', 'sender')->orderBy('sent_at', 'desc')->get();
        $title  = 'Messages';

        return view('admin.messenger.index', compact('topics', 'title'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = [];
        $user = auth()->user();
        if ($user->role->contains('id', 3)) {
            $users = BasicSvc::getLandlordsAllUsers()->pluck('name', 'id');
        } elseif($user->role->contains('id', 2)) {
            $users = BasicSvc::getTenantsAllUsers()->pluck('name', 'id');
        }

        return view('admin.messenger.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMessageRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request)
    {
        try {
            $sender = Auth::user()->id;

            MessengerTopic::create([
                'subject'     => $request->input('subject'),
                'sender_id'   => $sender,
                'receiver_id' => $request->input('receiver'),
                'sent_at'     => Carbon::now(),
            ])->read()
                ->messages()->create([
                    'sender_id' => $sender,
                    'content'   => $request->input('content'),
                ]);

            return redirect()->route('admin.messenger.index');
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return redirect()->back()->withErrors(['error' => 'Message could not be sent. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param MessengerTopic $topic
     * @return \Illuminate\Http\Response
     * @internal param MessengerTopic $topic
     * @internal param int $id
     */
    public function show(MessengerTopic $topic)
    {

        $user = Auth::user();
        if ($topic->receiver->id != $user->id && $topic->sender->id != $user->id) {
            return abort(401);
        }

        $topic->load('receiver', 'sender', 'messages');
        $unreadMessages = [];
        foreach ($topic->messages as $message) {
            if ($message->unread($topic)) {
                $unreadMessages[] = $message->id;
            }
        }
        $topic->read();

        return view('admin.messenger.show', compact('topic', 'unreadMessages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MessengerTopic $topic
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(MessengerTopic $topic)
    {
        $user = Auth::user();
        if ($topic->receiver->id != $user->id && $topic->sender->id != $user->id) {
            return abort(401);
        }
        $topic->load('receiver', 'sender');
        $user = $topic->otherPerson()->name;

        return view('admin.messenger.reply', compact('topic', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMessageRequest|Request $request
     * @param MessengerTopic $topic
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(UpdateMessageRequest $request, MessengerTopic $topic)
    {
        try {
            $user = Auth::user();
            if ($topic->receiver->id != $user->id && $topic->sender->id != $user->id) {
                return abort(401);
            }

            $topic->sent_at = Carbon::now();
            $topic->save();
            $topic->read();
            $topic->messages()->create([
                'sender_id' => Auth::user()->id,
                'content'   => $request->input('content'),
            ]);

            return redirect()->route('admin.messenger.show', $topic->id);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return redirect()->back()->withErrors(['error' => 'Message could not be updated. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MessengerTopic $topic
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @internal param int $id
     */
    public function destroy(MessengerTopic $topic)
    {
        $user = Auth::user();
        if ($topic->receiver->id != $user->id && $topic->sender->id != $user->id) {
            return abort(401);
        }

        $topic->delete();

        return redirect()->route('admin.messenger.index');
    }

    public function inbox()
    {
        $topics = Auth::user()->inbox()->with('receiver', 'sender')->orderBy('sent_at', 'desc')->get();
        $title  = 'Inbox';

        return view('admin.messenger.index', compact('topics', 'title'));
    }

    public function outbox()
    {
        $topics = Auth::user()->outbox()->with('receiver', 'sender')->orderBy('sent_at', 'desc')->get();
        $title  = 'Outbox';

        return view('admin.messenger.index', compact('topics', 'title'));
    }
}
