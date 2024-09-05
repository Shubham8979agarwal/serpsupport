{{-- -------------------- Saved Messages -------------------- --}}

@if($get == 'saved')
    <table class="messenger-list-item" data-contact="{{ Auth::user()->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
                <div class="saved-messages avatar av-m">
                    <span class="far fa-bookmark"></span>
                </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ Auth::user()->id }}" data-type="user">Saved Messages <span>You</span></p>
                <span>Save messages secretly</span>
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- Contact list -------------------- --}}
<?php 
$makeuniqueid = $lastMessage->from_id."_".$lastMessage->to_id."_@@!!";
$getchatarchieve = DB::table('ch_messages')->where('myuniqueid', $makeuniqueid)->value('chatarchieve');
?>
@if($get == 'users' && !!$lastMessage && $getchatarchieve!='yes')
<?php
$lastMessageBody = mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8');
$lastMessageBody = strlen($lastMessageBody) > 30 ? mb_substr($lastMessageBody, 0, 30, 'UTF-8').'..' : $lastMessageBody;
echo "<pre>";
print_r($lastMessage->chatarchieve);
echo "</pre>";
?>
<a href="/chat/{{$user->id}}">
<table class="messenger-list-item" data-contact="{{ $user->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td style="position: relative">
            @if($user->active_status)
                <span class="activeStatus"></span>
            @endif
            <div class="avatar av-m"
            style="background-image: url('{{ $user->avatar }}');">
            </div>
        </td>
        {{-- center side --}}
        <!--  -->
        <td>
        
            <p>
                {{ $lastMessage->forwhich_user_url }} <i class="fa fa-exchange"></i> {{ $lastMessage->website_url }}
            </p>
            
            <span>
                {{-- Last Message user indicator --}}
                {!!
                    $lastMessage->from_id == Auth::user()->id
                    ? '<span class="lastMessageIndicator">You :</span>'
                    : ''
                !!}
                {{-- Last message body --}}
                @if($lastMessage->attachment == null)
                {!!
                    $lastMessageBody
                !!}
                @else
                <span class="fas fa-file"></span> Attachment
                @endif
            </span>
            {{-- New messages counter --}}
                {!! $unseenCounter > 0 ? "<b>".$unseenCounter."</b>" : '' !!}
        </td>
    </tr>
</table>
</a>
@endif
{{-- -------------------- Search Item -------------------- --}}
@if($get == 'search_item')
<table class="messenger-list-item" data-contact="{{ $user->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td>
        <div class="avatar av-m"
        style="background-image: url('{{ $user->avatar }}');">
        </div>
        </td>
        {{-- center side --}}
        <td>
            <p data-id="{{ $user->id }}" data-type="user">
            {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
        </td>

    </tr>
</table>
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if($get == 'sharedPhoto')
<div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif
