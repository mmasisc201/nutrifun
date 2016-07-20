<?php

session_start();

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'POST'){
    
    $result = array();
    
    try {
        $json = file_get_contents("php://input");
        $data = strip_tags($json);

        $user = json_decode(trim($data), TRUE);    


        $credentials = array();   
        $credentials['key'] = $user['idPlayer'];
        $credentials['token'] = $user['token'];
        $credentials['country'] = $user['country'];

        $_SESSION['credentials'] = $credentials;
        
        $result['header']['status'] = 'success';
        
    } catch (Exception $exc) {
        $result['header']['status'] = 'fail';
        $result['header']['message'] = $exc->getMessage();
        //echo $exc->getTraceAsString();
    }    
    
    echo json_encode($result);
    
} else if(!isset($_SESSION['credentials'])){
	header('Location: ./login');
}