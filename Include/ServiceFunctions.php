<?php
require_once('Module.php');
require_once('DbConnector.php');

/* *FUNCTION TO GET LANGUAGE CODE*/
function getLanguageCode()
{
    $getLanguageCode = 1;
    return  $getLanguageCode;
}


//*************************************
function CardDetailsexist($php_tsocode,$php_languagecode,$cardno) //,$php_distcode
{

    global $connector;

	
      $sql="select a.*,b.* from cardmaster a,familymaster b where
            a.tso_code = $php_tsocode and a.language_code = $php_languagecode and a.rationcardno = $cardno
            and a.language_code=b.language_code and a.tso_code=b.tso_code and a.rationcardno=b.rationcardno";

    $result=$connector->query($sql);
	
    if(!($result))
    {
        $php_LblMessage .= "<li>Data not available...</li>";
        return false;
    }

    while($row = pg_fetch_object($result)) //if data available,display it in the card details tab
    {
       
            $tdate=FormatDBDate($row->issued_time,"dd/MM/yyyy");   //to check with ard_slno and issued_date

            
                if($row->card_register == "N")
                {
                   $php_LblMessage .= "<li>Card is not Registered...</li>";
                   return false;
                }
			   
			   /* if ($row->verified == "Y" && $row->issued == "Y" )
                {
                }
                else if ($row->verified == "N")
                {
                    $php_LblMessage .= "<li>Card is not Verified...</li>";
                    return false;
                }
                else if ($row->issued =="N")
                {
                    $php_LblMessage .= "<li>Card is not Issued...</li>";
                    return false;
                }*/
         
               
                $_SESSION['ownername'] = $row->owner_name;
                $_SESSION['address1'] = $row->address1;
                $_SESSION['address2'] = $row->address2;
                $_SESSION['address3'] = $row->address3;

                $php_txtDistCode = $row->district_code;
                
                $php_txtLBodyCode = $row->local_body_code;
                $php_txtLBodyName = getLocalBodyName($php_txtDistCode, $php_txtLBodyCode, $php_languagecode);
                $_SESSION['Lbody']= $php_txtLBodyCode."-".$php_txtLBodyName;
                
                $php_txtVillageCode = $row->village_code;
                $php_txtVillageName = getVillageName($php_txtDistCode, $php_txtVillageCode, $php_languagecode);
                $_SESSION['village']=$php_txtVillageCode."-".$php_txtVillageName;
                
                $_SESSION['ard'] = $row->ard_no;
                $_SESSION['ward'] = $row->ward_number;
                $_SESSION['house'] = $row->house_no;
                
                $_SESSION['FamilyIncome'] = $row->monthly_income;
                $_SESSION['IncomeTaxPayee'] = $row->incometax_payee;
                $_SESSION['PanCardNo'] = $row->pancard_no;
                
                $_SESSION['aplbpl'] = $row->apl_bpl;
                $_SESSION['apna'] = $row->annapoorna;
                $_SESSION['aay'] = $row->andhyodaya;
                
                $_SESSION['Electrified'] = $row->electrified;
                
                $_SESSION['gas_available'] = $row->gas_available;
                $_SESSION['NoOfCylinders'] = $row->no_cylinders;
                
                $_SESSION['KeroPermitNo'] = $row->kero_permitno;
                $_SESSION['KeroQty'] = $row->qty_kerosene;
                
                $_SESSION['no_adults'] = $row->no_adults;
                $_SESSION['no_minors'] = $row->no_minors;
                $_SESSION['total_members'] = $row->total_members;
                $_SESSION['total_units'] = $row->total_units;
                
                $_SESSION['regno']=$row->ard_card_slno;
				$php_date=FormatDBDate($row->issued_time,"dd/MM/yyyy");
                $_SESSION['IssuedDate']=$php_date;

                $_SESSION['OldRationCardNo'] = $row->old_cardno;
                $_SESSION['CardSource'] = getSourceOfCard($row->card_source, $php_languagecode);

                $php_photo = "../Photo.php?TsoCode=$php_tsocode&LanguageCode=$php_languagecode&RationCardNo=$cardno";
				$_SESSION['photo']=$php_photo;
               
                return true;
			
    }
}
//**************************************
/* FUNCTION TO GET Reason */
	function getReason($reasoncode,$card_type)
	{
		global $connector;

		$sql = "select reason_description from reason_master where reason_code=$reasoncode and card_function_type=$card_type";

		$result = $connector->query($sql);

		while($row = pg_fetch_row($result))
		{
			$getReason = $row[0];
		}

		return $getReason;
	}
/* END OF FUNCTION TO GET Reason*/



/* FUNCTION TO GET Certificate Name */
	function getCertificateName($certcode)
	{
		global $connector;
						
		$sql = "select certificate_name from cardmaster_certificates where certificate_code={$certcode}";
		
		$result = $connector->query($sql);
		
		while($row = pg_fetch_row($result))
		{
			$getCertificateName = $row[0];
		}
		
		return $getCertificateName;
	}
/* END OF FUNCTION TO GET Certificate Name */

/* FUNCTION TO GET Payment Type */
	function getPaymentName($paycode)
	{
		global $connector;
						
		$sql = "select payment_type from mode_payment where payment_code={$paycode}";
		
		$result = $connector->query($sql);
		
		while($row = pg_fetch_row($result))
		{
			$getPaymentName = $row[0];
		}
		
		return $getPaymentName;
	}
/* END OF FUNCTION TO GET Payment Type */

/* FUNCTION TO GET Delivery Type */
	function getDeliveryName($delcode)
	{
		global $connector;
						
		$sql = "select delivery_type from mode_delivery where delivery_code={$delcode}";
		
		$result = $connector->query($sql);
		
		while($row = pg_fetch_row($result))
		{
			$getDeliveryName = $row[0];
		}
		
		return $getDeliveryName;
	}
/* END OF FUNCTION TO GET Delivery Type */

//Retrieving next appl number & date
function RetrieveNextApplNo()
{
    global $connector;

    $_SESSION['appno'] = "";
    $_SESSION['appdate']= "";
    
    $sql="select max_appl_no from cen_max_appl_number where year=". $_SESSION['cur_year'];

    $result=$connector->query($sql);
      while($row=pg_fetch_object($result))
       {
        $applno = $row->max_appl_no;
       }

       if($applno=="") $applno=1;

       else (int)$applno = ((int)$applno)+1;
           
           
    //updating next appl no
    if($applno==1) //new rec for cur year
    {
        $sql = "insert into cen_max_appl_number values(".$_SESSION['cur_year'].",$applno)";
    }
    else
    {
        $sql = "update cen_max_appl_number set max_appl_no=$applno where year=".$_SESSION['cur_year'];
    }
    $result=$connector->query($sql);
    if($result)
    {
       $_SESSION['appno']=$applno;
       $datenow = date("d/m/Y");
       $_SESSION['appdate']= FormatDate($datenow,"MM/dd/yyyy");
    }
    
    
}

function SaveApplicationDetails($tsocode,$card_type,$cardno,$modepay,$modedel)
{
        global $connector;
        //global $applno;
        //global $newRecord;
		//*********
		global $php_name;
		global $php_addr1;
		global $php_addr2;
        global $php_addr3;
        global $php_email;
        global $php_AppNo;
        global $php_AppDate;
        global $cardno;
        //$cardno=InsertActualData($cardno);
		//$saved=true;
		//global 
		$php_languagecode=getLanguageCode();
        $_SESSION['lang_code']=$php_languagecode;
		//**********
        $userid=$_SESSION['UserID']; 
		$datenow = date("d/m/Y");
		$submtime = FormatDate($datenow,"MM/dd/yyyy");

        //if cert attached
        $filearray=$_SESSION['file_array'] ;
        if(count($filearray)!=0) $certattached = "Y";
        else $certattached = "N";

    if($php_AppNo == "" && $php_AppDate =="") //new record
    {
        $appdate = $_SESSION['appdate'];
        $applno = $_SESSION['appno'];
        

           //inserting new record
           $values = array("tso_code" => (int)$tsocode,
                             "appl_date" => FormatDate($appdate, "yyyy-MM-dd"),
                             "appl_number" => (int)$applno,
                             "language_code" => (int)$php_languagecode,
                             "card_function_type" => (int)$card_type,
                             "applicants_name" => $php_name,
                             "applicants_add1" => $php_addr1,
                             "applicants_add2" => $php_addr2,
                             "applicants_add3" => $php_addr3,
                             "applicants_emailid" => $php_email,
                             "rationcardno" => (int)$cardno,
                             "certificates_attached" => $certattached,
                             "submitted_by" => $userid,
                             "submitted_time" => FormatDate($submtime, "yyyy-MM-dd"),
                             "mode_payment" => (int)$modepay,
                             "mode_delivery" => (int)$modedel);

        $result = $connector->insert("cen_cardappl_master", $values);

    }
    else //existing record
    {
//        $applno=$php_AppNo;
//		$appdate = FormatDate($php_AppDate, "MM/dd/yyyy");

        $values = array("applicants_name" => $php_name,
                         "applicants_add1" => $php_addr1,
                         "applicants_add2" => $php_addr2,
                         "applicants_add3" => $php_addr3,
                         "applicants_emailid" => $php_email,
                         "rationcardno" => (int)$cardno,
                         "certificates_attached" => $certattached,
                         "submitted_by" => $userid,
                         "submitted_time" => FormatDate($submtime, "yyyy-MM-dd"),
                         "mode_payment" => $modepay,
                         "mode_delivery" => $modedel,
                         "actionflag" => 'N');

            $key = array("tso_code" => $tsocode,
                         "appl_date" => FormatDate($php_AppDate, "yyyy-MM-dd"),
                         "appl_number" => $php_AppNo,
                         "card_function_type" => $card_type);
        
            $result = $connector->update("cen_cardappl_master", $values,$key);
    }


    if (!$result)
     {
         return false;

     }
    else
    {
        if(SaveSyncDetails($tsocode,1))
         {
             return true;
         }
         else
            return false;
    }
     
}
//function for saving certificate details

function saveCertificate($tsocode,$appdate,$applno,$card_type,$cert,$certno)
{
 
 global $connector;
 $error = false;
 $sql = "Delete from cen_card_appl_certificates where tso_code=" . $tsocode . " and appl_date='" . $appdate . "' and appl_number=" . $applno . " and card_function_type=" . $card_type;
 $res= $connector->query($sql);

    if($res)
     {
          foreach($cert as $val)
          {
              if($error == false)
              {
                 $values = array("tso_code" => $tsocode,
                             "appl_date" => FormatDate($appdate, "yyyy-MM-dd"),
                             "appl_number" => $applno,
                             "card_function_type"=>$card_type,
                             "certificate_code" => $val["slno"],
                             "certificate_number" => $certno
                            );

                 $result = $connector->insert("cen_card_appl_certificates", $values);
                  if(!$result)
                  {
                     $error = true;
                     break;
                  }
              }
             
        }

        if($error == false)
        {
            $saved = SaveSyncDetails($tsocode,16);
            if($saved == true) return true;
            else return false;
        }
        else return false;
    }
    else
    {
        return false;
    }

return true;
}

//***************
function Certificates_Retrieve($tsocode,$appldate,$applno,$card_type)
{
     global $connector;

     $sql2="select c.certificate_code,c.certificate_number,m.certificate_name
		       from cen_card_appl_certificates c,cardmaster_certificates m
		       where c.certificate_code=m.certificate_code and c.card_function_type=".$card_type."
		       and c.tso_code=".$tsocode." and c.appl_date='".$appldate."' and c.appl_number=".$applno ;


	// $sql2=" select * from cen_card_appl_certificates where tso_code=$tsocode and appl_date='$appldate' and appl_number=$applno and card_function_type=$card_type" ;
     $result2 = $connector->query($sql2);

     $arry_cert = array();

     while($row = pg_fetch_array($result2))
        {
            $arry_cert[]= array("slno" => $row["certificate_code"],
                                "certificate_number" => $row["certificate_number"],
                                "certificate_name" => $row['certificate_name']);
        }
     return $arry_cert;
}
//***************
//
//function for saving certificate details
function SaveCertificatePDFDetails($tsocode,$appdate,$applno,$card_type)
{
     global $connector;
     $filearray=$_SESSION['file_array'] ;

     if (count($filearray) != 0)
     {
         $saved=true;
         $sql = "Delete from cen_card_appl_certificate_pdf where tso_code=" . $tsocode . " and appl_date='" . $appdate . "' and appl_number=" . $applno . " and card_function_type=" . $card_type;
         $res= $connector->query($sql);

         if($res)
         {
          foreach ($filearray as $row)
             {
                if ($saved==true)
                 {
				   					
                     $values = array("tso_code" =>$tsocode,
                             "appl_date" => FormatDate($appdate, "yyyy-MM-dd"),
                             "appl_number" => $applno,
                             "card_function_type" => $card_type,
                             "slno" => $row["slno"],
                             "org_filename" =>$row["org_file"],
                             "ser_filename" =>$row["ser_file"],
                             "file_count" => $row["filecount"]);

                $result = $connector->insert("cen_card_appl_certificate_pdf", $values);
             }
         
            if(!$result)
               {
                   $saved=false;
                   break;
               }
             }

         }
         //cen_table entry'
         if($saved == true)
         {
             $saved = SaveSyncDetails($tsocode,17);
         }
         if($saved == true)
         {
//            $_SESSION['file_count'] = "";
//            $_SESSION["file_row"] = "";
//            $_SESSION["files"] = "";
//            $_SESSION['file_array'] = "";
         }
         return $saved;

         
     }
     
     
}

function RetrieveCertificatePDFDetails($tsocode,$appdate,$applno,$card_type)
{
     global $connector;

     $sql2=" select * from cen_card_appl_certificate_pdf where tso_code=".$tsocode." and appl_date='".$appdate."' and appl_number=".$applno." and card_function_type=".$card_type." order by slno";
      $result2 = $connector->query($sql2);
       $filearray = null;
     while($row = pg_fetch_array($result2))
        {
          $filearray[] = array("slno" => $row["slno"],
                           "org_file"=>$row["org_filename"],
                           "ser_file" =>$row["ser_filename"],
                           "filecount" =>$row["file_count"]);
        }
      $_SESSION["file_array"] =  $filearray;
     
}

function SaveSyncDetails($tsocode,$tablecode)
{
    global $connector;
    $sql = "delete from cen_centraltablesync where tso_code=".$tsocode. " and central_tcode=".$tablecode;
     $res = $connector->query($sql);
     if($res)
     {
        $sql = "insert into cen_centraltablesync values($tsocode,$tablecode)";
         $res = $connector->query($sql);
         if($res)
         {
            return true;
         }
     }
     return false;
}

function Certificates_to_Attach($card_type,$certchecked)
{
    global $connector;
     $sql="select c.certificate_code,c.certificate_name,f.mandatory from cardmaster_certificates c,cardfunction_certificates f
         where c.certificate_code=f.certificate_code and f.card_function_type=".$card_type;

       $result = $connector->query($sql);
       $i=1;
      $mandatory ="";  //to identify mandatory fields
        while ($row = pg_fetch_object($result))
        {
            $warning = "&nbsp;";
          if($row->mandatory == "Y")
          {
              $mandatory .= $row->certificate_code.",";
              $warning = "*";
          }
         if (count($certchecked) != 0)
         {
             $selected="";
            foreach ($certchecked as $row1)
             {
                 if($row->certificate_code==$row1["slno"])
                    {
                     $selected="checked";
                     break;
                    }
             }

             }
 
       
             $certificate.="<tr>
                   <td style=\"width:10px;\"><label class=\"warning\">$warning</label></td>
                   <td ><input id='certificate[]' name='certificate[]' type ='checkbox' value='$row->certificate_code' $selected></input></td>
                   <td class='mal_labelstyle_left'><label>$row->certificate_name</label></td>
                   </tr>";
           
        $i= $i+1;
        
        }

        //saving mandatory values in a hidden field
        $certificate.="<tr>
                   <td colspan=\"2\"><input id=\"hmandatory\" name=\"hmandatory\" type=\"hidden\" value=\"$mandatory\" /></td>
                 </tr>";
    
        return $certificate;
}

function LoadCombo($query,$selected_value,$valueOnly)
{
    global $connector;

    $result=$connector->query($query);
    
    while ($row= pg_fetch_row($result))
    {
      $code = $row[0];
      $name = $row[1];
      $cat_value = $code . ' - '. $name; //code+name

      $selected =""; //flag for selected

    if ($valueOnly == true)
    {
        if (trim($selected_value) == trim($code))
            {
                $selected = "selected";
            }
    } 
    else
    {
         if (trim($selected_value) == trim($cat_value))
            {
                $selected = "selected";
            }
    }
     

     if ($valueOnly == true)
     {
        printf ("<option value=\"%s\" %s>%s - %s</option>",$code, $selected,$code,$name);
     }
     else
     {
         printf ("<option value=\"%s - %s\" %s>%s - %s</option>",$code,$name, $selected,$code,$name);
     }
    

    
    }
}



// DISPLAYING NAVIGATION IN EACH PAGE
function NavigationHeaderDisplay($card_type)
{
    /*$header="<tr>";
    $header.="<td style='width:100%;'>";
    $header.="<table>";
    $header.="<tr>";
    $header.="<td><p align='left'><a href='MainHome.php' class='navigationlink'>Home</a> > </p></td>";
    $header.="<td><p align='right'><a href='Home.php?card_type=$card_type' class='navigationlink'>Main Menu</a></p></td>";
    $header.="</tr>";
    $header.="</table>";
    $header.="</td>";
    $header.="</tr>";*/

    $header = "<a href='MainHome.php' class='navigationlink'>Home</a>&nbsp;>&nbsp;<a href='Home.php?card_type=$card_type' class='navigationlink'>Main Menu";
    
    return $header;
}

function Logout()
{
    session_unset();
    header("Location:../CitizenLogin.php");
}

/**
 * Function used to check the gender of new relation with the old relation.
 * Returns true if old grnder and new gender are same else returns false.
 * @param <Integer> $relation_code_old Old Relation Code
 * @param <Integer> $relation_code_new New Relation Code
 */
function ValidateGender($relation_code_old, $relation_code_new)
{
    if($relation_code_old == 1) return true;
    if($relation_code_new == 1) return true;
    
    global $connector;

    $gender_old = "";
    $gender_new = "";

    $sql = "select relation_sex from relation where language_code = " . getLanguageCode() . " and relation_code = $relation_code_old";

    $result = $connector->query($sql);

    $row = pg_fetch_array($result);

    $gender_old = trim($row['relation_sex']);
    

    $sql = "select relation_sex from relation where language_code = " . getLanguageCode() . " and relation_code = $relation_code_new";

    $result = $connector->query($sql);

    $row = pg_fetch_array($result);

    $gender_new = trim($row['relation_sex']);

    if($gender_old == $gender_new)
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>
  
  