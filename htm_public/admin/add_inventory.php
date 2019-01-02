<?php
session_start();

require (__DIR__ . '/../config.inc.php');
include('./includes/header.php'); // ==== HTML ==
require('../includes/product_functions.inc.php');


require(PDO_ADMIN);

$dbc = dbConn::getConnection();



require('./class/Administrator.php');
//require (MODELS. 'Order.php');


// Check for a form submission:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {	

	// Check for a added inventory:
	if (isset($_POST['add']) && is_array($_POST['add'])) {
		
		/* 
		// Define the two queries:
		$q1 = 'UPDATE specific_coffees SET stock=stock+? WHERE id=?';
		$q2 = 'UPDATE non_coffee_products SET stock=stock+? WHERE id=?';
		
		// Prepare the statements:
		$stmt1 = mysqli_prepare($dbc, $q1);
		$stmt2 = mysqli_prepare($dbc, $q2);
		
		// Bind the variables:
		mysqli_stmt_bind_param($stmt1, 'ii', $qty, $id);
		mysqli_stmt_bind_param($stmt2, 'ii', $qty, $id);
		*/
		
		// Count the number of affected rows:
		$affected = 0;		
		
					
		// Loop through each submitted value:
		foreach ($_POST['add'] as $sku => $qty) {
			
			// Validate the added quantity:
			if (filter_var($qty, FILTER_VALIDATE_INT, array('min_range' => 1))) {

				// Parse the SKU:
				list($type, $id) = parse_sku($sku);
				
				// Determine which query to execute based upon the type:
				if ($type === 'coffee') {
					
					// mysqli_stmt_execute($stmt1);					
					// $affected += mysqli_stmt_affected_rows($stmt1); // Add to the affected rows:

				} elseif ($type === 'goodies') {
					
					// mysqli_stmt_execute($stmt2);
					// $affected += mysqli_stmt_affected_rows($stmt2);	 // Add to the affected rows:
										
					try {
						$q1 = 'UPDATE products SET stock=stock+:newStock WHERE id=:ID';
						
						$smtp = $dbc->prepare($q1);
						$smtp->bindParam(':newStock', $qty);
						$smtp->bindParam(':ID', $id);
						$r = $smtp->execute();
						
						// Add to the affected rows:
						// $affected += mysqli_stmt_affected_rows($stmt2);		
						$affected ++;		 
					
					} catch (PDOException $ex) {	
						exit('Error Exception 87');
					} catch (Exception $ex) {	
						exit('Error Exception 897');
					}
					
				}
				
			}

		} // End of FOREACH.
		
		// Print a message:
		echo '
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success">
							'. $affected . ' Items(s) Were Updated!
						</div>
					</div>
				</div>
			</div>
		';
		//echo "<h4>$affected Items(s) Were Updated!</h4>";

	} // End of $_POST['add'] IF.

} 

?>

<div class="container">	
	<div class="row">	
		<div class="col-md-12">
			<h3>Add Inventory</h3>
			
			<form action="add_inventory.php" method="post" accept-charset="utf-8">

				<fieldset><legend>Indicate how many additional quantity of each product should be added to the inventory.</legend>
				
				<table class="table table-striped">
					<thead>
						<tr>
						<th>Item</th>
						<th>Normal Price</th>
						<th>Quantity in Stock</th>
						<th>Add</th>
					  </tr>
				    </thead>
					  
					<tbody>		
					<?php
					
					// Fetch every product:
					$q = '			
						SELECT CONCAT("G", p.id) AS sku, 
						c.category, 
						p.name, 
						FORMAT(p.price/100, 2) AS price, 
						p.stock 
						FROM products AS p 
						INNER JOIN categories AS c ON c.id=p.category_id 
						ORDER BY category, name			
					';
					
					/*
					$r = mysqli_query($dbc, $q);
					
					// Display form elements for each product:
					while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
						echo '<tr>
						<td align="right">' . htmlspecialchars($row['category']) . '::' . htmlspecialchars($row['name']) . '</td>
						<td align="center">' . $row['price'] .'</td>
						<td align="center">' . $row['stock'] .'</td>
						<td align="center"><input type="text" name="add[' . $row['sku'] . ']"  id="add[' . $row['sku'] . ']" size="5" class="small" /></td>
					  </tr>';
					}
					*/
					
					try {
						
						// $smtp = $dbc->prepare($q);						
						// $smtp->execute();
						$stmt = $dbc->query($q);
						
						
						// display results
						while($row = $stmt->fetch()) {
							// echo $row['name'] . ' by ' . $row['chef'] . "\n";#
							echo '
							<tr>
								<td>' . htmlspecialchars($row['category']) . '::' . htmlspecialchars($row['name']) . '</td>
								<td>' . $row['price'] .'</td>
								<td style="color:red;">' . $row['stock'] .'</td>
								<td><input type="text" name="add[' . $row['sku'] . ']"  id="add[' . $row['sku'] . ']" size="5" class="small" /></td>
							</tr>';
						}
						
					} catch (PDOException $ex) {	
						// exit('Exception 150');
						echo '<h3>'. $ex->getMessage() .'</h3>';
					}  catch (Exception $ex) {
						exit('Exception 10');
					}		
					
					?>

					</tbody>
				</table>
				
				<hr />
				<input type="submit" value="Add The Inventory" class="btn btn-success" />
				
				</fieldset>
			</form>

	
			
		</div>	
	</div>
</div>


<?php	include('./includes/footer.php'); ?>