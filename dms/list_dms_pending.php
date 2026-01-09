<?php 
require_once("../header_footer/header.php");
set_time_limit(0);
?>
<style>
 table#example2 {
  margin: 0 auto;
  width: 100%;
  clear: both;
 /* border-collapse: collapse;*/
  table-layout: fixed;         
  word-wrap:break-word;     
 /* white-space: pre-line;*/
}

/*table td{
    overflow:hidden;
    text-overflow: ellipsis;
}*/
 table  td a {
    margin: 5px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../bower_components/select2/select2.min.css">
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Cheque List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Cheque List</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="supplier-form" id="supplier-form" method="post" action="#">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Cheque List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped stripe row-border order-column" role="grid" style="font-size:12px;">
                  <thead>
                    <tr>
                      <th >Entry Dt.</th>
                      <th >Batch No.</th>
                      <th >Ref No.</th>
                      <?php if($empid==911855 || $empid==911001 || $empid==911102 || $empid==911300 || $empid==911100 || $empid==300421 || $empid==100205 || $empid==300418 || $empid==912642){?>
                      <th >Company</th>
                      <?php }?>
                      <th >Beneficiary Name</th>
                      <th >Chq. Date</th>
                      <th >P. Amt(in AED)</th>
                      <th >Pmnt. Details</th>
                      <th >Addl. Details</th>
                      <th >Authr<sup>n</sup></th>
                      <th ></th>
                    </tr>
                     <tr>
                      <th ></th>
                      <th ></th>
                      <th ></th>
                      <?php if($empid==911855 || $empid==911001 || $empid==911102 || $empid==911300 || $empid==911100 || $empid==300421 || $empid==100205 || $empid==300418 || $empid==912642){?>
                      <th ></th>
                       <?php }?>
                      <th ></th>
                      <th ></th>
                      <th ></th>
                      <th ></th>
                      <th ></th>
                      <th ></th>
                      <th ></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					                       if($empid==911855 || $empid==911001 || $empid==911102 || $empid==911300 || $empid==911100  || $empid==912642){//Sanil, Abood, Hashim, Karunesh, Dinesh, Sanish, Adnan
											$sql  = "SELECT tb1.*,tb2.`company_name`,COUNT(tb3.`item_document`) AS `no_docs` FROM `tbl_dms_transactions` tb1 LEFT JOIN `tbl_dms_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` LEFT JOIN `tbl_dms_transactions_items` tb3 ON tb1.`transaction_id`=tb3.`transaction_id` WHERE tb1.`status`!=3 AND tb1.`is_active`=1 GROUP BY tb1.`transaction_id` ORDER BY tb1.`entered_date` DESC";   
										   }else if($empid==300421 || $empid==300418){//Sanish
											$sql  = "SELECT tb1.*,tb2.`company_name`,COUNT(tb3.`item_document`) AS `no_docs` FROM `tbl_dms_transactions` tb1 LEFT JOIN `tbl_dms_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` LEFT JOIN `tbl_dms_transactions_items` tb3 ON tb1.`transaction_id`=tb3.`transaction_id` WHERE tb1.`status`!=3 AND tb1.`is_active`=1 AND tb1.`company_id`=8 GROUP BY tb1.`transaction_id` ORDER BY tb1.`entered_date` DESC";   
										   }else if($empid==100205){//RAJESH(ETAXI)
											$sql  = "SELECT tb1.*,tb2.`company_name`,COUNT(tb3.`item_document`) AS `no_docs` FROM `tbl_dms_transactions` tb1 LEFT JOIN `tbl_dms_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` LEFT JOIN `tbl_dms_transactions_items` tb3 ON tb1.`transaction_id`=tb3.`transaction_id` WHERE tb1.`status`!=3 AND tb1.`is_active`=1 AND tb1.`company_id`=10 GROUP BY tb1.`transaction_id` ORDER BY tb1.`entered_date` DESC";   
										   }else{
				                           $sql  = "SELECT tb1.*,tb2.`company_name`,COUNT(tb3.`item_document`) AS `no_docs` FROM `tbl_dms_transactions` tb1 LEFT JOIN `tbl_dms_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` LEFT JOIN `tbl_dms_transactions_items` tb3 ON tb1.`transaction_id`=tb3.`transaction_id` WHERE tb1.`status`!=3 AND tb1.`entered_by`='$userno' AND tb1.`is_active`=1 GROUP BY tb1.`transaction_id` ORDER BY tb1.`entered_date` DESC";
										   }
				                           $data = mysqli_query($conn,$sql);
										   $itr = 0;
                                            while($build = mysqli_fetch_array($data)){ 
											 // Status
											   if($build['status'] == "1") {
													$class = 'bg-danger';
													$label = "label-danger";
													$type = 'Open';
													$bg = "bg-red";
												} else if($build['status'] == "2") {
													$class = 'bg-warning';
													$type  = 'CEO Approved';
													$label = "label-warning";
													$bg = "bg-yellow";
												}else if($build['status'] == "3") {
													$class = 'bg-success';
													$type  = 'Accountant Approved';
													$label = "label-success";
													$bg = "bg-green";
												}else{
													$bg = "bg-red";
												//do nothing
												}
												
										
										            $no_docs = !empty($build['no_docs'])?$build['no_docs']:0;
													?>
							<tr id="tr_id_<?=$itr?>">
							  <td data-order="<?=date('Y-m-d',strtotime($build['date_of_entry']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
							  <td><?=$build['transaction_no']?></td>
							  <td><?=$build['reference_no']?></td>
                              <?php if($empid==911855 || $empid==911001 || $empid==911102 || $empid==911300 || $empid==911100 || $empid==300421 || $empid==100205 || $empid==300418  || $empid==912642){?>
                              <td><?=$build['company_name']?></td>
                              <?php }?>
							  <td><?=$build['beneficiary_name']?></td>
                              <td data-order="<?=date('Y-m-d',strtotime($build['cheque_date']))?>"><?=date('d-m-Y',strtotime($build['cheque_date']))?></td>
                              <td><?=Checkvalid_number_or_not($build['payment_amount'],2)?></td>
                              <td><?=$build['payment_details']?></td>
                              <td><?=$build['additional_details']?></td>
                              <td><input type="hidden" id="status_<?=$itr?>" value="<?=$build['status']?>"/>
                              <a href="javascript:void(0);" onClick="fun_authorize('<?=$build['transaction_id']?>','<?=$itr?>')" id="label_<?=$itr?>" title="<?=$type?>" class="label <?=$bg?>"><i class="fa fa-thumbs-up fa-lg"></i></a>
                              </td>
                              <!-- <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>-->
                             <td style="display:inline-flex; padding:10px;">
                      <?php if($build['status']!=3){?>
                       <a href="view_dms.php?flag=<?=$build['transaction_id']?>&gtoken=<?=$token?>" title="View" class="btn btn-block btn-default<?php if($no_docs>0){?> btn-success<?php }?> btn-sm" /><i class="fa fa-eye fa-sm"></i></a>
                       
                      <a href="edit_dms.php?flag=<?=$build['transaction_id']?>&gtoken=<?=$token?>" title="edit" class="btn btn-block btn-default btn-sm"/><i class="fa fa-edit fa-sm"></i></a>
                     
                      </a><?php }?>
                      </td>
							</tr>
							<?php 
							$itr++;
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
	 theme: "classic",
	  width: 'resolve' // need to override the changed default
});
$('.datepickerA').datepicker({
    format: 'dd-mm-yyyy',
    startDate: '-0d',
	autoclose: true
});
$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
});

function fun_authorize(t_id,tr_id){
	
	var status = $("#status_"+tr_id).val();
	if(status==1 || status==2){
		  var xmlhttp=new XMLHttpRequest();
			xmlhttp.open("GET","../ajax/ajax_dms_authorize_status.php?flag1="+t_id+"&flag2="+status,false);
			xmlhttp.send(null);
			if($.trim(xmlhttp.responseText)!=0){
			  if($.trim(xmlhttp.responseText)==2){
				$("#status_"+tr_id).val($.trim(xmlhttp.responseText));  
				$("#label_"+tr_id).removeClass("label bg-red");  
				$("#label_"+tr_id).addClass("label bg-yellow"); 
				$("#label_"+tr_id).attr('title','CEO approved!'); 
				noty({ text: "CEO approval done!", layout: 'topCenter', type: 'warning', timeout: 2000 });  
			  }else if($.trim(xmlhttp.responseText)==3){
				$("#status_"+tr_id).val($.trim(xmlhttp.responseText));  
				$("#label_"+tr_id).removeClass("label bg-yellow");  
				$("#label_"+tr_id).addClass("label bg-green");  
				$("#label_"+tr_id).attr('title','Accountant approved!'); 
				$("#tr_id_"+tr_id).hide();
			 	noty({ text: "Accountant approval done!", layout: 'topCenter', type: 'success', timeout: 2000 });
			  }else{
				noty({ text: "Error!", layout: 'topCenter', type: 'error', timeout: 2000 });
			  }
			}
	}else{
		noty({ text: "Already approval done!", layout: 'topCenter', type: 'error', timeout: 2000 });
	}
}
</script>
<!--<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>-->
<script src="../plugins/datatable_1_10_16/datatables.1.13.5.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../plugins/datatable_1_10_16/dataTables.select.min.js"></script>
<!--<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>-->
<script type="text/javascript">
$(document).ready( function () {
new DataTable('#example2', {
  "autowidth": false,
  "bSort": false,
  "select": {
            toggleable: false
        },
  /*"aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 0, 1, 2, 3,4, 5, 6, 7 ,8,9,10] }
    ],*/
	"aaSorting": [[ 0, "desc" ]],
    initComplete: function () {
        this.api()
            .columns()
            .every(function () {
                let column = this;
                let title = column.header().textContent;
 
                // Create input element
                let input = document.createElement('input');
                input.placeholder = title;
                column.header().replaceChildren(input);
                // Event listener for user input
                input.addEventListener('keyup', () => {
                    if (column.search() !== this.value) {
                        column.search(input.value).draw();
                    }
                });
            });
    }
});
})
</script>
