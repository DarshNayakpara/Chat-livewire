<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\TestingEvent;
use App\Events\OrderShipped;
class SendMessage extends Component
{
    public $selectedConversation;
    public $receiverInstance;
    public $body;
    public  $createdMessage;
    protected $listeners = ['updateSendMessage','dispatchSendMessage','resetComponent'];

     public function resetComponent(){
        $this->selectedConversation = null;
        $this->receiverInstance = null;
    }
    public function sendMessage(){
        if($this->body == null){
            return null;
        }

        $createdMessage = Message::create([
            'conversation_id'=>$this->selectedConversation->id,
            'sender_id'=>auth()->id(),
            'receiver_id'=>$this->receiverInstance->id,
            'body'=>$this->body,

        ]);
        $this->createdMessage = $createdMessage;
        $this->selectedConversation->last_time_message = $this->createdMessage->created_at;
        $this->selectedConversation->save();


       
        $this->emitTo('chat.chatbox','pushMessage',$this->createdMessage->id);
        // refresh conversation list
        $this->emitTo('chat.chat-list','refreshConversationList');
      
        $this->reset('body');
        $this->emitSelf('dispatchSendMessage');
        
     

    }

    function updateSendMessage(Conversation $conversation,User $receiver)
    {
        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;
    }
    
    function dispatchSendMessage()
    {
        event(new MessageSent(Auth()->user(),$this->createdMessage,$this->selectedConversation,$this->receiverInstance));

        event(new OrderShipped('Hello Worldssssssssssssssssssssss'));
    }
    public function render()
    {
        return view('livewire.chat.send-message');
    }

}
