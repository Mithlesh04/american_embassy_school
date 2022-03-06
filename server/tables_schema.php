<?php

require_once 'db_conn.php';


// create school_list table
$school_list = "
    CREATE TABLE IF NOT EXISTS school_list (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        school_name VARCHAR(255) NOT NULL UNIQUE
    );
  ";
if(!$mysqli->query($school_list)){
    die("Error creating student table: " . $mysqli->error);
}



// create student_grades table
$student_grades = "
    CREATE TABLE IF NOT EXISTS student_grades (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        grades VARCHAR(255) NOT NULL UNIQUE
    );
  ";

if(!$mysqli->query($student_grades)){
    die("Error creating student table: " . $mysqli->error);
}




// create student table
// student= stdFirstName, stdLastName, stdFullName, stdSchool, stdGrade, stdAge, stdEmail, stdPhone
// parent= stdMotherName, stdFatherName, stdMotherEmail, stdFatherEmail, created_at, updated_at, ip_address_created, ip_address_updated
$studentTable = "
    CREATE TABLE IF NOT EXISTS students (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        std_first_name VARCHAR(30) NOT NULL,
        std_last_name VARCHAR(30) NOT NULL,
        std_full_name VARCHAR(60) NOT NULL,
        
        std_school int not null,
        std_grade int not null,

        std_age INT(3) NULL,
        std_email VARCHAR(50) NULL,
        std_phone VARCHAR(20) NULL,
        std_mother_name VARCHAR(50) NOT NULL,
        std_father_name VARCHAR(50) NOT NULL,
        std_mother_email VARCHAR(50) NULL,
        std_father_email VARCHAR(50) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        ip_address_created VARCHAR(50) NOT NULL,
        ip_address_updated VARCHAR(50) NOT NULL,
        
        FOREIGN KEY(std_school) REFERENCES school_list(id),
        FOREIGN KEY(std_grade) REFERENCES student_grades(id)
    ) COMMENT='std_school is Foreign key of school_list. std_grade is Foreign key of student_grades.';
";


if(!$mysqli->query($studentTable)){
    die("Error creating student table: " . $mysqli->error);
}




// insert data 
$insert = "
    INSERT INTO school_list (school_name) VALUES
    ('elementary'),
    ('middle'),
    ('high');
    INSERT INTO student_grades (grades) VALUES
    ('gr 0 - gr 5'),
    ('gr 6 - gr 8'),
    ('gr 9 - gr 12');
";

$mysqli->multi_query($insert);