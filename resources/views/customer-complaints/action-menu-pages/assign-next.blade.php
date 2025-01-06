<div class="row">
    <!-- begin panel -->
    <div class="panel panel-inverse">
        <div class="panel-heading">

            <h4 class="panel-title mb-4">Assign Request <strong><?= $complaints->request_no ?> >>
                    <?= $complaints->tbl_complaints_categories?->name ?> >> SLA :
                    <?= $complaints->tbl_complaints_categories?->duration_num . ' ' . $complaints->tbl_complaints_categories?->duration_type ?></strong>
            </h4>
        </div>
        <div class="panel-body">
            <div class="panel panel-inverse">

                <div class="panel-body">
                    <form class="form-horizontal" id="assignFrom" method="post" action>

                        <div class="mb-3">
                            <label for="range_02" class="form-label">Assign To</label>
                            <div class="col-lg-12">

                                <select name="assign_to" id="assign_to" data-plugin-selectTwo
                                    class="form-select populate" required>
                                    <option value=""></option>
                                    <?php foreach ($users as $user) { ?>
                                        <option value="<?= $user->id ?>"><?= $user->full_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="range_02" class="form-label">Share Document via</label>
                            <div class="col-lg-12">
                                <select name="delivery" id="delivery" class="form-select" required>
                                    <option value=""></option>
                                    <option value="SMS">SMS</option>
                                    <option value="EMAIL">EMAIL</option>
                                </select>
                            </div>
                        </div>
                      
                        <div class="mb-3">
                            <label class="form-label">Assign Comment</label>
                            <div class="col-lg-12">
                                <textarea name="assign_comment" class="form-control input-lg"></textarea>
                            </div>
                        </div>
                        <!-- Added -->
                       
                        <!-- End Added -->

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-9 col-md-offset-1 col-md-10">
                                <input type="hidden" name="id" value="{{$req}}">
                                {{-- <input type="submit" class="btn btn-success" name="btnAssign" value="Assign"> --}}
                            </div>
                        </div>

                        <div class="modal-footer">
                            {{-- <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button> --}}
                            <button type="submit" class="btn btn-success waves-effect waves-light">Assign Complaint</button>
                        </div>


                    </form>
                </div>

            </div>

        </div>

    </div>
    <!-- end panel -->
</div>

<script>
    $(document).ready(function() {
        $(document).on('change', 'select#delivery', function() {
            console.log('here');
            var delivery = $(this).val();
            if (delivery == 'SMS') {
                $('div#live1').show('slow');
                $('div#live2').hide('slow');
            } else if (delivery == 'EMAIL') {
                $('div#live2').show('slow');
                $('div#live1').hide('slow');
    
            } else {
                $('div#live1').hide('slow');
                $('div#live2').hide('slow');
            }
        });
    
        $('input#duration_time').val(moment().format('MMMM Do YYYY, h:mm:ss a'));
    
        $(document).on('change blur keyup', 'input#policy_start, input#policy_duration_num, select#duration_type',
            function() {
    
                //if ($('select#tb_product_id').val() > 0) {
    
                calculate_duration();
                //}
            });
    
        function calculate_duration() {
            console.log('here');
            var duration = $('select#tbl_product_id').find("option:selected").data("duration");
            var takeoff = 0;
            // if (duration == 73) {
            // 	takeoff = 0;
            // } else {
            // 	takeoff = 1;
            // }
            var policy_start = $('input#policy_start').val();
            var policy_end = $('input#policy_end');
            var policy_num = $('input#policy_duration_num').val();
            var duration_type = $('select#duration_type').val();
            if (policy_start.length > 0) {
                //console.log(policy_num, duration_type);
                var policy_end_date = moment(policy_start, 'YYYY-MM-DD').add(policy_num, duration_type).subtract(
                    takeoff, 'days').format('YYYY-MM-DD');
                //console.log(policy_end_date);
                policy_end.val(policy_end_date);
            }
        }
    
    });
    </script>