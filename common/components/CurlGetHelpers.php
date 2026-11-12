<?php 
namespace app\common\components;
use Yii;
use yii\helpers\Url;

class CurlGetHelpers
{	
	public static function get($controller='hello', $action='info', $id = NULL)
	{

        $url = 'http://192.168.1.1/yii2-consumption/web/'.$controller.'/'.$action;

		$curled = curl_init(); //Starting handle.
			if($id){
				curl_setopt_array($curled, [
                    CURLOPT_HTTPPROXYTUNNEL => false,
                    CURLOPT_URL => $url,
  //                  CURLOPT_POST => false,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => json_encode(
                        ['id'=>$id]
                    ),
    #				CURLOPT_POSTFIELDS => json_encode(
    #					Arr::merge($data,
    #					array('token'=>Security::token()))
    #				), //converting $data to JSON.
                    CURLOPT_HTTPHEADER => [
                        "accept: application/json",
                        "content-type: application/json",
                        "X-Requested-With: XMLHttpRequest"
                    ],
			    ]);
			} else {
				curl_setopt_array($curled, [
				CURLOPT_HTTPPROXYTUNNEL => false,
				CURLOPT_URL => $url,
				CURLOPT_POST => TRUE,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => [
					"accept: application/json",
					"content-type: application/json",
					"X-Requested-With: XMLHttpRequest",
					
				],
			]);
			}
			
			$result = '';
			$response = curl_exec($curled);

			if (curl_errno ( $curled )) {
				echo curl_error ( $curled );
				curl_close ( $curled );
				exit ();
			} else {
				$getinfo = curl_getinfo($curled);
				$responses = (array)json_decode($response, TRUE);//get array of Std class
				$message = '';
				/* if($getinfo['http_code'] == 200){
					if(is_array($responses)){
						foreach($responses as $response) {
							foreach($response as $key=>$value){
								if(is_string($value)){
									$message = $message.' key - '.$key.' value - '.$value."\n";
								}							
							}
						}				
					} else {
						foreach($responses as $key=>$value){
							$message = $message.' key - '.$key.' value - '.$value;
						}
					}
				} else {
					if(isset($responses['message'])){
						$message = $message.$responses['message'];
					}					
				}	
							
				$result = $message.' -- code -- '.$getinfo['http_code']; */
	//			var_dump($errors); 
			}		
			curl_close($curled);
			
			return [
				$responses,
				$getinfo
			];
	}
		
}
			
		
		

