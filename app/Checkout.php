<?php

	namespace App;


	class Checkout{



	    public static function checkout(){


	    	// Set the stripe API key for current session
	    	\Stripe\Stripe::setApiKey('sk_test_W47JynSDfC8AenJRlmAe4kQP');

	    	$charge = \Stripe\Charge::create(array(
			  "amount" => 2000,
			  "currency" => "usd",
			  "source" => "tok_19umu5GnetXw0RpFFe6fNnDm", // obtained with Stripe.js
			));

	    	// token creation for this credit card
	  //   	$temp_token = \Stripe\Token::create(array(
			//   "card" => array(
			//     "number" => "4242424242424242",
			//     "exp_month" => 2,
			//     "exp_year" => 2018,
			//     "cvc" => "314"
			//   )
			// ));

			// print_r($temp_token->id);

	  //   	// 
	  //   	\Stripe\Charge::create(array(
			//   "amount" => 20000,
			//   "currency" => "cad",
			//   "source" => $temp_token->id, // obtained with Stripe.js
			//   "description" => "Charge for abigail.martin@example.com"
			// ), array(
			//   "idempotency_key" => "NAGedYd7OLVHbygh",
			// ));

			print_r($charge);


 			$re = \Stripe\Refund::create(array(
			  "charge" => "ch_19un0XGnetXw0RpFkU2F58t1"
			));

	    	return 0;


	  //   	$headers = apache_request_headers();
			// // Do Error check
			// try{
			// 	$jwt = $headers['Authortoken'];
			// }
			// catch(\Exception $e) {

			// 	try{
			// 		$jwt = $headers['authortoken'];
			// 	}
			// 	catch(\Exception $e) {
			// 		return 'Cannot find header: Authortoken/authortoken';
			// 	}
				
			// }
			// $ch = curl_init($url);

			// curl_setopt_array($ch, array(
			//     CURLOPT_RETURNTRANSFER => TRUE,
			//     CURLOPT_HTTPHEADER => array(
			//         'Authortoken: ' . $jwt,
			//         'Content-Type: application/json'
			//     )
			// ));

			// // Send the request
			// $response = curl_exec($ch);

			// // Check for errors
			// if($response === FALSE){
			//     die(curl_error($ch));
			// }

			// return json_decode($response);
	    }

	    public static function createCustomer(){

	    	\Stripe\Stripe::setApiKey("sk_test_O2mj41gmPm2YLr0okKelY9FL");

	    	// Have to catch exceptions later
	    	try{
				$customer = \Stripe\Customer::create(array(
				  "source" => "tok_1A0uFmLmdls5mzOxgC9HoO5D" // obtained with Stripe.js
				));
			}
			catch(\Exception $e){
				$a = json_decode($e->httpBody);
				print_r($a->error->message);
				return $e->httpBody;
			}

			return $customer['id'];
	    }



	    public static function currentCard(){
	    	\Stripe\Stripe::setApiKey("sk_test_O2mj41gmPm2YLr0okKelY9FL");

	    	$customer = \Stripe\Customer::retrieve("cus_AJ2dn7nKcAEHcK");

	    	return $customer->sources->data;


	    }

	    public static function setDefultCard(){

	    	\Stripe\Stripe::setApiKey("sk_test_O2mj41gmPm2YLr0okKelY9FL");

	    	$customer = \Stripe\Customer::retrieve("cus_AJ2dn7nKcAEHcK");

	    	$customer->default_source = "card_19yQZ3Lmdls5mzOxhNX4wKaj";

	    	$customer->save();


	    }

	    public static function setCharge(){
	    	\Stripe\Stripe::setApiKey("sk_test_O2mj41gmPm2YLr0okKelY9FL");

			\Stripe\Charge::create(array(
			  "amount" => 5666,
			  "currency" => "cad",
			  "customer" => "cus_AJ2dn7nKcAEHcK" // obtained with Stripe.js
			));
	    }

	    public static function refund(){
	    	\Stripe\Stripe::setApiKey("sk_test_O2mj41gmPm2YLr0okKelY9FL");

			$re = \Stripe\Refund::create(array(
			  "charge" => "ch_1A0v6ILmdls5mzOxfmPSopFY"
			));
	    }
	}
?>