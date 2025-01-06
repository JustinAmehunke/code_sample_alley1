<h5>

    <strong>Audit Trail Log :</strong>
  </h5>

  <table id="technical_requirement1" class="table table-striped no-wrap table-bordered responsive"
  style="font-size: 13px;color: #000; padding: 5px 10px !important; ">

  <thead>
    <tr>

      <th class="col-md-1"></th>

      <th class="col-md-2">Request No.</th>
      <th class="col-md-2">Status</th>
      <th class="col-md-2">Created On</th>
      <th class="col-md-2">Created By</th>
      <th class="col-md-2">Notes</th>
    

    </tr>
  </thead>
  <tbody>
    <?php  $i=1; foreach ($records as $record) {
      ?>
      <tr>
        <td><?= $i ?></td>
        <td><?= $record->tbl_customer_complaints?->request_no ?></td>
        <td><?= $record['status'] ?></td>
        <td> <?= date('d-M-Y H:i:s',strtotime($record['createdon'])) ?></td>
        <td><?= getUserName($record['createdby']) ?></td>

        <td class="text-left">
            <?= $record['note'] ?>
        </td>
       
      </tr>
    <?php $i++; } ?>

  </tbody>
</table>