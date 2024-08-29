@include('frontend.dashboard.common.header')
@include('Chatify::layouts.headLinks')
<?php
    // Capture the current URL
    $currentUrl = url()->current();
?>
<div class="messenger">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView {{ !!$id ? 'conversation-active' : '' }}">
        {{-- Header and search bar --}}
        <div class="m-header">
            <nav>
                <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">MESSAGES</span> </a>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#"><i class="fas fa-cog settings-btn"></i></a>
                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                </nav>
            </nav>
            {{-- Search input --}}
            <!-- <input type="text" class="messenger-search" placeholder="Search" /> -->
            {{-- Tabs --}}
            {{-- <div class="messenger-listView-tabs">
                <a href="#" class="active-tab" data-view="users">
                    <span class="far fa-user"></span> Contacts</a>
            </div> --}}
        </div>
        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
           {{-- Lists [Users/Group] --}}
           {{-- ---------------- [ User Tab ] ---------------- --}}
           <div class="show messenger-tab users-tab app-scroll" data-view="users">
               {{-- Favorites --}}
               <div class="favorites-section">
                <!-- <p class="messenger-title"><span>Favorites</span></p> -->
                <div class="messenger-favorites app-scroll-hidden" style="display: none;"></div>
               </div>
               
               {{-- Contact --}}
               <p class="messenger-title"><span>All Messages</span></p>
               <div class="listOfContacts" style="width: 100%;height: calc(100% - 272px);position: relative;">
               </div>
           </div>
             {{-- ---------------- [ Search Tab ] ---------------- --}}
           <div class="messenger-tab search-tab app-scroll" data-view="search">
                {{-- items --}}
                <p class="messenger-title"><span>Search</span></p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Type to search..</span></p>
                </div>
             </div>
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] amd buttons --}}
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                {{-- header back button, avatar and user name --}}
                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                    <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                    </div>
                    <!-- <div class="messenger-messagingView"> -->

                        <p>
                            {{ $lastMessage->forwhich_user_url }} <i class="fa fa-exchange"></i> {{ $lastMessage->website_url }}
                        </p>
                        <!-- @if($get == 'users' && !!$lastMessage)
                        @if($lastMessage->from_id==Auth::user()->id)
                        <p>
                            {{ $lastMessage->website_url }}
                        </p>
                        @elseif($lastMessage->to_id==Auth::user()->id)
                        <p>
                            {{ $lastMessage->forwhich_user_url }}
                        </p>
                        @endif
                        @else
                            <p>SERPsupport Messenger</p>
                        @endif -->

                        
                    <!-- </div> -->
                    <?php 
                    /*$previousUrl = url()->previous();
                    $parts = explode('/', $previousUrl);
                    $segment = isset($parts[3]) ? $parts[3] : '';  // Check if the segment exists
                    $currentUrl = url()->current();

                    if (!empty($segment) && $segment == 'backlinks' && strpos($currentUrl, '_') === false) {
                        $ls = request()->segment(count(request()->segments()));  
                        $parts = explode('_', $ls);

                        if (isset($parts[1])) {  // Check if the array key 1 exists
                            $id = $parts[1];

                            $getWebsiteName = DB::table('websites')
                                ->where('user_id', $id)
                                ->select('website_url')->pluck('website_url')
                                ->first();

                            if (is_null($getWebsiteName)) {
                                return redirect('account-settings');  // Redirect if $getWebsiteName is null
                            }

                            echo '<a href="' . url()->current() . '" class="">' . $getWebsiteName . '</a>';
                        } else {
                            // Handle missing key (redirect or show an error)
                            return redirect('account-settings')->with('error', 'Invalid URL structure.');
                        }

                    } elseif (!empty($segment) && $segment == 'outlinks' && strpos($currentUrl, '_') === false) {
                        $currentUrl = url()->current();
                        $ls = request()->segment(count(request()->segments()));  
                        $parts = explode('_', $ls);

                        if (isset($parts[0])) {  // Check if the array key 0 exists
                            $id = $parts[0];

                            $getWebsiteName = DB::table('websites')
                                ->where('user_id', $id)
                                ->select('website_url')->pluck('website_url')
                                ->first();

                            if (is_null($getWebsiteName)) {
                                return redirect('account-settings');  // Redirect if $getWebsiteName is null
                            }

                            echo '<a href="' . url()->current() . '" class="">' . $getWebsiteName . '</a>';
                        } else {
                            // Handle missing key (redirect or show an error)
                            return redirect('account-settings')->with('error', 'Invalid URL structure.');
                        }
                    }elseif(strpos($currentUrl, '_') !== false){
                        echo "SERPsupport Messenger";
                    }*/
                    ?>
                </div>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <!-- <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    <a href="/"><i class="fas fa-home"></i></a> -->
                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                </nav>
            </nav>
            {{-- Internet connection --}}
            <div class="internet-connection">
                <span class="ic-connected">Connected</span>
                <span class="ic-connecting">Connecting...</span>
                <span class="ic-noInternet">No internet access</span>
            </div>
        </div>

        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <div class="message">
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    <div class="messenger-infoView app-scroll">
        {{-- nav actions --}}
        <nav>
            <p>User Details</p>
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        {!! view('Chatify::layouts.info')->render() !!}
    </div>
</div>

@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')
@include('frontend.dashboard.common.footer')

