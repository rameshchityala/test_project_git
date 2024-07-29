
<?php


 function sendOTP($number,$row2,$conn){
	$username = $row2['USERNAME'];
    //$numbers        = array('918977988889');
	$sender         = 'TSCYCP';	
    $digits         = 6;	
    $otp            = rand(pow(10, $digits-1), pow(10, $digits)-1);
	$message        = 'Dear '. $username.', '.$otp.' is your OTP for Login. Regards, CYCAPS Team, T4C';

	//echo $message;
	
    //$numbers        = implode(',', $numbers);
    $apikey         = 'I5AyQYir754-NwwB39eZ5bHPj9zV8dmFJGJxe9Ju5V';
    $sms_url        = 'https://api.textlocal.in/send/';

    	
	// Prepare data for POST request
	$data           = array(
        'apikey' => $apikey,
     'numbers' => $number, "sender" => $sender, 
     "message" => $message);
	//ECHO "<PRE>";
	//print_r($data);
	// Send the POST request with cURL
	$ch             = curl_init($sms_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response       = curl_exec($ch);
    if(curl_errno($ch)){
        // Log::info(print_r(curl_error($ch), true).' curl repsonse');    
		 //print_r(curl_error($ch));
    }
	
	
	
	
	curl_close($ch);
	$decode_result  = json_decode($response);
	//print_r($decode_result);
    if($decode_result){
        $code = $decode_result->status;
        if($code=='success'){
			$sql2 = "UPDATE USERS SET OTP= '$otp',OTP_CRTD_DATE = GETDATE() WHERE MOBILE = '$number' ";
			$st2 = sqlsrv_query( $conn, $sql2 );
			// echo $otp;
            //$save_otp = DB::table('users')->where('id',$id)->update(['otp'=>$otp]);

            $response = array('success'=>1, 'message'=>'OTP sent sucessfully');
            return json_encode($response);
        }else{
            $response = array('success'=>0, 'message'=>'OTP not sent, try again');
            return json_encode($response);
        }
    }
}
