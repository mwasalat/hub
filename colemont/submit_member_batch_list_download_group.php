<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
require_once("../plugins/classes/PHPExcel.php");
ini_set('max_execution_time', 0); //0=NOLIMIT
set_time_limit(0);
ini_set('memory_limit','2048M');
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load("../excel_template/cmt/cmt_ins_excel_premium_template.xlsx");
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri') ->setSize(11);
$today            = date('d-m-Y');
$ordervalueA = $_REQUEST['flag'];

$newQryjA = "SELECT tb2.`company_name` FROM `tbl_cmt_ins_members_premium` tb1 INNER JOIN `tbl_cmt_ins_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` WHERE tb1.`tr_no`='$ordervalueA' GROUP BY tb1.`company_id` ORDER BY tb1.`company_id` ASC";
$sqlQryjA = mysqli_query($conn, $newQryjA);
if(mysqli_num_rows($sqlQryjA)>0){
$letter = "U";
$vt=1;
while($rowQryjA = mysqli_fetch_array($sqlQryjA)){ 
$objPHPExcel->getActiveSheet()->setCellValue($letter.$vt, $rowQryjA['company_name']);
$letter++;
}
}
$vt=2;
$sql = "SELECT * FROM `tbl_cmt_ins_members` WHERE `tr_no`='$ordervalueA' ORDER BY `insurance_id` ASC";
$data = mysqli_query($conn,$sql);
$i = 1;
while($sbuild = mysqli_fetch_array($data)){
$objPHPExcel->getActiveSheet()->setCellValue('A'.$vt,$i);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$vt,$sbuild['first_name']);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$vt,$sbuild['middle_name']);
$objPHPExcel->getActiveSheet()->setCellValue('D'.$vt,$sbuild['last_name']);
$objPHPExcel->getActiveSheet()->setCellValue('E'.$vt,$sbuild['emirates_id']);
$objPHPExcel->getActiveSheet()->setCellValue('F'.$vt,$sbuild['emirates_id_application_no']);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$vt,$sbuild['passport_no']);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$vt,$sbuild['dob']);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$vt,$sbuild['gender']);
$objPHPExcel->getActiveSheet()->setCellValue('J'.$vt,$sbuild['relation_type']);
$objPHPExcel->getActiveSheet()->setCellValue('K'.$vt,$sbuild['marital_status']);
$objPHPExcel->getActiveSheet()->setCellValue('L'.$vt,$sbuild['mobile_no']);
$objPHPExcel->getActiveSheet()->setCellValue('M'.$vt,$sbuild['landline_no']);
$objPHPExcel->getActiveSheet()->setCellValue('N'.$vt,$sbuild['email']);
$objPHPExcel->getActiveSheet()->setCellValue('O'.$vt,$sbuild['nationality']);
$objPHPExcel->getActiveSheet()->setCellValue('P'.$vt,$sbuild['emirate_residence']);
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$vt,$sbuild['location_residence']);
$objPHPExcel->getActiveSheet()->setCellValue('R'.$vt,$sbuild['emirate_visa']);
$objPHPExcel->getActiveSheet()->setCellValue('S'.$vt,$sbuild['visa_issuance_date']);
$objPHPExcel->getActiveSheet()->setCellValue('T'.$vt,$sbuild['currently_insured']);
                    $premium             = 0;
                    $sqlQryAB             = mysqli_query($conn,"SELECT `company_id`,`premium` FROM `tbl_cmt_ins_members_premium` WHERE `tr_no`='$ordervalueA' GROUP BY `company_id` ORDER BY `company_id` ASC");
                    if(mysqli_num_rows($sqlQryAB)>0){
						$letter = "U";
						while($rowQryB = mysqli_fetch_array($sqlQryAB)){ 
							$company_id          = $rowQryB['company_id'];
							$premium             = empty($rowQryB['premium'])?"0":$rowQryB['premium'];
							$objPHPExcel->getActiveSheet()->setCellValue($letter.$vt,Checkvalid_number_or_not($premium,2));
							$letter++;
						}
					}

$i++;
$vt++; 
}
    $objPHPExcel->setActiveSheetIndex(0);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=CMT-INS-'.$ordervalueA.'_'.date('d-m-Y-H-i-s').'.xlsx');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
    $objWriter->save('php://output');  
    exit();
?>
