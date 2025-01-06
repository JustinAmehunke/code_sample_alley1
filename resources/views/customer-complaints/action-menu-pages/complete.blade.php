<form class="form-horizontal" id="completeForm" method="post" action>

    <div class="mb-3">
        <label class="form-label" for="id_type">Complaint Category * :</label>
        <div class="col-lg-12">
            <select name="cat_id" id="" class="form-select">
                <option>
                <?php 
                    foreach ($categories as $category) : ?>
                <option value="<?= $category['id'] ?>">
                    <?= $category['name'] ?></option>
            <?php endforeach ?>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="id_type">Classification * :</label>
        <div class="col-lg-12">
            <select name="class_id" id="" class="form-select">
                <option>
                <?php 
                    foreach ($classifications as $classification) : ?>
                <option value="<?= $classification['id'] ?>">
                    <?= $classification['name'] ?></option>
            <?php endforeach ?>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label>Notes</label>
        <textarea name="note" class="form-control input-lg"></textarea>
    </div>

    <!-- Added -->
    <div class="mb-3">
        <label>Notified By</label>
        <div class="input-group input-group-icon">
            <select name="notified_by_id" id="notified_by_id" required data-plugin-selectTwo
                class="form-select populate input-lg">
                <option value=""></option>

                <?php 
                    foreach ($notifiedBy as $notifiedB) : ?>
                <option value="<?= $notifiedB['id'] ?>">
                    <?= $notifiedB['name'] ?></option>
                <?php endforeach ?>
            </select>

        </div>
    </div>
    <div class="mb-3">
        <label>Product Type</label>
        <div class="input-group input-group-icon">
            <select name="product_type_id" id="product_type_id" required data-plugin-selectTwo
                class="form-select populate input-lg">
                <option value=""></option>

                <?php 
                    foreach ($productTypes as $productType) : ?>
                <option value="<?= $productType['id'] ?>">
                    <?= $productType['name'] ?></option>
                <?php endforeach ?>
            </select>

        </div>
    </div>
    <div class="mb-3">
        <label>Process</label>
        <div class="input-group input-group-icon">
            <select name="process_id" id="process_id" required data-plugin-selectTwo
                class="form-select populate input-lg">
                <option value=""></option>

                <?php 
                    foreach ($processes as $process) : ?>
                <option value="<?= $process['id'] ?>">
                    <?= $process['name'] ?></option>
                <?php endforeach ?>
            </select>

        </div>
    </div>
    <!-- End Added -->
        
    <div class="row">
        <div class="col-sm-8">
        &nbsp;
        </div>
        <div class="col-sm-4 text-right">
            <input type="hidden" name="id" value="{{$req}}">

        
        {{-- <button type="submit" name="btnComplete" class="btn btn-success hidden-xs">Submit</button>
        <button type="submit" name="btnComplete" class="btn btn-success btn-block btn-lg visible-xs mt-sm">Validate Response</button> --}}


        </div>
    </div>

    <div class="modal-footer">
        {{-- <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button> --}}
        <button type="submit" class="btn btn-success waves-effect waves-light">Complete Complaint</button>
    </div>


</form>