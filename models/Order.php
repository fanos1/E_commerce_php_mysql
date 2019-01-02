<?php

class Order {
	
	
        public function __construct() {

        }
	
	
	/*
	 * INSERT an order INTO the ORDER table
         * Return the last inserted orderID
	*/
	public static function add_order($dbc, $cid, $uid, $ship, $delivery_slot, $cc )  {
                        
            try {
                
                //We want 2 values returned: $order_total and $order_id
                $valueToReturn = array(); 
                
                //Start TRANSACTION. WE HAVE 4 QUERIES
                $dbc->beginTransaction();
                
                //1st QUERY ============
                $q = "
                    INSERT INTO orders (customer_id, shipping, delivery_slot, credit_card_number, order_date) 
                        VALUES ( :cid, :ship, :delivery, :cc, NOW() )";
		
                $stmt = $dbc->prepare($q);                 
                $stmt->bindParam(':cid', $cid);
                $stmt->bindParam(':ship', $ship);
                $stmt->bindParam(':delivery', $delivery_slot);
                $stmt->bindParam(':cc', $cc);
                $stmt->execute();
                
                $lastOrderId = $dbc->lastInsertId();
                
                //2ND QUERY ===============
                $q = "
                    SELECT 
                    c.product_code, 
                    c.product_id, 
                    c.quantity, 
                    IFNULL(sales.price, prod.price) AS price_per
                    FROM carts AS c 
                    INNER JOIN products AS prod ON c.product_id = prod.id 
                    LEFT OUTER JOIN sales ON (
                            sales.product_id = prod.id 
                            AND ((NOW() BETWEEN sales.start_date AND sales.end_date) 
                            OR (NOW() > sales.start_date AND sales.end_date IS NULL)) 
                    ) 
                    WHERE  c.user_session_id = :uid
                ";
                $stmt = $dbc->prepare($q);                 
                $stmt->bindParam(':uid', $uid);
                $stmt->execute();
                //$result = $stmt->fetch(PDO::FETCH_ASSOC);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($rows as $array) {
                    $x1 = $array['product_code'];  //goodies
                    $x2 = $array['product_id'];    //17                    
                    $x3 = $array['quantity'];      //1
                    $x4 = $array['price_per'];     //2500

                    $res = $dbc->exec("INSERT INTO order_contents 
                        (order_id, product_code, product_id, quantity, price_per)
                            VALUES($lastOrderId, $x1, $x2, $x3, $x4 )");
                }                
                                
                
                //4th QUERY =============
                $stmt2 = $dbc->query("SELECT SUM(quantity*price_per) AS subtotal 
                        FROM order_contents WHERE order_id=$lastOrderId");
                $y = $stmt2->fetch(PDO::FETCH_ASSOC);
                //$subtotal = $y['subtotal'];
                
                $total = $y['subtotal'] + $ship;
                
                
                //QUERY  ==============              
                $dbc->exec("UPDATE orders SET total = $total  WHERE id=$lastOrderId");

                //COMMIT
                if( $dbc->commit() ) {
                    array_push($valueToReturn, $lastOrderId);
                    array_push($valueToReturn, $total);
                }
                
                return $valueToReturn;
                
            } 
			catch (Exception $ex) {                
                 //echo $ex->getMessage(); //DEBUG
                 $dbc->rollBack(); // if TRANSACTION failed, rollback
            }
			catch (PDOException $ex) {  
				$dbc->rollBack(); // if TRANSACTION failed, rollback
			}
		
	}
	
        
        public static function get_order_contents($dbc, $oid) {
            
                $q = "SELECT 
                    oc.quantity, oc.price_per, 
                    (oc.quantity*oc.price_per) AS subtotal, 
                    cat.category, 
                    prod.name, 
                    o.total, 
                    s.size,
                    o.shipping 
                    FROM order_contents AS oc 
                    INNER JOIN products AS prod ON oc.product_id=prod.id 
                    INNER JOIN categories AS cat ON cat.id = prod.category_id 
                    INNER JOIN orders AS o ON oc.order_id = o.id 
                    INNER JOIN sizes AS s ON ( s.id = prod.size )
                    WHERE oc.order_id = :oid
                ";
                $stmt = $dbc->prepare($q);                 
                $stmt->bindParam(':oid', $oid);
                $stmt->execute();
                $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $r;                
                
            
        }


        /*
         * INSERTs INOT order_content TABLE the SLELECT subquery
         */
        public static function add_charge($dbc, $chargeId, $orderIdLast, $transacType, $orderTotal, $fullResponse )  {
              
			$q = "INSERT INTO charges VALUES (NULL, :charge_id, :oid, :trans_type, :orderTotal, :fullResponse, NOW())";
			$stmt = $dbc->prepare($q); 
			
			$stmt->bindParam(':charge_id', $chargeId);
			$stmt->bindParam(':oid', $orderIdLast);
			$stmt->bindParam(':trans_type', $transacType);
			$stmt->bindParam(':orderTotal', $orderTotal);
			$stmt->bindParam(':fullResponse', $fullResponse);
			
			$stmt->execute();
			return $stmt->rowCount();
			
			// return $stmt->execute();
        }
        
        /*
         * List all orders. Admin staff can choose to view all the orders that need processing
         */
        public static function view_orders($dbc) {
            
                //total is stored as hundreds. i.e. 1500 for £15. We devide total therefore here
                $q = '
                SELECT
                o.id, 
                FORMAT(o.total/100, 2) AS total,             
                cust.id AS cid,
                cust.delivery_slot,
                CONCAT(cust.first_name, ", ", cust.last_name ) AS name,
                o.shipping, 
                o.paid_status,
                o.order_date 
                FROM orders AS o
                LEFT OUTER JOIN order_contents AS oc ON (oc.order_id = o.id AND oc.ship_date IS NULL) 
                INNER JOIN customers AS cust ON (cust.id = o.customer_id)                
                GROUP BY o.id DESC
                ';

                //return $dbc->query($q);
                $stmt = $dbc->prepare($q);                 
                //$stmt->bindParam(':oid', $oid); //NO VALUES TO BIND
                $stmt->execute();
                $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $r;
                
            
        }
	
        /*
         * admin can view a single order, this single view will have more details
         */
         public static function view_single_order($dbc, $order_id) {
            
                $q = 'SELECT total, shipping, credit_card_number, 
                DATE_FORMAT(order_date, "%a %b %e, %Y at %h:%i%p") AS od, 
                email, 
                CONCAT(last_name, ", ", first_name) AS name,
                CONCAT_WS(" ", address1, post_code) AS address,
                phone, 
                customer_id, 
                o.delivery_slot,
                CONCAT_WS(" - ", cat.category, prod_tbl.name, product_code.name, s.size) AS item,                 
                prod_tbl.stock, 
                quantity, 
                price_per, 
                DATE_FORMAT(ship_date, "%b %e, %Y") AS sd 
                FROM orders AS o 
                INNER JOIN customers AS cust ON (o.customer_id = cust.id) 
                INNER JOIN order_contents AS oc ON (oc.order_id = o.id) 
                INNER JOIN products AS prod_tbl ON (oc.product_id = prod_tbl.id ) 
                INNER JOIN categories AS cat ON (cat.id = prod_tbl.category_id) 
                INNER JOIN product_code ON (product_code.id = prod_tbl.product_code) 
                INNER JOIN sizes AS s ON ( s.id = prod_tbl.size )
                WHERE o.id =:order_id';
                
        
                $stmt = $dbc->prepare($q);                 
                $stmt->bindParam(':order_id', $order_id); 
                $stmt->execute();
                $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $r;
                
        }
    
        
        public static function get_customer_details_by_orderID($dbc, $ord_ID) {
            
            $q = "SELECT 
                    cust.email, 
                    cust.first_name, 
                    cust.last_name, 
                    cust.address1, 
                    cust.city, 
                    cust.post_code, 
                    cust.phone                    
                    FROM customers AS cust  
                    INNER JOIN orders AS ord ON ord.customer_id = cust.id 
                    WHERE ord.id = :oid
                ";
                $stmt = $dbc->prepare($q);                 
                $stmt->bindParam(':oid', $ord_ID);
                $stmt->execute();
                $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                return $r;
            
        }
    
	
} //End Cart