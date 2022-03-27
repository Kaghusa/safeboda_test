<?php
header("Content-Type:application/json");
require_once '../core/init.php'; 
// Hash::requestValidation();

# time(YMDHIS)::4dkbd::event_token

$response            = array();
$response['status']  = BAD_REQUEST;
$response['message'] = "Bad Request";
 
# Check API Request service Type :: GET
$thread=time();
if(Input::checkInput('service', 'get', '1')){
    $service = Input::get('service', 'get');
    
    switch ($service){
        
        case "promo":

            if(Input::checkInput('request', 'get', '1')){
                $request = Input::get('request', 'get');
                switch($request){
                    case "generate":
                        # get request data
                            $_POST_DATA_JSON     = file_get_contents('php://input');
                            $_POST_DATA          = json_decode(str_replace("\\", "",$_POST_DATA_JSON),true);
                           
                            if(!empty($_POST_DATA)){
                                // log received data
                                Functions::Log(LOANLOG,"RECEIVED PROM REQUEST:", json_encode($_POST_DATA),$thread);
                                $validParameters = true;
                                $_POST_DATA       = (Array)  $_POST_DATA;
                                if(!DataValidation::dataContains("event_code", $_POST_DATA_JSON)) $validParameters = false;
                                if(!DataValidation::dataContains("promo_expiry_date", $_POST_DATA_JSON)) $validParameters = false;
                                if(!DataValidation::dataContains("promo_code_amount", $_POST_DATA_JSON)) $validParameters = false;
                                if(!DataValidation::dataContains("event_venue_radius", $_POST_DATA_JSON)) $validParameters = false;
                                
                                
                                if ($validParameters){
                                    $event_code =$_POST_DATA ['event_code'];
                                    $promo_expiry_date =$_POST_DATA ['promo_expiry_date'];
                                    $promo_code_amount =$_POST_DATA ['promo_code_amount'];
                                    $event_venue_radius =$_POST_DATA ['event_venue_radius'];
                                    $event_status =EventController::checkEventStatus($event_code);
                                    Functions::Log(LOANLOG,"EVENT STATUS: ", $event_status,$thread);
                                    if($event_status=="Y"){
                                        // event validation
                                        $event_data=EventController::getEventDataByCode($event_code);
                                        Functions::Log(LOANLOG,"EVENT DATA: ", json_encode($event_data),$thread);
                                        if($event_data){
                                            // expiry date validation  by calculation numbers of day between it and current , it should be greater or equal to today's date 
                                            $number_of_Days=Dates::getNumberOfDaysBetweenTwoDates(date('Y-m-d'),$promo_expiry_date);
                                            Functions::Log(LOANLOG,"Number of days from today to promo expiry date is ", $number_of_Days,$thread);
                                            if ($promo_expiry_date && $number_of_Days>=1){
                                                //promo amount validation based on the minimum amount configured in init file 
                                                if($promo_code_amount>=PROMOMINUMAMOUNT){
                                                    if($event_venue_radius>0){
                                                     // Promo code generation and data insertion in the db 
                                                     $promo_code=PromoCodeController::generatePromoCode(8);
                                                     Functions::Log(LOANLOG,"Generated Promo code", $promo_code,$thread);
                                                     $promo_data=array(
                                                        'event_code'=>$event_code,
                                                        'promo_code'=>$promo_code,
                                                        'amount'=>$promo_code_amount,
                                                        'radius'=>$event_venue_radius,
                                                        'expirty_date'=>$promo_expiry_date,
                                                        'created_at'=>Dates::timestamp(),
                                                     );
                                                     Functions::Log(LOANLOG,"Promo data",  json_encode($promo_data),$thread);
                                                     if(PromoCodeController::Add($promo_data)){
                                                        $response['status']  =SUCCESS ;
                                                        $response['promo_code']  =$promo_code ;
                                                        $response['message'] = "Promo code generated successfully";
                                                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                                     }else {
                                                        $response['status']  =FAILLURE ;
                                                        $response['message'] = "ERROR occured while generated promo code ";
                                                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                                     }

                                                }else {
                                                    $response['status']  =FAILLURE ;
                                                    $response['message'] = "Enter valid Radius";
                                                    Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                                }
                                                    
                                                }else {
                                                    $response['status']  =FAILLURE ;
                                                    $response['message'] = "Promo amount is less than the configured which is ".PROMOMINUMAMOUNT." ".CURRENCY;
                                                    Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);

                                                }

                                               


                                            }else {
                                                $response['status']  =BAD_REQUEST ;
                                                $response['message'] = "Wrong date value or type";
                                                Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);

                                            }
                                            

                                        }else {
                                            $response['status']  =FAILLURE ;
                                            $response['message'] = "Unable to get user data";
                                            Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                        }

                                       

                                    }else if($event_status=="D") {

                                        $response['status']  =NOT_REGISTERED ;
                                        $response['message'] = "Event is not active";
                                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);

                                    }else if ($event_status=="N"){
                                        $response['status']  =NOT_REGISTERED ;
                                        $response['message'] = "Event not found";
                                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                    }

                                    

                                }else{
                                    $response['status']  = BAD_REQUEST;
                                    $response['message'] = "Invalid Body data found";
                                    Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                }




                            }else {
                                $response['status']  = BAD_REQUEST;
                                $response['message'] = "No Body data found";
                                Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                            }

                    break;


                    case "deactivate":
                        # get request data
                            $_POST_DATA_JSON     = file_get_contents('php://input');
                            $_POST_DATA          = json_decode(str_replace("\\", "",$_POST_DATA_JSON),true);
                           
                            if(!empty($_POST_DATA)){
                                // log received data
                                Functions::Log(LOANLOG,"RECEIVED  REQUEST:", json_encode($_POST_DATA),$thread);
                                $validParameters = true;
                                $_POST_DATA       = (Array)  $_POST_DATA;
                                if(!DataValidation::dataContains("promo_code", $_POST_DATA_JSON)) $validParameters = false;
                                
                                if ($validParameters){
                                    $promo_code =$_POST_DATA ['promo_code'];
                                    $promo_status =PromoCodeController::checkPromoCodeStatus($promo_code);
                                    Functions::Log(LOANLOG,"PROMO STATUS: ", $promo_status,$thread);
                                    if($promo_status=="Y"){
                                        // promo validation
                                        $promo_data=PromoCodeController::getPromoCodeDataByCode($promo_code);
                                        Functions::Log(LOANLOG,"PROMO DATA: ", json_encode($promo_data),$thread);
                                        if($promo_data){
                                                
                                            $promo_data=array(
                                                'status'=>'D',
                                                        
                                            );
                                            $conditions=array(
                                                'promo_code'=>$promo_code,
                                                        
                                            );
                                            Functions::Log(LOANLOG,"Promo edit data",  json_encode($promo_data),$thread);
                                            if(PromoCodeController::updateEntries ($promo_data,$conditions)){
                                                $response['status']  =SUCCESS ;
                                                $response['message'] = "Promo code deactivated successfully";
                                                Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                            }else {
                                                $response['status']  =FAILLURE ;
                                                $response['message'] = "ERROR occured while deactivating promo code ";
                                                Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                            }

                                        }else {
                                            $response['status']  =FAILLURE ;
                                            $response['message'] = "Unable to get user data";
                                            Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                        }

                                       

                                    }else if($promo_status=="D") {

                                        $response['status']  =NOT_REGISTERED ;
                                        $response['message'] = "Promo is not active";
                                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);

                                    }else if ($promo_status=="N"){
                                        $response['status']  =NOT_REGISTERED ;
                                        $response['message'] = "Promo not found";
                                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                    }

                                    

                                }else{
                                    $response['status']  = BAD_REQUEST;
                                    $response['message'] = "Invalid Body data found";
                                    Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                }




                            }else {
                                $response['status']  = BAD_REQUEST;
                                $response['message'] = "No Body data found";
                                Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                            }

                    break;


                    case "all":
                       $promo_data=PromoCodeController::getAllPromoCodes();
                       if($promo_data){
                        $response['status']  = SUCCESS;
                        $response['message'] = "Data found";
                        $response['count'] = count($promo_data);
                        $response['data'] = $promo_data;
                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                       }else {
                        $response['status']  = FAILLURE;
                        $response['message'] = "No data found";
                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                       }
                    break;
                    case "active":
                        $promo_data=PromoCodeController::getActivePromoCodes();
                        if($promo_data){
                            $response['status']  = SUCCESS;
                            $response['message'] = "Data found";
                            $response['count'] = count($promo_data);
                            $response['data'] = $promo_data;
                            Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                        }else {
                            $response['status']  = FAILLURE;
                            $response['message'] = "No data found";
                            Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                        }
                     break;

                     case "redeem":
                        # get request data
                            $_POST_DATA_JSON     = file_get_contents('php://input');
                            $_POST_DATA          = json_decode(str_replace("\\", "",$_POST_DATA_JSON),true);
                           
                            if(!empty($_POST_DATA)){
                                // log received data
                                Functions::Log(LOANLOG,"RECEIVED  REQUEST:", json_encode($_POST_DATA),$thread);
                                $validParameters = true;
                                $_POST_DATA       = (Array)  $_POST_DATA;
                                if(!DataValidation::dataContains("promo_code", $_POST_DATA_JSON)) $validParameters = false;
                                if(!DataValidation::dataContains("origin_latitude", $_POST_DATA_JSON)) $validParameters = false;
                                if(!DataValidation::dataContains("origin_longitude", $_POST_DATA_JSON)) $validParameters = false;
                                if(!DataValidation::dataContains("destination_latitude", $_POST_DATA_JSON)) $validParameters = false;
                                if(!DataValidation::dataContains("destination_longitude", $_POST_DATA_JSON)) $validParameters = false;
                                
                                if ($validParameters){
                                    $promo_code =$_POST_DATA ['promo_code'];
                                    $origin_latitude =$_POST_DATA ['origin_latitude'];
                                    $origin_longitude =$_POST_DATA ['origin_longitude'];
                                    $destination_latitude =$_POST_DATA ['destination_latitude'];
                                    $destination_longitude =$_POST_DATA ['destination_longitude'];
                                    
                                    $promo_status =PromoCodeController::checkPromoCodeStatus($promo_code);
                                    
                                    Functions::Log(LOANLOG,"PROMO STATUS: ", $promo_status,$thread);
                                    if($promo_status=="Y"){
                                        // promo validation
                                        $promo_data=PromoCodeController::getPromoCodeDataByCode($promo_code);
                                        Functions::Log(LOANLOG,"PROMO DATA: ", json_encode($promo_data),$thread);
                                        if($promo_data){
                                            $number_of_Days=Dates::getNumberOfDaysBetweenTwoDates(date('Y-m-d'),$promo_data->expirty_date);
                                            if($number_of_Days>0){

                                                $distance=Functions::getDistanceInKm($origin_latitude,$origin_longitude,$destination_latitude, $destination_longitude)    ; 
                                                Functions::Log(LOANLOG,"DISTANCE  IS : ", $distance,$thread);   
                                                
                                                if($distance<=$promo_data->radius){
                                                    $response['status']  = SUCCESS;
                                                    $response['message'] = "Promo Code redeemed successfully";
                                                    $response['data'] = $promo_data;

                                                }else {
                                                    $response['status']  =FAILLURE ;
                                                    $response['message'] = "Origin  or destination are not  within  radius of the event venue";
                                                    Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                                }
                                            }else {
                                                $response['status']  =FAILLURE ;
                                                $response['message'] = "Promo Code expired";
                                                Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                            }
                                           
                                            

                                        }else {
                                            $response['status']  =FAILLURE ;
                                            $response['message'] = "Unable to get user data";
                                            Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                        }

                                       

                                    }else if($promo_status=="D") {

                                        $response['status']  =NOT_REGISTERED ;
                                        $response['message'] = "Promo is not active";
                                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);

                                    }else if ($promo_status=="N"){
                                        $response['status']  =NOT_REGISTERED ;
                                        $response['message'] = "Promo not found";
                                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                    }

                                    

                                }else{
                                    $response['status']  = BAD_REQUEST;
                                    $response['message'] = "Invalid Body data found";
                                    Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                                }




                            }else {
                                $response['status']  = BAD_REQUEST;
                                $response['message'] = "No Body data found";
                                Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                            }

                    break;
                    default:
                        $response['status']  = BAD_REQUEST;
                        $response['message'] = "Invalid  service Request";
                        Functions::Log(LOANLOG,"RESPONSE SENT: ", json_encode($response),$thread);
                    break;
                }
                    


            }else {
                $response['status']  = BAD_REQUEST;
                $response['message'] = "Payment service request does not exist";

            }
        break;
        default:
            $response['status']  = BAD_REQUEST;
            $response['message'] = "Invalid payment service";
        break;

    }
}

echo json_encode($response);