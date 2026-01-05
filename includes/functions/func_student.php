<?php

function student_list($pdo) {    
       
        $query = "SELECT lrn, student_name, section, sex, age  FROM student_tbl";         
        $statement = $pdo->query($query);
        return $statement->fetchAll();
       
}

?>