<?php
require (__DIR__ . '/../config.inc.php');	



/* 
require(PDO);
try {
    $dbc = dbConn::getConnection();
} catch (Exception $ex) {    
    exit("<h3>An Error Occured, We apologise</h3>");
}
 * 
 */

include(ROOT. '/includes/header.php'); // includes/header.php
?>

<div class="container" style="margin-top: 3em;">
    <div class="row">
        <div class="col-12">
            
            <article>
                <h3>Wholesale Pistachio and Walnuts</h3>
                <p>Please call us If you require large quantities of walnut, almonds and pistachios. </p>
            </article>
            <article>
                <h3>Fruit and veg delivery to your office</h3>
                <p>Please call us If you have an office and would like your office employees to be healthy. We deliver FREE within London, and 
                our prices are very competitive. Good quality with the lowest prices.</p>
            </article>
            
        </div>
    </div>
</div>

<?php include(INCLUDES. 'footer.php');  ?>
	