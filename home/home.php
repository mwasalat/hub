<?php require_once("../header_footer/header.php");
//Total Vehcles BMS
$sqlNewQryA = mysqli_query($conn,"SELECT `vehicle_id` FROM `tbl_bms_vehicle_master`");
$NumNewQryA =(mysqli_num_rows($sqlNewQryA))?mysqli_num_rows($sqlNewQryA):0; 

//Total Brokers BMS
$sqlNewQryB = mysqli_query($conn,"SELECT `broker_id` FROM `tbl_bms_broker_master`");
$NumNewQryB =(mysqli_num_rows($sqlNewQryB))?mysqli_num_rows($sqlNewQryB):0; 

//Total Locations BMS
$sqlNewQryC = mysqli_query($conn,"SELECT `location_id` FROM `tbl_bms_location_master`");
$NumNewQryC =(mysqli_num_rows($sqlNewQryC))?mysqli_num_rows($sqlNewQryC):0; 

//LPO Transactions
$sqlNewQryD = mysqli_query($conn,"SELECT `transaction_id` FROM `tbl_transactions` WHERE `status`=1");
$NumNewQryD =(mysqli_num_rows($sqlNewQryD))?mysqli_num_rows($sqlNewQryD):0; 

//BMS Booking
$sqlNewQryE = mysqli_query($conn,"SELECT `booking_id` FROM `tbl_bms_booking` WHERE `status`=1");
$NumNewQryE =(mysqli_num_rows($sqlNewQryE))?mysqli_num_rows($sqlNewQryE):0; 


//Colemont 
if($empid==911855 || $_SESSION['usercompany']==12){
//a. Insurance Company Master
$sqlNewQryF = mysqli_query($conn,"SELECT `company_id` FROM `tbl_cmt_ins_company_master` WHERE `is_active`=1");
$NumNewQryF =(mysqli_num_rows($sqlNewQryF))?mysqli_num_rows($sqlNewQryF):0; 
//b. Insurance Client Master
$sqlNewQryG = mysqli_query($conn,"SELECT `customer_id` FROM `tbl_cmt_ins_customer_master` WHERE `is_active`=1");
$NumNewQryG =(mysqli_num_rows($sqlNewQryG))?mysqli_num_rows($sqlNewQryG):0; 
//c. Insurance Price Master
$sqlNewQryH = mysqli_query($conn,"SELECT `price_batch_id` FROM `tbl_cmt_ins_price_batch_master` WHERE `is_active`=1");
$NumNewQryH =(mysqli_num_rows($sqlNewQryH))?mysqli_num_rows($sqlNewQryH):0; 
//d. Insurance Member Master
$sqlNewQryI = mysqli_query($conn,"SELECT `insurance_master_id` FROM `tbl_cmt_ins_members_master` WHERE `is_active`=1");
$NumNewQryI =(mysqli_num_rows($sqlNewQryI))?mysqli_num_rows($sqlNewQryI):0; 
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> ENG <small>Portal</small> </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <!------Start Blocks------------->
    <div class="row">
      <?php if($empid==911855 || $empid==911770 || $empid==911868 || $empid==911102 || $empid==911120 || $empid==902792 || $empid==911072){//Sanil,Mizar,Maricel,Hashim , Jim & Gamal- BMS Section?> 
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?=$NumNewQryA?></h3>
            <p>BMS Vehicles</p>
          </div>
          <div class="icon"> <i class="fa fa-car"></i> </div>
          <a href="../bms_master/vehicle_master.php?gtoken=<?=$token?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?=$NumNewQryB?></h3>
            <p>BMS Brokers</p>
          </div>
          <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
          <a href="../bms_master/broker_master.php?gtoken=<?=$token?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?=$NumNewQryC?></h3>
            <p>BMS Locations</p>
          </div>
          <div class="icon"> <i class="fa fa-map"></i> </div>
          <a href="../bms_master/location_master.php?gtoken=<?=$token?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-maroon">
          <div class="inner">
            <h3><?=$NumNewQryE?></h3>
            <p>BMS Bookings</p>
          </div>
          <div class="icon"> <i class="fa fa-book"></i> </div>
          <a href="../bms_booking/list_booking.php?gtoken=<?=$token?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <?php }?>
      <?php if($empid==911855 || $empid==12345  || $empid==911092 || $empid==911102){//Sanil,Sanju, Peter, Hashim - LPO Section?>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?=$NumNewQryD?></h3>
            <p>Unique LPO Transactions</p>
          </div>
          <div class="icon"> <i class="ion ion-pie-graph"></i> </div>
          <a href="../lpo/transaction_list.php?gtoken=<?=$token?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <?php }?>
      
      
      <?php if($empid==911855 || $_SESSION['usercompany']==12){//Sanil,Colemont Team?>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?=$NumNewQryF?></h3>
            <p>Insurance Companies - Colemont</p>
          </div>
          <div class="icon"> <i class="ion ion-pie-graph"></i> </div>
          <a href="../colemont/company_master.php?gtoken=<?=$token?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
       <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?=$NumNewQryG?></h3>
            <p>Insurance Customers - Colemont</p>
          </div>
          <div class="icon"> <i class="ion ion-pie-graph"></i> </div>
          <a href="../colemont/customer_master.php?gtoken=<?=$token?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?=$NumNewQryH?></h3>
            <p>Insurance Price Master - Colemont</p>
          </div>
          <div class="icon"> <i class="ion ion-pie-graph"></i> </div>
          <a href="../colemont/list_price_master.php?gtoken=<?=$token?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
       <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-maroon">
          <div class="inner">
            <h3><?=$NumNewQryI?></h3>
            <p>Insurance Member Master - Colemont</p>
          </div>
          <div class="icon"> <i class="ion ion-pie-graph"></i> </div>
          <a href="../colemont/list_ins_premium.php?gtoken=<?=$token?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <?php }?>
      
    </div>
    <!------End Blocks------------->
    <!-- Default box -->
    <?php /*?><div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">ENG</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"> <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"> <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body"> Welcome to ENG  Portal!!! </div>
        <!-- /.box-body -->
       <!-- <div class="box-footer"> All </div>-->
        <!-- /.box-footer-->
      </div><?php */?>
    <!-- /.box -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once("../header_footer/footer.php");?>
