<?php
/**********************************
Project VENUS

Virtual
Enrollment
Notification &
Update
System
************************************/

/*
Build file structure such that urls can later be generated dynamically, ex:
"../institution/department/studies.xml"

Should probably include a hash (or study name as hash) that will go in configuration file. 

Make an option for text paging vs numeric paging only
*/

// these vars will ultimately be sent with the POST from the VENUS appliance
/*
if(isset($_GET["data"])){
	print_r($_SERVER[REQUEST_URI]);
	//$encoded = json_encode($_GET);
	//header('Content-type: application/json');
	//exit($encoded);
}
*/
if(!isset($_GET["data"])){echo "404"; die();}
$id = htmlspecialchars($_GET["data"]);
$location = htmlspecialchars($_GET["coreid"]);


/*
get vars are id and location
- no change to the id logic
- build a switch statement for the location logic
-- case(location)
--- $callback number
--- $xml_file
--- $speak_to -- who the research nurse should asked to speak with when s/he calls back
*/

$test_mode = 1; // 0 = live mode. anything else = test mode.

/*
Each coreid is assigned to a specific location. If the core ID changes, this section must be changed to reflect it.
*/

switch ($location) {
    case "6r1234567890": // this is the coreid for the device at Eskenazi
		$location_name = "placename1";
		$callback_number = "1234567890";
		$speak_to = "resident.";
		if($test_mode == 0){
			$xml_filename = "studies.xml";
		}else{
			$xml_filename = "test_studies.xml";
		}
        break;
    case "9j1234567890":
		$location_name = "placename2";
        $callback_number = "1234567890";
		$speak_to = "resident.";
		if($test_mode == 0){
			$xml_filename = "2studies.xml";
		}else{
			$xml_filename = "test_studies.xml";
		}
        break;
    default:
        die("location not set");
}

//DEBUGGING STUFF
/*
	echo $id;
	echo "<br />";
	echo $location;
	echo "<br />";
	echo $xml_filename;
	echo "<br />";
	echo $callback_number;
	echo "<br />";
	echo $speak_to;
	echo "<br />";
	
	//exit("all done!");
	
	echo "moar!";
*/

// include XML file
$studies = simplexml_load_file($xml_filename); // comment out for testing
//echo '<pre>'; print_r($studies); echo '</pre>';
// include twilio class - found at http://twilio.com
require("../assets/twilio/Services/Twilio.php");
$sid = "YOUR_TWILIO_SID_HERE"; 
$token = "YOUR_TWILIO_TOKEN_HERE"; 

// include postmark class - found at http://postmarkapp.com
require("../assets/postmark.php");
$postmark_api_key = "YOUR_POSTMARK_KEY_HERE";
$postmark_from_email = "YOUR_POSTMARK_EMAIL_ADDRESS_HERE";

// include pushover or boxcar class


$study_title = $studies->xpath('//study[@id="'.$id.'"]/title')[0];
//echo $study_title;
//echo '<br />';

//echo $studies->study[$id]->contacts->contact[0]->name;
//echo '<br />';


// Edit these things if you wish...
$text_page_message = "Potential candidate for $study_title at $location_name. $callback_number";
$sms_message = "Potential candidate for $study_title at $location_name. $callback_number - Ask to speak to the $speak_to";
$phone_message = "There is a potential candidate for your study, $study_title, at $location_name. Please call $callback_number for more details. Goodbye.";

$email_subject = "ALERT: ".$study_title;
$email_message = "There is a potential candidate for your study, $study_title, at $location_name. Please call $callback_number for more details. Ask to speak to the $speak_to";

// set timestamp variable

// count the number of contacts in this study
$contacts_count = count($studies->xpath('//study[@id="'.$id.'"]/contacts/contact'));
//echo '<pre>'; print_r($studies->xpath('//study[@id="'.$id.'"]/contacts/contact')); echo '</pre>';

// loop through all the contacts in the study
$i = 1;
while($i < $contacts_count + 1){
	
	// grab the name for each contact (for the logfile)
	//$name = $studies->study[$id]->contacts->contact[$i]->name;
	$name = $studies->xpath('//study[@id="'.$id.'"]/contacts/contact['.$i.']/name')[0];
	if($name != ""){
		// debug
		//echo "NAME: ".$name."<br />";
		//echo '<pre>'; print_r($name); echo '</pre>';
	}
	
	// grab the phone number for each contact
	// echo "<strong>Phone:</strong> ".$studies->study[$id]->contacts->contact[$i]->phone.'<br />';
	
	
	$phone = $studies->xpath('//study[@id="'.$id.'"]/contacts/contact['.$i.']/phone')[0];
	
	if($phone != ""){
		
		// debug
		//echo "PHONE: ".$phone."<br />";
		
		// use twilio to make the phone call
	}
	
	
	// grab the email for each contact
	//echo "<strong>Email:</strong> ".$studies->study[$id]->contacts->contact[$i]->email.'<br />';
	$email = $studies->xpath('//study[@id="'.$id.'"]/contacts/contact['.$i.']/email')[0];
	
	if($email != ""){
		
		// debug
		//echo "EMAIL: ".$email."<br />";
		
		
		$postmark = new Postmark("$postmark_api_key","$postmark_from_email");
		$result = $postmark->to("$email")
			->subject("$email_subject")
			->plain_message("$email_message")
			->send();
		
	}
	
	// grab the pager for each contact
	//echo "<strong>Pager:</strong> ".$studies->study[$id]->contacts->contact[$i]->pager.'<br />';
	
	// IF A PAGER EXISTS, SEND A PAGE. OTHERWISE, RESET THE PAGER VAR TO EMPTY
	if($studies->xpath('//study[@id="'.$id.'"]/contacts/contact['.$i.']/pager')[0] != ""){
		$pager = $studies->xpath('//study[@id="'.$id.'"]/contacts/contact['.$i.']/pager')[0];
		$pager = "+1".$pager;
		
		// debug
		//echo "PAGER: ".$pager."<br />";
		
		// use twilio to send text page messages
		
		$client = new Services_Twilio($sid, $token);
		$client->account->messages->sendMessage("2345678900", "$pager", "$text_page_message");
		
	}//else{$pager = "";}
	
	
	

	// grab the sms for each contact
	// echo "<strong>SMS:</strong> ".$studies->study[$id]->contacts->contact[$i]->sms.'<br /><br />';
	
	// IF AN SMS NUMBER EXISTS, SEND THE SMS MESSAGE. OTHERWISE, RESET THE SMS VAR TO EMPTY
	if($studies->xpath('//study[@id="'.$id.'"]/contacts/contact['.$i.']/sms')[0] != ""){
		$sms = "+1".$studies->xpath('//study[@id="'.$id.'"]/contacts/contact['.$i.']/sms')[0];
		
		// debug
		//echo "SMS: ".$sms."<br />";
		
		// use twilio to send sms messages
		
		$client = new Services_Twilio($sid, $token);
		$client->account->messages->sendMessage("2345678900", "$sms", "$sms_message");
		
	}//else{$sms = "";}
	
	
	
	// push to array for log file
	//echo $phone."<br />";
	//echo $email."<br />";
	//echo $sms."<br />";
	//echo $pager."<br /><hr />";
	$now = time();
	$csv_line = array($now, $study_title, $name, $phone, $pager, $email , $sms);
	
	$fp = fopen('files/logfile.csv', 'a');
	//echo $csv_line."<br />";
	//echo '<pre>'; print_r($csv_line); echo '</pre>';
	fputcsv($fp, $csv_line);
	//fclose($fp);
	
	// empty all the vars
	$name = "";
	$phone = "";
	$email = "";
	$sms = "";
	$pager = "";
	
	$i++;
}

//print_r($_SERVER[REQUEST_URI]);
//$encoded = json_encode($_GET);
//header('Content-type: application/json');
//exit($encoded);
exit($_SERVER[REQUEST_URI]);
// output array to log file

// ping pushover or boxcar
// http://pushover.net

/*
echo '<hr />';
echo '<pre>'; print_r($_GET); echo '</pre>';
echo '<hr />';
$studies = (array) $studies;
echo '<pre>'; print_r($studies); echo '</pre>';

?>
*/
