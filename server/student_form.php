<?php

require_once 'db_conn.php';
require_once 'global.php';

use Globals\Globals;


try{
    
    $rules = array(
        "stdFirstName"=> "required,string",
        "stdLastName" => "required,string",
        "stdFullName" => "required,string",
        "stdSchool" => "required,string,lowercase",
        "stdGrade" => "required:string,lowercase",
        "stdAge" => "stdGrade,int",
        "stdEmail" => "email",
        "stdPhone" => "nullable,string",
        
        "stdMotherName" => "required,string",
        "stdFatherName" => "required,string",

        "stdMotherEmail" => "email",
        "stdFatherEmail" => "email",
    );

    $validate = Globals::Validator($_POST,$rules);

    if($validate['is_error']){
        Globals::Response(400,$validate['firstError']);
    }

    $validate = $validate['result'];


    $create_at = $mysqli->real_escape_string(date("Y-m-d H:i:s"));
    $updated_at = $mysqli->real_escape_string(date("Y-m-d H:i:s"));

    $ip_address_created = $mysqli->real_escape_string(Globals::GetIpAddress());
    $ip_address_updated = $mysqli->real_escape_string(Globals::GetIpAddress());


    $std_school_id = $mysqli->query("SELECT id FROM school_list WHERE school_name = '{$validate['stdSchool']}'")->fetch_assoc()['id'];

    if(!$std_school_id){
        Globals::Response(400,"School not found");
    }

    $std_grade_id = $mysqli->query("SELECT id FROM student_grades WHERE grades = '{$validate['stdGrade']}'")->fetch_assoc()['id'];

    if(!$std_grade_id){
        Globals::Response(400,"Grade not found");
    }



    // insert into students table and get the id of student_grades table and school_list table 
    $studentTable = "
        INSERT INTO students(
            std_first_name,
            std_last_name,
            std_full_name,
            std_school,
            std_grade,
            std_age,
            std_email,
            std_phone,
            std_mother_name,
            std_father_name,
            std_mother_email,
            std_father_email,
            created_at,
            updated_at,
            ip_address_created,
            ip_address_updated
        )
        VALUES (
            '{$validate['stdFirstName']}',
            '{$validate['stdLastName']}',
            '{$validate['stdFullName']}',
            '$std_school_id',
            '$std_grade_id',
            '{$validate['stdAge']}',
            '{$validate['stdEmail']}',
            '{$validate['stdPhone']}',
            '{$validate['stdMotherName']}',
            '{$validate['stdFatherName']}',
            '{$validate['stdMotherEmail']}',
            '{$validate['stdFatherEmail']}',
            '{$create_at}',
            '{$updated_at}',
            '{$ip_address_created}',
            '{$ip_address_updated}'
        )
";


    if(!$mysqli->query($studentTable)){
        Globals::Response(500,$mysqli->error);
    }else{
        Globals::Response(200,"Student added successfully");
    }

    print_r($validate);

    



}catch(Exception $e){
    http_response_code(505);
    Globals::Response(505, 'Something went wrong');
}