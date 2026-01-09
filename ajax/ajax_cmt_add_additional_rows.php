<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid();   
?>

<div class="row col-md-12 new_customer" id="added_additional_members_<?=$unique_id?>" style="border:0.25px solid #cbb8b8;margin: 10px;">
  <h4>Additional Member</h4>
  <div class="col-md-6">
    <div class="form-group">
      <label class="required">First Name:</label>
      <input type="text" class="validate[required] form-control" name="additionl_customer_first_name[]" placeholder="First Name"/>
    </div>
    <div class="form-group">
      <label class="">DOB:</label>
      <div class="input-group date">
        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        <input type="text" name="additionl_dob[]" class="validate[required] datepickerA form-control" value=""  placeholder="DOB"/>
      </div>
    </div>
    <div class="form-group">
      <label class="">Marital Status:</label>
      <select class="validate[required] form-control select" name="additionl_marital_status[]" style="width:100%;">
        <option value="M">Married</option>
        <option value="S">Single</option>
      </select>
    </div>
    <div class="form-group">
      <button type="button" class="btn btn-default pull-left user-removeBtn1" onclick="fun_remove_additional_member('<?=$unique_id?>')">Remove Member</button>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label class="required">Last Name:</label>
      <input type="text" class="validate[required] form-control" name="additionl_customer_last_name[]" placeholder="Last Name"/>
    </div>
    <div class="form-group">
      <label class="">Gender:</label>
      <select class="validate[required] form-control select" name="additionl_gender[]" style="width:100%;">
        <option value="M">Male</option>
        <option value="F">Female</option>
      </select>
    </div>
    <div class="form-group">
      <label class="">Relation:</label>
      <select class="validate[required] form-control select" name="additionl_relation[]" style="width:100%;">
        <option value="S">Spouse</option>
        <option value="C">Child</option>
      </select>
    </div>
  </div>
</div>
