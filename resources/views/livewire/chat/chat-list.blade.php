<div>
    {{-- Care about people's approval and you will be their prisoner. --}}

    <div class="chatlist_header">

        <div class="title">
            Chat
        </div>

        <div class="img_container">
            <img src="https://ui-avatars.com/api/?background=0D8ABC&color=fff&name={{auth()->user()->name}}" alt="">
        </div>
    </div>

    <div class="chatlist_body">

        @if (count($conversation) > 0)
            
            @foreach ($conversation as $item)

                <div class="chatlist_item " wire:key='{{$item->id}}'wire:click="$emit('chatUserSelected',{{$item}},{{$this->getChatUserInstance($item,$name='id')}})">
                    <div class="chatlist_img_container">

                        <img src="https://ui-avatars.com/api/?name={{$this->getChatUserInstance($item,$name='name')}}"
                            alt="">
                    </div>

                    <div class="chatlist_info">
                        <div class="top_row">
                            <div class="list_username">
                             {{$this->getChatUserInstance($item,$name='name')}}
                            </div>
                            <span class="date">
                                 {{$item->messages->last()->created_at->shortAbsoluteDiffForHumans()}}
                                </span>
                        </div>

                        <div class="bottom_row">

                            <div class="message_body text-truncate">
                              {{$item->messages->last()->body}}
                            </div>
                            
                            {{-- <div class="unread_count badge rounded-pill text-light bg-danger">

                                56
                            </div> --}}
                           
                            @php
                                if(count($item->messages->where('read',0)->where('receiver_id',Auth()->user()->id))){

                             echo ' <div class="unread_count badge rounded-pill text-light bg-danger">  '
                                 . count($item->messages->where('read',0)->where('receiver_id',Auth()->user()->id)) .'</div> ';

                                }

                            @endphp
                        </div>
                    </div>
                </div>
            @endforeach
                @else

                You have no conversation yet
 
                @endif




       

    </div>
</div>
