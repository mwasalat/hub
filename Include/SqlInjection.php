<?php
/*
SQL INJECTION CODE
*/
function killstring($mainstring)
{
	/*$db_host_kill     = "localhost";
	$db_username_kill = "wfinoerr_dhub";
	$db_password_kill = "abcd@1234"; 
	$db_name_kill     = "wfinoerr_dhub";
	$conn_kill        = new mysqli("$db_host_kill","$db_username_kill","$db_password_kill","$db_name_kill") or die ("Could not connect to MYSQL");*/
	$mainstring = trim($mainstring);
	//$mainstring=strtolower($mainstring); 
	//$badstrings="create%alter%script%<%>%select%drop%;%--%insert%update%delete%xp_%'%\%,%(%)%";
	//$badstrings="create/alter/script/select/drop/--/insert/update/delete/xp_/`/$/'/^/\/~/"; 
	$badstrings="script/</>/drop/--/delete/xp_/'/(/)/%/$/!/=/#/^/*/?/~/`/“/Â/\/";
	//$badarray=explode("%",$badstrings);
	$badarray=explode("/",$badstrings);
	foreach( $badarray as $key =>$badsubstring)  
	{
		$mainstring = str_replace($badsubstring," ",$mainstring);
	}
	$mainstring = str_replace('"', " ", $mainstring);//Remove double quotes
	//$mainstring = preg_replace("/\r\n/", " ", $mainstring);//Remove html quotes 
	$mainstring = preg_replace('!\s+!', ' ', $mainstring);//
	$mainstring = trim($mainstring);
	//$ln=strlen($mainstring);  
	return($mainstring);
}
  
function valid_Integer($number)
{
//if(!preg_match("/[^0-9]/",$number))
 if(!ereg("^[0-9]+$",$number))
	{
	 return false;
	}
	else
	{
	 return true;
	}
}

function valid_Decimal($number)
{
	if(!ereg("^[0-9.]+$",$number))
	{
	 return false;
	}
	else
	{
	 return true;
	}
}
	
function valid_AlphaNumeric($text)
{
 if(!ereg("^[[:space:]0-9A-Za-z,-._/()]+$",$text))
	{
	 return false;
	}
	else
	{
	 return true;
	}
}

function valid_String($text)
{
 if(!ereg("^[[:space:]A-Za-z.]+$",$text))
	{
	 return false;
	}
	else
	{
	 return true;
	}
}

function valid_Date($date)
{
  $pattern ='/\b\d{1,2}[\/-]\d{1,2}[\/-]\d{4}\b/';

  if (!preg_match($pattern, $date))
	{
		return false;
	}
 else
	{
	  return true;
	}
}	
	    
/*
$finname = killstring($_POST['tfinname']);

if(isset($_POST['ttargetdate']))
					$targetdate=killstring($_POST['ttargetdate']);						
				if(	$targetdate=="")
					$targetdateupd="null";
				 else
				   $targetdateupd="to_date('$targetdate','DD-MM-YYYY')";



**********script************

function addflag(formname,pageurl)
{//alert('Add');
	formname.flagvalue.value="add";
	formname.action=pageurl;
	formname.submit();
}


if(TheForm.tfinname)
{
  		if (TheForm.tfinname.value.length == 0)
  		{
   			alert("Enter Financial Component Name");
   			return false;
  		}
}
*/

// check file size while uploading
	 
	function CheckFileSize($fsize,$size)
	{
		if(($fsize>0) && ($fsize<=$size))
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
	
	   // check field size [Buffer Overflows Check]
	
	 function CheckFieldSize($field,$size,$nul)
	{	 
		  if($nul=="no")
		  {
			   $fsize=strlen($field);
			   
			   if($fsize > 0 && $fsize <= $size)
			   {
					return true;
			   }
			   else
			   {
					return false;
			   }	 
			} 
			else
			  {
			  
			   $fsize=strlen($field);
			   
			   if($fsize <= $size)
				   {
						return true;
				   }
			   else
				   {
						return false;
				   }
				}
		}
	
	// check chars are valid or not . . . . .
	
	 function allValidChars($email) 
	 {
		 $parsed = '0';
		 
		 $ent=chr(13);
		 
		 $validchars = "abcdefghijklmnopqrstuvwxyz,0123456789@.-_";
		 
		 $elen=strlen($email);
		 
		 for($i=0;$i<$elen;$i++)
		 {
			$elett=strtolower(substr($email,$i,1));
			
			$st=strpos($validchars,$elett);
			
			if($st!=0)
			{
				$parsed='1';
				continue;
			}
			else
			{
				$parsed='0';
				break;
			}
		 }
		
		return $parsed;	
	}
	
	
	// check Email . . . . .
	
	function CheckEmail($str)
	{
	
		if(eregi("^[a-z0-9]+[a-z0-9_-]*(\.[a-z0-9_-]+)*@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.([a-z]+){2,}$", $str))		
		{
			return '1';
		}		
		else 
		{
			return '0';
		}	
	
	}
	
	// Write File,where $ap="wb","a","wa" etc
	
	function writeFileF($path,$text,$ap)
	{
		$handle=fopen($path,$ap);
		
		if (fwrite($handle, $text) == FALSE) 
		{
		 	return false;
		}
		else 
		{
			return true;
		}
	
	}
	
	// check chars are valid or not . . . . .
	
	function checkValidChrs($chrstr)
	{
	
		$cstr=strtolower($chrstr);
		
		$validchars = "abcdefghijklmnopqrstuvwxyz0123456789@.-_";
		
		// | [a-z0-9]+[!@#$%^&*] | ^[!@#$%^&*]+[a-z0-9]
		
		if(eregi("[:space:]a-zA-Z0-9_.-]",$cstr))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	  
	/*
	For Cross side scripting 
	
	<?php
	$cur_url=$_SERVER['REQUEST_URI'];
	$url=explode('?',$cur_url);
	$cur_url=$url[0];
	if($url[0]!="/vanitha/admin/home.php") //Replace it with your home url
	{
		$urls=explode('*',$_SESSION['url']);
		$n=count($urls);
		$flg=0;
		for($i=0;$i<$n;$i++)
		{
			if($urls[$i]==$url[0])
			{
				$flg=1;
				break;
			}
		}	
		if($flg==0)
		{
			?>
			<script language="javascript">
				alert("You Cannot Access this Page");
				window.location="../admin/logout.php";
			</script>
			<?php
		}
	}*/

?>