<?php

require_once 'db_conn.php';
require_once 'global.php';

use Globals\Globals; 

// get school list
if( isset($_GET['type']) && $_GET['type'] === 'get' ){

    $result = $mysqli->query("SELECT school_name FROM school_list ORDER BY id ASC ");
    
    $schools = array();
    while ($row = $result->fetch_assoc()) {
        $schools[] = $row['school_name'];
    }

    // $data = json_encode();
    $result -> free_result();
    $mysqli -> close();

    Globals::Response(200,'success',$schools);
}else if( isset($_GET['type']) && $_GET['type'] === 'insert' && isset($_GET['school_name']) && !empty($_GET['school_name']) ){
        // currently not implemented in frontend
        $school_name = $_GET['school_name'];
    
        $result = $mysqli->query("INSERT INTO school_list (school_name) VALUES ('$school_name')");
        
        $mysqli -> close();
    
        Globals::Response(200,"School added successfully");
}else{
    Globals::Response(400,"Bad request");
}
