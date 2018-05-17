 <div id="container">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('manager/dashboard'); ?>">Dashboard</a></li>
            <li>Orders</li>
            <li class="active breadcrumb-item"><i class="white-text" aria-hidden="true"></i> View Orders</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    CDRR
                </div>
                <!-- /.panel-heading -->

                <!--
                 if   <?= $columns['cdrrs']['data'][0][4]; ?> is D-CDRR, add two columns for  aggr_consumed & aggr_on_hand 
                
             -->
             <div class="panel-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 ">
                            <?php if ($role=='county'){?>

                                <button type="submit" class="btn btn-success" id="approveOrder">Approve Order</button>
                                <button type="submit" class="btn btn-warning" id="rejectOrder">Reject Order</button>
                            <?php }?>
                        </div>
                        <div class="col-md-8 ">
                            <span class="label label-info">* 1</span> - <small> Total Qty ISSUED to ARV dispensing sites (Satellite sites plus Central site dispensing point(s) where relevant)</small><br />
                            <span class="label label-info">* 2</span> - <small> End of Month Physical Count</small><br />
                            <span class="label label-info">* 3</span> - <small> Reported Aggregated Quantity CONSUMED in the reporting period (Satellite sites plus Central site dispensing point where relevant)</small> <br />
                            <span class="label label-info">* 4</span> - <small> Reported Aggregated Physical Stock on Hand at end of reporting period (Satellite sites plus Central site dispensing point where relevant)
                            </small>
                            <br />
                            <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <table cellpadding="4"  width="100%" class="table">
                                <tbody>
                                    <tr>
                                        <td><b>Facility Name: </b><span class="facility_name"> <?= $columns['cdrrs']['data'][0]['facility_name']; ?></span></td>
                                        <td><b>Facility code: </b><span class="mflcode"><?= $columns['cdrrs']['data'][0]['mflcode']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><b>County: </b><span class="county"><?= $columns['cdrrs']['data'][0]['county']; ?></span></td>
                                        <td><b>Subcounty: </b><span class="subcounty"><?= $columns['cdrrs']['data'][0]['subcounty']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><b>Type of Service provided at the Facility: </b><span class="services"><?= $columns['cdrrs']['data'][0]['services']; ?></span></td>
                                        <td><b>Non-ARV: </b> <?= $columns['cdrrs']['data'][0]['non_arv']; ?><input type="checkbox" class="non_arv" readonly="readonly"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>Period of Reporting: </b>
                                            <b>Beginning:</b> <span class="period_begin"><?= $columns['cdrrs']['data'][0]['period_begin']; ?></span>
                                            <b>Ending:</b> <span class="period_end"><?= $columns['cdrrs']['data'][0]['period_end']; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>order status: </b> <span class="period_begin"><?= $columns['cdrrs']['data'][0]['status']; ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                             

                        </div>
                        <div class="col-md-8">
                            <table class=" table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th rowspan="3">Drug Name</th>
                                        <th rowspan="3">Pack Size</th>
                                        <th>Beginning Balance</th>
                                        <th>Quantity Received</th>
                                        <th>Qty ISSUED  <span class="label label-info">* 1</span></th>
                                        <th>End Month Count <span class="label label-info">* 2</span></th>
                                        <th>Qty Consumed <span class="label label-info">* 3</span></th>
                                        <th>Physical SAH<span class="label label-info">* 4</span></th>
                                        <?php if($columns['cdrrs']['data'][0]['code'] == 'D-CDRR'){ ?> 
                                            <th >Aggregate Consumed</th>
                                            <th >Aggregate SAH</th>
                                        <?php }?>
                                        <th >Qty for RESUPPLY</th>
                                    </tr>
                                    <tr>
                                        <th><small>(packs)</small></th>
                                        <th><small>(packs)</small></th>
                                        <th><small>(packs)</small></th>
                                        <th><small>(packs)</small></th>
                                        <th><small>(packs)</small></th>
                                        <th><small>(packs)</small></th>
                                        <th><small>(packs)</small></th>
                                        <?php if($columns['cdrrs']['data'][0]['code'] == 'D-CDRR'){ ?> 
                                            <th><small>(packs)</small></th>
                                            <th><small>(packs)</small></th>
                                        <?php }?>
                                    </tr>
                                    <tr>
                                        <th>A</th>
                                        <th>B</th>
                                        <th>C</th>
                                        <th>D</th>
                                        <th>E</th>
                                        <?php if($columns['cdrrs']['data'][0]['code'] == 'D-CDRR'){ ?> 
                                            <th>F</th>
                                            <th>G</th>
                                        <?php }?>
                                        <th>H</th>
                                        <th>J</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <form name="orderForm" id="orderForm">
                                        <?php foreach ($columns['drugs'] as $key => $drug) {  ?>
                                            <tr>
                                                <td><?= $drug['name'];?></td>
                                                <td><?= $drug['pack_size'];?></td>
                                                <?php 
                                                $drugname = $drug['name'];
                                                 if (in_array($drugname, array_keys($columns['cdrrs']['data']['cdrr_item']))){?>
                                                <td><?= $columns['cdrrs']['data']['cdrr_item'][$drugname]['balance']; // beginning balance ?></td>
                                                <td><?= $columns['cdrrs']['data']['cdrr_item'][$drugname]['received']; // qty received?></td>
                                                <td><?= $columns['cdrrs']['data']['cdrr_item'][$drugname]['dispensed_units'];// Qty Issued ?></td>
                                                <?php if($columns['cdrrs']['data'][0]['code'] == 'D-CDRR'){ ?> 
                                                    <td><?= $columns['cdrrs']['data']['cdrr_item'][$drugname]['aggr_consumed'];?></td>
                                                    <td><?= $columns['cdrrs']['data']['cdrr_item'][$drugname]['aggr_on_hand'];?></td>
                                                <?php }?>
                                                <td><?= $columns['cdrrs']['data']['cdrr_item'][$drugname]['count'];?></td>
                                                <td><?= $columns['cdrrs']['data']['cdrr_item'][$drugname]['received']; //dispensed?></td>
                                                <td><?= $columns['cdrrs']['data']['cdrr_item'][$drugname]['received'];?></td>
                                                <td><?= $columns['cdrrs']['data']['cdrr_item'][$drugname]['resupply'];?></td>

                                                <?php } else { ?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php if($columns['cdrrs']['data'][0]['code'] == 'D-CDRR'){ ?> 
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php }?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                            <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </form>
                                </tbody>
                            </table>
                        </div> <!--end of cdrr-->
                        <div class="col-md-4">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <th >Regimen Code</th>
                                    <th >ARV Treatment Regimen</th>
                                    <th >Active Patients on this regimen <span class="label label-info">* 1</span></th>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($columns['regimens'] as $category => $regimens ) {?>
                                        <tr>
                                            <td colspan="3"> <?= $category; ?></td>
                                        </tr>
                                        <?php foreach($regimens as $regimen) { ?>
                                            <tr>
                                                <td><?= $regimen['code'];?></td>
                                                <td><?= $regimen['name'];?></td>
                                                <td><?php 
                                                if(in_array($regimen['id'], array_keys($columns['maps']['data']))){
                                                    echo $columns['maps']['data'][$regimen['id']];
                                                }else{
                                                    echo '0';
                                                }?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div><!--end of maps-->
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
</div>
<!-- /#page-wrapper -->

<script type="text/javascript">
    $(function(){
        $('#side-menu').remove();
        // alert('page-wrapper!');


        $('#approveOrder').click(function(e){
            $.get( "/ART/manager/orders/actionOrder/<?= $cdrr_id; ?>/approved", function( data ) {
              alert(data);
              window.location.href = "";
          });
        })
        $('#rejectOrder').click(function(e){
            $.get( "/ART/manager/orders/actionOrder/<?= $cdrr_id; ?>/rejected", function( data ) {
              alert(data);
              window.location.href = "";
          });
        })
        $('#complete_allocation').click(function(e){
            $.get( "/ART/manager/orders/actionOrder/<?= $cdrr_id; ?>/allocated", function( data ) {
              alert(data);
              window.location.href = "";
          });
        });

        $('#save_allocation').click(function(e){
            // $('form')..serializeArray();
            var form = $('#orderForm');
            var url ="/ART/manager/orders/updateOrder/<?= $cdrr_id; ?>";

            $.ajax( {
              type: "POST",
              url: url,
              data: form.serialize(),
              success: function( response ) {
                alert('Allocation Saved')

                $.get( "/ART/manager/orders/actionOrder/<?= $cdrr_id; ?>/pending", function( data ) {
              // alert(data);
              // window.location.href = "";
          });


            }
        });
        })


    });
</script>
<style type="text/css">
.breadcrumb{
    padding: 8px 15px 5px 8px;
    margin-bottom: 0px; 
}
.panel-default{
    margin: 12px;
}
td,th{
    padding: 2px;
}
</style>