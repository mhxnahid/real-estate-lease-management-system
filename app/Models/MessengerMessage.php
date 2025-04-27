<?php

namespace App\Models;

use MessengerTopic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessengerMessage extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'topic_id',
        'sender_id',
        'content'
    ];
    protected $casts = [
        'sent_at' => 'datetime', // Use datetime casting for sent_at
    ];
    public $with = ['sender'];


    public function topic()
    {
        return $this->belongsTo(MessengerTopic::class);
    }

    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    public function unread($topic)
    {
        $user = Auth::user();
        if ($this->sender->id == $user->id) {
            return false;
        }
        $read_at = $topic->userType() . "_read_at";
        $read_at = $topic->{$read_at};
        if (! $read_at) {
            return true;
        }
        if ($this->sent_at > $read_at) {
            return true;
        }

        return false;

    }
}
