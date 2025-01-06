<div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" id="share-doc" method="post" action>
            <div class="mb-3">
                <label for="" class="">Document Type</label>
                <div class="">
                    <select name="doc_type" id="doc_type" class="form-select" required>
                        <option value=""></option>
                        <option value="1">Proposal Form</option>
                        <option value="2">Mandate Form</option>
                        <option value="3">Terms and Conditions</option>
                    </select>
                </div>

            </div>
            <div class="mb-3">
                <label for="" class="">Share Document via</label>
                <div class="">
                    <select name="delivery" id="delivery" class="form-select" required>
                        <option value=""></option>
                        <option value="SMS">SMS</option>
                        <option value="EMAIL">EMAIL</option>
                    </select>

                </div>
            </div>
            <br>
            <div id="live1" style="display: none;">
                <div class="panel-heading">

                    <h4 class="panel-title">Send via SMS</h4>

                </div><br>
                <div class="mb-3">
                    <label for="" class="">Name</label>
                    <div class="">
                        <input name="name" id="name" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="" class="">Mobile Number</label>
                    <div class="">
                        <input name="phone_no" id="phone_no" class="form-control">
                    </div>
                </div>
                {{-- <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9 col-md-offset-1 col-md-10">
                        
                        <input type="submit" class="btn btn-success" name="btnSend" value="Send">
                    </div>
                </div> --}}
            </div>
            <div class="mailbox-compose" id="live2" style="display: none;">
                <div class="panel-heading">
                    <h4 class="panel-title">Send via Email</h4>
                </div>
                <div class="form-group form-group-invisible mb-3">
                    <label for="to" class="control-label-invisible">To:</label>
                    <div class="col-sm-offset-2 col-sm-9 col-md-offset-1 col-md-10">
                        <input id="to" type="email" name="to" class="form-control form-control-invisible" data-role="tagsinput" data-tag-class="label label-primary" value="{{$document_application->email}}">
                    </div>
                </div>

            
                {{-- <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9 col-md-offset-1 col-md-10">
                        <input type="hidden" name="id" value="">
                        <input type="submit" class="btn btn-success" name="btnSend" value="Send">
                    </div>
                </div> --}}
                

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                <input type="hidden" name="id" value="{{$document_application->id}}">
            </div>
        </form>
    </div>
  </div>