<?php

require_once 'db_conn.php';
require_once 'global.php';

use Globals\Globals; 

// get school list
if( isset($_GET['type']) && $_GET['type'] === 'get' ){

    $result = $mysqli->query("SELECT grades FROM student_grades ORDER BY id ASC ");
    
    $grade = array();
    while ($row = $result->fetch_assoc()) {
        $grade[] = $row['grades'];
    }

    $result -> free_result();
    $mysqli -> close();

    Globals::Response(200,'success',$grade);
}else if( isset($_GET['type']) && $_GET['type'] === 'insert' && isset($_GET['grade']) && !empty($_GET['grade']) ){
        // currently not implemented in frontend
    
        $grade = $_GET['grade'];
    
        $result = $mysqli->query("INSERT INTO student_grades (grades) VALUES ('$grade')");
        
        $mysqli -> close();
    
        Globals::Response(200,"School added successfully");
}else{
    Globals::Response(400,"Bad request");
}
