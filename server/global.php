<?php

namespace Globals;

class Globals {

    public static function GetIpAddress(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


    public static function Response($status, $status_message, $data=null) {
        $response['status'] = $status;
        $response['message'] = $status_message;
        $response['data'] = $data;
        exit(json_encode($response));
    }


    public static function Validator($validate=[],$rules=[],$errors=[]){
        
        $result = array();
        $isError = false;
        $errors = array();


        foreach($rules as $key=>$val){

            if($isError){
                break;
            }

            $rules_array = explode(",",$val);

            $value = $validate[$key];
            
             // required
            if(in_array('required',$rules_array)){
                if(!isset($validate[$key]) || empty($validate[$key])){
                    $errors[$key] = "The $key field is required";
                    $isError = true;
                }
            }

            // required_if
            $required_if = explode(':',$rules_array[0]);
            if($required_if[0] === 'required_if'){
                if(isset($required_if[1])){
                    if(!empty($validate[$required_if[1]]) && empty($validate[$key])){
                        $errors[$key] = "The $key field is required";
                        $isError = true;
                    }
                }
            }

             // string
            if(in_array('string',$rules_array)){
                // convert to string if not empty
                if(!empty($validate[$key])){
                    $value = (string)$validate[$key];
                }
            }

            // lowercase
            if(in_array('lowercase',$rules_array)){
                // convert to string if not empty
                if(!empty($validate[$key])){
                    $value = strtolower($validate[$key]);
                }
            }

            // integer
            if(in_array('int',$rules_array)){
                // convert to integer if not empty
                if(!empty($validate[$key])){
                    $value = (int)$validate[$key];
                }
            }

            // boolean
            if(in_array('boolean',$rules_array)){
                // convert to boolean if not empty
                if(!empty($validate[$key])){
                    $value = (boolean)$validate[$key];
                }
            }

            // email
            if(in_array('email',$rules_array)){
                if(!empty($validate[$key])){
                    if(!filter_var($validate[$key], FILTER_VALIDATE_EMAIL)){
                        $errors[$key] = "The $key field is not a valid email";
                        $isError = true;
                    }
                }
            }

            // nullable
            if(in_array('nullable',$rules_array)){
                if(!isset($validate[$key]) || empty($validate[$key])){
                    $value = null;
                }
            }

            $result[$key] = $value;

        }


        return array(
            'is_error' => $isError,
            'errors' => $errors,
            'result' => $result,
            'firstError' => $errors[key($errors)]
        );
        
    }

}

?>