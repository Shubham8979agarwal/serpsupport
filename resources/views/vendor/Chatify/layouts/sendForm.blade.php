<?php 
$makeuniqueid_01 = $lastMessage->from_id."_".$lastMessage->to_id."_@@!!";
$makeuniqueid_02 = $lastMessage->to_id."_".$lastMessage->from_id."_@@!!";
$getchatarchieve = DB::table('ch_messages')->where('myuniqueid', $makeuniqueid_01)->orwhere('myuniqueid',$makeuniqueid_02)->where('chatarchieve',"!=","NULL")->value('chatarchieve');
?>
@if($getchatarchieve!='yes')
<div class="messenger-sendCard">
    <form id="message-form" method="POST" action="{{ route('send.message') }}" enctype="multipart/form-data">
        @csrf
        <label><span class="fas fa-plus-circle"></span><input disabled='disabled' type="file" class="upload-attachment" name="file" accept=".{{implode(', .',config('chatify.attachments.allowed_images'))}}, .{{implode(', .',config('chatify.attachments.allowed_files'))}}" /></label>
        <button class="emoji-button"></span><span class="fas fa-smile"></button>
        <textarea readonly='readonly' name="message" class="m-send app-scroll" placeholder="Type a message.."></textarea>
        <button disabled='disabled' class="send-button"><span class="fas fa-paper-plane"></span></button>
    </form>
</div>
@else
<div class="alert alert-success" style="margin: 20px;">
   This chat has been archived ...
</div>
@endif
