<div class="panel panel-inverse">
    <div class="panel-heading">

        <h4 class="panel-title">Update Request <strong><?= $complaints->request_no ?> >> <?= $complaints->tbl_complaints_categories->name ?></strong> </h4>
    </div>
    <div class="panel-body">
        <div class="panel panel-inverse">

            <div class="panel-body">
                <form class="form-horizontal" id="update-finalForm" method="post" action>

                   
                    <div class="mb-3">
                        <label>Level</label>
                        <div class="input-group input-group-icon">
                            <select name="level_id" id="assign_to" data-plugin-selectTwo class="form-select populate input-lg">
                                <option value=""></option>
                                    <?php 
                                    foreach ($levels as $level) : ?>
                                    <option value="<?= $level['id'] ?>">
                                    <?= $level['name'] ?></option>
                            <?php endforeach ?>
                            </select>

                        </div>
                    </div>
                    <div class="mb-3">
                        <label>How Resolved</label>
                        <div class="input-group input-group-icon">
                            <select name="how_resolved_id" id="assign_to" data-plugin-selectTwo class="form-select populate input-lg">
                                <option value=""></option>
                               
                                    <?php 
                                foreach ($resolves as $resolve) : ?>
                                <option value="<?= $resolve['id'] ?>">
                                    <?= $resolve['name'] ?></option>
                            <?php endforeach ?>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-9 col-md-offset-1 col-md-10">
                            <input type="hidden" name="id" value="{{$req}}">
                            {{-- <input type="submit" class="btn btn-success" name="btnUpdateFinal" value="Submit"> --}}
                        </div>
                    </div>

                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-success waves-effect waves-light">Save Changes</button>
                    </div>


                </form>
            </div>



        </div>

    </div>

</div>