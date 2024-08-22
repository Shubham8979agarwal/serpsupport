{{-- user info and avatar --}}
<div class="avatar av-l chatify-d-flex"></div>
<!-- <p class="info-name">{{ config('chatify.name') }}</p> -->
<p><?php $website_url = session('website_url'); ?> {{ $website_url }}</p>
<div class="messenger-infoView-btns">
    <a href="#" class="btn btn-success">Submit Link Details</a>
</div>
<div class="messenger-infoView-btns">
    <a href="#" class="danger delete-conversation">Delete Conversation</a>
</div>
{{-- shared photos --}}
<div class="messenger-infoView-shared">
    <p class="messenger-title"><span>Shared Photos</span></p>
    <div class="shared-photos-list"></div>
</div>
