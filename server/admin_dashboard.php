<?php

require_once 'db_conn.php';
require_once 'global.php';

use Globals\Globals; 


// render students list
echo "<h1>Students List</h1>";

$students_list = $mysqli->query("SELECT * FROM students");

if($students_list->num_rows > 0){
    $thead = "";
    $tbody = "";
    $isThead = false;
    while($row = $students_list->fetch_assoc()){
        $t = $isThead;
        $tbody.= "<tr>";
        foreach($row as $key => $value){
            if(!$t){
                $thead .= "<th style='text-align:center'>$key</th>"; 
            }
            
            $tbody .= "<td style='text-align:center'>".($value ? $value: '--')."</td>";
        }
        $tbody .= "</tr>";
        if($thead){
            $isThead = true;
        }
    }
    $tbody = "<tr>".$tbody."</tr>";
    echo "
            <table>
                <thead>
                    $thead
                </thead>
                <tbody>
                    $tbody
                </tbody>
            </table>    
        ";

}else{
    echo "No students found";
}




// render school list
echo "<h1>School List</h1>";

$school_list = $mysqli->query("SELECT * FROM school_list");
if($school_list->num_rows > 0){
    $tbody = "";
    while($row = $school_list->fetch_assoc()){
        $tbody.= "
            <tr>
             <td>".$row['id']."</td>
             <td>".$row['school_name']."</td>
            </tr>
            ";
    }
    echo "<table>
            <thead>
                <th>ID</th>
                <th>School Name</th>
            </thead>
            <tbody>
                $tbody
            </tbody>
        </table>";

}else{
    echo "No schools found";
}





// render student_grades
echo "<h1>School student grades</h1>";

$school_list = $mysqli->query("SELECT * FROM student_grades");
if($school_list->num_rows > 0){
    $tbody = "";
    while($row = $school_list->fetch_assoc()){
        $tbody.= "
            <tr>
             <td>".$row['id']."</td>
             <td>".$row['grades']."</td>
            </tr>
            ";
    }
    echo "<table>
            <thead>
                <th>ID</th>
                <th>grades</th>
            </thead>
            <tbody>
                $tbody
            </tbody>
        </table>";

}else{
    echo "No grades data found";
}