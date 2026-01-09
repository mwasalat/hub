<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
$insurance_master_id = killstring($_REQUEST['flag']);
$msg = "";
?>
<style>
/* table#example2 {
  margin: 0 auto;
  width: 100%;
  clear: both;
  table-layout: fixed;         
  word-wrap:break-word;     
}*/
 /* td a {
    margin: 5px;
  }*/
  </style>
<link rel="stylesheet" href="../bower_components/select2/select2.min.css">
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Insurance Member Premium List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Insurance Member Premium List</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="company-form" id="company-form" method="post" action="#" enctype="multipart/form-data">
      <?php if ($msg) { ?>
      <hr>
      <div class="alert <?=$alert_label?>">
        <?=$msg?>
      </div>
      <hr>
      <?php }?>
      <br/><br/>
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2"  class="table table-bordered table-hover table-striped stripe row-border order-column" role="grid" style="font-size:12px;">
                  <thead>
                    <tr>
                      <th >Entered Dt.</th>
                      <th >First Name</th>
                      <th >Middle Name</th>
                      <th >Last Name</th>  
                      <th >EID</th>
                      <th >EID Appln No</th>
                      <th >Passport No</th>
                      <th >DOB</th>
                      <th >Gender</th>
                      <th >Relation Type</th>
                      <th >Marital Status</th>
                      <th >Mobile No</th>
                      <th >Landline No</th>
                      <th >Email</th>
                      <th >Nationality</th>
                      <th >Emirate Residence</th>
                      <th >Location Residence</th>
                      <th >Emirate Visa</th>
                      <th >Visa Issuance Dt.</th>
                      <th >Currently Insured</th>
                      <th >Premium</th>
                      <th >Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 

					                        if (!empty($insurance_master_id )){
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.*,tb2.`premium`,tb3.`company_name` FROM `tbl_cmt_ins_members` tb1 INNER JOIN `tbl_cmt_ins_members_premium` tb2 ON tb1.`insurance_master_id`=tb2.`insurance_master_id` LEFT JOIN `tbl_cmt_ins_company_master` tb3 ON tb2.`company_id`=tb3.`company_id` WHERE tb1.`insurance_master_id`='$insurance_master_id' ORDER BY tb1.`insurance_id` ASC");
										   $itr = 0;
										   $itr = 1;
                                            while($build = mysqli_fetch_array($sqlQ)){
											$class   = ($build['is_active'] == "1")? 'bg-success' : 'bg-danger';
											$label   = ($build['is_active'] == "1")? 'label-success' : 'label-danger';
											$type    = ($build['is_active'] == "1")? 'Acive' : 'Inactive';
											$premium = empty($build['premium'])?0:Checkvalid_number_or_not($build['premium'],2);
										?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td><?=$build['first_name']?></td>
                      <td><?=$build['middle_name']?></td>
                      <td><?=$build['last_name']?></td>
                      <td><?=$build['emirates_id']?></td>
                      <td><?=$build['emirates_id_application_no']?></td>
                      <td><?=$build['passport_no']?></td>
                       <td><?=date('d-m-Y',strtotime($build['dob']))?></td>
                        <td><?=$build['gender']?></td>
                         <td><?=$build['relation_type']?></td>
                          <td><?=$build['marital_status']?></td>
                           <td><?=$build['mobile_no']?></td>
                            <td><?=$build['landline_no']?></td>
                            <td><?=$build['email']?></td>
                            <td><?=$build['nationality']?></td>
                            <td><?=$build['emirate_residence']?></td>
                            <td><?=$build['location_residence']?></td>
                             <td><?=date('d-m-Y',strtotime($build['currently_insured']))?></td>
                              <td><?=$build['visa_issuance_date']?></td>
                               <td><?=$build['location_residence']?></td>
                               <td><?=$build['company_name'].":".$premium;?>
                               <?php
                               //echo $build['company_name'].":".$premium;
							/*		$sqlQryAB             = mysqli_query($conn,"SELECT tb1.`company_id`,tb1.`premium`,tb2.`company_name` FROM `tbl_cmt_ins_members_premium` tb1 LEFT JOIN `tbl_cmt_ins_company_master` tb2 ON  tb1.`company_id`=tb2.`company_id` WHERE tb1.`tr_no`='$tr_no' ORDER BY tb2.`company_name` ASC");
									if(mysqli_num_rows($sqlQryAB)>0){
										$letter = "U";
										while($rowQryB = mysqli_fetch_array($sqlQryAB)){ 
											$company_name          = $rowQryB['company_name'];
											$premium             = empty($rowQryB['premium'])?"0":$rowQryB['premium'];
											echo $company_name.":".Checkvalid_number_or_not($premium,2).",";
										}
									}*/
									?>
                               </td>
                      <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                    </tr>
                    <?php 
					$i++;
                            }
						}
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </form>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once("../header_footer/footer.php");?>
<script type="text/javascript">
$(document).ready(function(){
$("#company-form").validationEngine();
$('.select').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
});
$('.datepickerA').datepicker({
    format: 'dd-mm-yyyy',
    startDate: '-0d',
	autoclose: true
});
$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
});
</script>
<!-- DataTables -->
<script src="../bower_components/select2/select2.js" type="text/javascript"></script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../plugins/datatable_1_10_16/pdfmake.min.js"></script>
<script src="../plugins/datatable_1_10_16/vfs_fonts.js"></script>
<!--<script src="../plugins/datatable_1_10_16/datatables.button.js"></script>-->
<script type="text/javascript" src="../plugins/datatable_1_10_16/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/jszip.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/buttons.html5.min.js"></script>
<script src="../plugins/datatable_1_10_16/dataTables.select.min.js"></script>
<script type="text/javascript">
$(document).ready( function () {
  var oTable = $('#example2').DataTable({
  "aaSorting": [[ 0, "desc" ]],
  "select": {
            toggleable: false
        },
  "autowidth":false,		
  "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 0, 1, 2, 3,4, 5, 6, 7 ,8, 9, 10, 11,12] }
    ],
	    dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Insurance Premium List - Colemont',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:first-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Insurance Premium List - Colemont',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:first-child)',
				}
            },
			 {
                extend: 'pdf',
                title: 'Insurance Premium List - Colemont',
			    className: 'btn btn-info',
				orientation: 'landscape',
                pageSize: 'LEGAL',
				exportOptions: {
					columns: ':not(:first-child)',
				}
            }
			]
        });
    });
</script>