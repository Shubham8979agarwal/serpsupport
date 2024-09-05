{{-- user info and avatar --}}
<?php
$currentUrl = url()->current();
$lastSegment = request()->segment(count(request()->segments()));
$chat_id = $lastSegment . "_" . Auth::user()->id;
$acceptedby_to = DB::table('submitlinks')
    ->where('chat_id', $chat_id)
    ->value('acceptedby_to');
$chat_status = DB::table('submitlinks')
    ->where('chat_id', $chat_id)
    ->value('chat_status');    
?>
<div class="avatar av-l chatify-d-flex"></div>
<!-- <p class="info-name">{{ config('chatify.name') }}</p> -->
<p></p>
@if($acceptedby_to==Auth::user()->id && $chat_status!="closed")
<div class="messenger-infoView-btns">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
   <div class="container">
      <!-- Button to Open the Modal -->
      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
      Submit Link Details
      </button>
      <!-- The Modal -->
      <div class="modal fade" id="myModal">
         <div class="modal-dialog">
            <div class="modal-content">
               <!-- Modal Header -->
               <div class="modal-header">
                  <h4 class="modal-title">Submit Link Details</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>
               <!-- Modal body -->
               <form action="{{ route('submitlinkdetails') }}" method="post">
               @csrf
               <div class="modal-body">
                  Select type of link: 
                  <label class="checkbox-inline">
                  <input type="checkbox" name="typeoflink" class="link-checkbox" value="Niche edits"> Niche edits
                  </label>
                  <label class="checkbox-inline">
                  <input type="checkbox" name="typeoflink" class="link-checkbox" value="Guest post"> Guest post
                  </label>
                  <label class="checkbox-inline">
                  <input type="checkbox" name="typeoflink" class="link-checkbox" value="Image link"> Image link
                  </label>
                  @error('typeoflink')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
                  <div class="form-group row">
                     <label for="inputEmail3" class="col-sm-2 col-form-label">Outlink on:</label>
                     <div class="col-sm-10">
                        <input type="text" class="form-control" name="outlink_on" placeholder="Add URL here" autocomplete="off" value="{{ old('outlink_on') }}">
                        @error('outlink_on')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="inputPassword3" class="col-sm-2 col-form-label">Backlink on: </label>
                     <div class="col-sm-10">
                        <input type="text" class="form-control" name="backlink_on" placeholder="Add URL here" autocomplete="off" value="{{ old('backlink_on') }}">
                        @error('backlink_on')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="inputPassword3" class="col-sm-2 col-form-label">Anchor text</label>
                     <div class="col-sm-10">
                        <input type="text" class="form-control" name="anchor_text" placeholder="Add anchor text here" autocomplete="off" value="{{ old('anchor_text') }}">
                        @error('anchor_text')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                  </div>
                  Outlink placed on your website?
                  <label class="checkbox-inline">
                  <input type="checkbox" name="outlink_placed_on_your_website" value="Yes" class="link-checkbox02"> Yes
                  </label>
                  <label class="checkbox-inline">
                  <input type="checkbox" name="outlink_placed_on_your_website" value="No" class="link-checkbox02"> No
                  </label>
                  @error('outlink_placed_on_your_website')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
               <div class="form-group row">
                  <div class="col-sm-12">
                     <input type="hidden" name="chat_id" value="{{$chat_id}}">
                     <button type="submit" class="btn btn-success mb-3">Submit Link Details</button>
                  </div>
               </div>
            </form>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="messenger-infoView-btns">
   <a href="#" class="danger delete-conversation">Delete Conversation</a>
</div>
@elseif($acceptedby_to==Auth::user()->id && $chat_status=="closed")
<div class="alert alert-success" style="margin: 20px;">
   Link Details Submitted Successfully
</div>
<script>
    document.querySelectorAll('.link-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('.link-checkbox').forEach(otherCheckbox => {
                    if (otherCheckbox !== this) {
                        otherCheckbox.checked = false;
                    }
                });
            }
        });
    });
    document.querySelectorAll('.link-checkbox02').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('.link-checkbox02').forEach(otherCheckbox => {
                    if (otherCheckbox !== this) {
                        otherCheckbox.checked = false;
                    }
                });
            }
        });
    });
</script>
@endif
{{-- shared photos --}}
<!-- <div class="messenger-infoView-shared">
   <p class="messenger-title"><span>Shared Photos</span></p>
   <div class="shared-photos-list"></div>
</div> -->