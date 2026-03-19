<?php

$pdo = new PDO(
    "mysql:host=localhost;dbname=stud_my_student_id;charset=utf8mb4",
    "my_student_id",
    "my_password",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);
