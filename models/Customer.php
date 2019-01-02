<?php

class Customer {
	
	
        public function __construct() {

        }
	
	
	/*
	*
	*/
	public static function add_customer($dbc, $e, $f, $l, $a1, $a2, $c,  $post_code, $p )  {
            
        $customerLastInsertID = 0; //FALSE
           
		$q = "
		INSERT INTO customers (email, first_name, last_name, address1, address2, city,  post_code, phone) 
		VALUES (:e, :f, :l, :a1, :a2, :c, :z, :p);
		";
	
		$stmt = $dbc->prepare($q); 
		
		$stmt->bindParam(':e', $e);											
		$stmt->bindParam(':f', $f);
		$stmt->bindParam(':l', $l);
		$stmt->bindParam(':a1', $a1);
		$stmt->bindParam(':a2', $a2);
		$stmt->bindParam(':c', $c);                
		$stmt->bindParam(':z', $post_code);
		$stmt->bindParam(':p', $p);
		
		//PDOStatement->execute() returns true on success for INSERT
		if( $stmt->execute() ) {                    
			//IF INSERT was successfull, Retrieve the customer ID:
			$customerLastInsertID = $dbc->lastInsertId();                     
			return $customerLastInsertID;
		} 
	}
	
		/*
         * Fetch all customers from table. Used by adming staff
         */
        public static function getAllCustomers($dbc) {
            
			$q = 'SELECT * FROM customers';
			$stmt = $dbc->query($q);
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $r;            
        }
	
} //End Cart