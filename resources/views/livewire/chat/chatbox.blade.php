<div>
    {{-- Stop trying to control. --}}
    

    @if($selectedConversation)
        
    <div class="chatbox_header">

            <div class="return">
                <i class="bi bi-arrow-left"></i>
            </div>

            <div class="img_container">
                <img src="https://ui-avatars.com/api/?name={{$receiverInstance->name}}" alt="">

            </div>


            <div class="name">
                {{$receiverInstance->name}}
            </div>


            <div class="info">

                <div class="info_item">
                    <i class="bi bi-telephone-fill"></i>
                </div>

                <div class="info_item">
                    <i class="bi bi-image"></i>
                </div>

                <div class="info_item">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
            </div>
        </div>

        <div class="chatbox_body">
           
            @foreach ($messages as $message )
                
            <div class="msg_body  {{auth()->id() == $message->sender_id ? 'msg_body_me' :'msg_body_receiver' }}" style="width:80%;max-width:80%;max-width:max-content">
                    {{$message->body}}
                <div class="msg_body_footer">
                    <div class="date">
                       {{$message->created_at->format('m: i a')}}
                    </div>

                    <div class="read">
                        @php
                            if($message->user->id === auth()->id())
                            {
                               if($message->read == 0)
                               {
                                echo "<i class='bi  bi-check2 status_tick'></i>";
                            } else {
                                echo "<i class='bi  bi-check2-all text-primary '></i>";
                                
                            }
                        }
                        @endphp
                        

                    </div>
                </div>
            </div>
            @endforeach
            

        </div>
        <script>
            $('.chatbox_body').on('scroll',function(){
                var top = $('.chatbox_body').scrollTop();
                // console.log(top);
                if(top == 0){
                    // console.log("in");
                     window.livewire.emit('loadmore');
                }
            });

            
        </script>
        <script>
            window.addEventListener('updatedHeight',event=>{
                let old = event.detail.height;
                let newHeight = $('.chatbox_body')[0].scrollHeight;
                
                let height = $('.chatbox_body').scrollTop(newHeight - old);
                 height = $('.chatbox_body')[0].scrollHeight;
                
    
                window.livewire.emit('currentHeight',{
                                height:height,
                });
            })
        </script>
    @else
    No Conversation Selected
    @endif
    
        

        <script>
            window.addEventListener('rowChatToBottom',event=>{

                $('.chatbox_body').scrollTop($('.chatbox_body')[0].scrollHeight);
            });
            

</script>

<script>
$(document).on('click','.return',function(){

    window.livewire.emit('resetComponent');

});
</script>

<script>

window.addEventListener('markMessageAsRead',event=>{
var value = document.querySelectorAll('.status_tick');
value.array.forEach(element => {
    element.classList.remove('bi bi-check2');
    element.classList.add('bi bi-check2-all','teat-primary');
});
});

</script>
</div>
