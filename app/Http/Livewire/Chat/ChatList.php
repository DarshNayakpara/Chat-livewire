<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
class ChatList extends Component
{
    public $auth_id;
    public $conversation;
    public $receiverInstance;
    public $name;
    public $selectedConversation;

    protected $listeners= ['chatUserSelected','refreshConversationList' => '$refresh','resetComponent'];

    public function resetComponent(){
        $this->selectedConversation = null;
        $this->receiverInstance = null;
    }

    public function chatUserSelected(Conversation $conversation,$receiverId)
    {
        // dD($conversation,$receiverId);
       $this->selectedConversation= $conversation;

       $receiverInstance  = User::find($receiverId);

       
       // update all Message as Read when user click on conversation
      
       $this->emitTo('chat.chatbox','loadConversation', $this->selectedConversation,$receiverInstance);
       

        $this->emitTo('chat.send-message','updateSendMessage',$this->selectedConversation,$receiverInstance);
    
    }



    public function getChatUserInstance(Conversation $conversation,$request)
    {
        $this->auth_id = auth()->user()->id;
        if($conversation->sender_id == $this->auth_id){
            $this->receiverInstance = User::firstWhere('id', $conversation->receiver_id);

        }else
        {
            $this->receiverInstance = User::firstWhere('id', $conversation->sender_id);

        }

        if(isset($request))
        {
            return $this->receiverInstance->$request;
        }
    }
    public function mount()
    {

        $this->auth_id = auth()->user()->id;
        $this->conversation = Conversation::where('sender_id',$this->auth_id)->orWhere('receiver_id',$this->auth_id)->orderBy('last_time_message','DESC')->get();  
    }
    public function render()
    {
        return view('livewire.chat.chat-list');
    }
}
