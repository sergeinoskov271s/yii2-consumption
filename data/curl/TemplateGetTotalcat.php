<?php 


			$curl = curl_init(); //Starting handle.
			curl_setopt_array($curl, array(
				CURLOPT_HTTPPROXYTUNNEL => false,
				CURLOPT_URL => 'http://192.168.1.1/yii2-consumption/json/template/gettotalcat',
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
#				CURLOPT_POSTFIELDS => json_encode(
#					Arr::merge($data, 
#					array('token'=>Security::token()))
#				), //converting $data to JSON.
				CURLOPT_HTTPHEADER => array(
					"accept: application/json",
					"content-type: application/json"			),
			));
			//curl_setopt($curl, CURLOPT_URL, "http://example.com/api/endpoint");

// Set the request method to POST
			curl_setopt($curl, CURLOPT_POST, true);

			$result = '';
			$resp = curl_exec($curl);

			if (curl_errno ( $curl )) {
				echo curl_error ( $curl );
//				//::instance()->add(Log::NOTICE, Debug::vars('---er',curl_error ( $curl )));		
				curl_close ( $curl );
				exit ();
			} else {
				$getinfo = curl_getinfo($curl);
				$responses = (array)json_decode($resp, TRUE);//get array of Std class		
			
				$message = '';
				if($getinfo['http_code'] == 200){
					if(is_array($responses)){
						foreach($responses as $response) {
							foreach($response as $key=>$value){
								if(is_string($value)){
									$message = $message.' key - '.$key.' value - '.$value;
								}							
							}
						}				
					} else {
						foreach($responses as $key=>$value){
							$message = $message.' key - '.$key.' value - '.$value;
						}
					}
				} else {
//					Log::instance()->add(Log::NOTICE, Debug::vars('error',$responses));//
					if(isset($responses['message'])){
						$message = $message.$responses['message'];
					}					
				}	
							
				$result = $message.' -- code -- '.$getinfo['http_code'];
	//			var_dump($errors); 
			}		
			curl_close($curl);		
			
//			echo $responses;
			
			echo $result."\n";
		
		

