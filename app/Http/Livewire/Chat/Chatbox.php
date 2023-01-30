<?php

namespace App\Http\Livewire\Chat;   


use Livewire\Component;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\TestingEvent;
use App\Events\OrderShipped;
use App\Events\MessageRead;
class Chatbox extends Component
{
    public $selectedConversation;
    public $receiverInstance;
    public $messages;
    public $messages_count;
    public $paginateVar = 10;
    public $height;
    public $auth_id;
    // protected $listeners = ['loadConversation','pushMessage','loadmore','currentHeight'];

    

    public function getListeners(){

        $auth_id = auth()->user()->id;
        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived', 
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastMessageRead',  
            'loadConversation', 'pushMessage', 'loadmore', 'updateHeight','broadMessageRead' , 'resetComponent'
        ];
    }
    public function resetComponent(){
        $this->selectedConversation = null;
        $this->receiverInstance = null;
    }
    // public function getListeners()
    // {
    //     return [
        // "echo:orders,OrderShipped"=>'somefunction',
    //         "echo:orders,OrderShipped" => 'notifyNewOrder','loadConversation','pushMessage','loadmore','currentHeight'
    //     ];
    // }
    public function broadcastMessageRead($event){
        
        if($this->selectedConversation){



            if((int) $this->selectedConversation->id === (int) $event['conversation_id']){

                $this->dispatchBrowserEvent('markMessageAsRead');
            }

        }
    }

    public function broadcastedMessageReceived($event)
    {
        $this->emitTo('chat.chat-list','refreshConversationList');
       
        $broadcastedMessage = Message::find($event['message']);

        if($this->selectedConversation)
        {
            if((int)$this->selectedConversation->id === (int)$event['conversation_id'])
            {

                $broadcastedMessage->read = 1;
                $broadcastedMessage->save();
                $this->pushMessage($broadcastedMessage->id);

                $this->emitSelf('broadMessageRead');
            }
        }

    }
    public function broadMessageRead(){
        event(new MessageRead($this->selectedConversation->id,$this->receiverInstance->id));
    }
//    public function somefunction($event){
//         dd($event);
//    }

    public function pushMessage($messageId)
    {
        $message = Message::find($messageId);
        $this->messages->push($message);

        $this->dispatchBrowserEvent('rowChatToBottom');
    }
    public function loadmore(){
       $this->paginateVar = $this->paginateVar + 10;
       $this->messages = Message::where('conversation_id',$this->selectedConversation->id)->skip($this->messages_count - $this->paginateVar)
        ->take($this->paginateVar)->get();
        $height = $this->height; 
        $this->dispatchBrowserEvent('updatedHeight',($height));

    }

    public function currentHeight($height){
        // $this->dispatchBrowserEvent('scrollToHeight', ['height' => $height]);
        $this->height = $height;


    }

    public function loadConversation(Conversation $conversation,User $receiver)
    {
        $auth_id = auth()->user()->id;
        $this->auth_id = $auth_id;
        // dd($conversation,$receiver);
        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;
        $this->messages_count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $this->messages = Message::where('conversation_id',$this->selectedConversation->id)->skip($this->messages_count - $this->paginateVar)
        ->take($this->paginateVar)->get();

        $this->dispatchBrowserEvent('chatSelected');

        Message::where('conversation_id',$this->selectedConversation->id)->where('receiver_id',$this->auth_id)->update(['read'=>1]);

        $this->emitSelf('broadMessageRead');
         $this->emitTo('chat.chat-list','refreshConversationList');
    }
    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
