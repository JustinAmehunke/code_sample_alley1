<div class="row">

    <div class="col-md-12">
      <section class="panel">
        <div class="panel-body">
          <div class="invoice">
            <header class="clearfix">
              <div class="row">
                <div class="col-sm-6 mt-md">
  
                  <img src="/assets/images/logo.png" alt="Old Mutual Life Assurance Ghana Limited" height="55">
  
                </div>
                <div class="col-sm-6 text-right mt-md mb-md">
                    <address class="ib mr-xlg">
                        Old Mutual Life Assurance Ghana Limited<br>
                        No. 4 Dr. Paul A. Acquah Street
                        Airport Residential Area
                        Accra<br>
                        Phone: 0307000600<br>
                        admin@oldmutual.com.gh
                    </address>
                </div>
              </div>
            </header>
  
              <h5>
  
                <strong>Audit Trail Log :</strong>
              </h5>
  
              <table id="technical_requirement1" class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important; ">
  
              <thead>
                <tr>
  
                  <th class="col-md-1"></th>
  
                  <th class="col-md-2">Request No.</th>
                  <th class="col-md-2">Resource Type</th>
                  <th class="col-md-2">User</th>
                  <th class="col-md-2">Action</th>
                  <th class="col-md-2">Date/Time</th>
                
  
                </tr>
              </thead>
              <tbody>
                @foreach ($records as $key => $record)
                  <tr>
                    <td>{{++$key}}</td>
                    <td><?= $record->tbl_document_applications['request_no'] ?></td>
                    <td><?= $record->tbl_document_workflow_type['name'] ?></td>
                    <td><?= $record['reference'] ?></td>
                    <td><?= $record['log_action'] ?></td>
  
                    <td class="text-left">
                        <?= date('d-M-Y H:i:s',strtotime($record['createdon'])) ?>
                    </td>
                   
                  </tr>
                  @endforeach
              </tbody>
            </table>
        </div>
    </div>
  
  
  
      </section>
    </div>
  
  
  </div>

