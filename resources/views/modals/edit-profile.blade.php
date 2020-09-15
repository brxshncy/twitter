<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> Edit Profile</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('edit-profile',$profile->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">First Name</label>
                            <input type="text" class="form-control" name="fname" value="{{ $profile->fname }}">
                        </div>
                    </div>
                    <div class="col">
                        <label for="">Last Name</label>
                        <input type="text" class="form-control" name="lname" value="{{ $profile->lname }}">
                    </div>
                </div>
             <div class="row">
                 <div class="col">
                     <div class="form-group">
                         <label for="">Address</label>
                         <input type="text" class="form-control" name="address" value="{{ $profile->address }}">
                     </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col">
                     <div class="form-group">
                        <label for="">Contact</label>
                        <input type="text" class="form-control" name="contact" value="{{ $profile->contact }}">
                     </div>
                    
                 </div>
                 <div class="col">
                    <label for="">Birthdate</label>
                    <input type="date" class="form-control" name="bday" value="{{ $profile->bday }}">
                </div>
             </div>
             <div class="row">
                 <div class="col">
                    <div class="form-group">
                      <label for="">Bio</label>
                      <input type="text" class="form-control" name="bio" value="{{ $profile->bio }}">
                    </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col">
                     <div class="form-group">
                        <label for="">Profile Picture</label>
                        <input type="file" name="profile_pic" id="" class="form-control" value="{{ $profile->profile_pic }}">
                     </div>
                 </div>
             </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Edit</button>
            </div>
        </form>
      </div>
    </div>
  </div>