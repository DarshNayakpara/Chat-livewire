<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
class CreateChat extends Component
{
    public $users;
    public $message="Hello How Are You";

     public function  checkconversation($receiverId)
     {
        $checkedconversation =  Conversation::where('receiver_id',auth()->user()->id)->where('sender_id',$receiverId)->orWhere('receiver_id',$receiverId)->where('sender_id',auth()->user()->id)->get();

        if(count($checkedconversation) == 0){
        //    dd("no conversation ");
        $createdConversation = Conversation::create(['sender_id'=>auth()->user()->id,'receiver_id'=>$receiverId,'last_time_message'=>now()]);
        
        $createdMessage =   Message::create(['conversation_id'=>$createdConversation->id,'sender_id'=>auth()->user()->id,'receiver_id'=>$receiverId,'body'=>$this->message]);

             $createdMessage->created_at;
            $createdConversation->save();

            dD('saved');

        }else if(count($checkedconversation) >= 1   ) {
            dd("conversation exists");
        }
     }

    public function render()
    {
        $this->users = User::where('id', '!=', auth()->id())->get();
        return view('livewire.chat.create-chat');
    }
}
