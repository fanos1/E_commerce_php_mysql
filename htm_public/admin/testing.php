<?php
session_start(); 

require ('../includes/config.inc.php');
require ('../includes/form_functions.inc.php');

require(PDO_ADMIN);

try {
    $dbc = dbConn::getConnection();
} catch (Exception $ex) {        
    //echo '<h3>'.$ex->getMessage().'</h3>'; //testing only    
    exit("<h3>An Error Occured, We apologise</h3>");
}

require(MODELS.'Category.php');
require(MODELS.'Product.php');



     
try{
    $q = 'INSERT INTO testing(name) VALUES(:name)';

    $stm = $dbc->prepare($q);
    $stm->bindParam(':name', $name);
    $resul = $stm->execute();


    $count = $stm->rowCount(); //returns the number of rows affected by a DELETE, INSERT, or UPDATE statement. 
    
    var_dump($count); //OUT:: (int) 1
    exit();

} catch (Exception $ex) {
    echo '<h3>';
    echo $ex->getMessage();
    echo '</h3>';
}
?>

   