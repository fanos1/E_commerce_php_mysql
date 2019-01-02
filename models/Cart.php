<?php

class Cart {

    public function __construct() {
        
    }

    public static function update_cart($dbc, $uid, $sp_type, $pid, $qty) {

        $result = FALSE;

		if ($qty > 0) {
			$q = "
				UPDATE carts 
				SET quantity = :qty, date_modified = NOW() 
				WHERE user_session_id = :uid 
				AND product_code = :type 
				AND product_id = :pid
				";

			$stmt = $dbc->prepare($q);
			//bind $uid
			$stmt->bindParam(':qty', $qty);
			$stmt->bindParam(':uid', $uid);
			$stmt->bindParam(':type', $sp_type);
			$stmt->bindParam(':pid', $pid);

			$r = $stmt->execute();

			if ($r) {
				$result = TRUE;
			}
		} else if ($qty == 0) {
			//Calling a static method() from another method() we call it with self::
			self::remove_from_cart($dbc, $uid, $sp_type, $pid);
		}

		return $result;
			       
    }

    public static function move_to_cart($dbc, $uid, $sp_type, $pid, $qty) {

            $q1 = "
                    SELECT id FROM carts 
                    WHERE user_session_id = :uid 
                    AND product_type = :type 
                    AND product_id = :pid
                    ";

            $stmt = $dbc->prepare($q1);

            //bind $uid
            $stmt->bindParam(':uid', $uid);
            $stmt->bindParam(':type', $sp_type);
            $stmt->bindParam(':pid', $pid);

            $stmt->execute();
            $r1 = $stmt->fetch(PDO::FETCH_ASSOC);

            $cid = $r1['id'];

            /*
              This procedure will be called when the customer clicks a link.
              In that case, a simple INSERT is all that’s necessary.
              However, if the user clicks the same link again later—thereby adding to the cart something already in the cart,
              another INSERT should NOT be executed. The decision I made in the book is to assume that
              the customer purposefully wanted to add another quantity of the same
              item to the cart in such instances. So the stored procedure runs an UPDATE query when the item exists in the cart
             */

            if ($cid > 0) { // it will be 1 IF current user HAS ALREADY added this  item before into table.
                //UPDATE cart for this item if this user has already added this item to his cart. Increment by 1				
                $q2 = "
                        UPDATE carts 
                        SET quantity = quantity + 1, date_modified = NOW() 
                        WHERE id = :cid
                        ";
                $stmt = $dbc->prepare($q2);
                $stmt->bindParam(':cid', $cid);
                $r2 = $stmt->execute();

                if (!$r2) {
                    exit("<h1>INSERT QUERy failed, satr 118, Cart Class </h1>");
                }
                return $r2;
            } else { // IF this use has not alread added this item in Cart, INSERT! 
                $q2 = "
                        INSERT INTO carts (user_session_id, product_type, product_id, quantity) 
                        VALUES (:uid, :type, :pid, :qty)
                        ";
                $stmt = $dbc->prepare($q2);
                $stmt->bindParam(':uid', $uid);
                $stmt->bindParam(':type', $sp_type);
                $stmt->bindParam(':pid', $pid);
                $stmt->bindParam(':pid', $qty);

                $r2 = $stmt->execute();

                if (!$r2) {
                    exit("<h1>INSERT QUERy failed, satr 135, Cart Class </h1>");
                }

                return $r2;
            }
        
    }

    public static function add_to_cart($dbc, $uid, $sp_type, $pid, $qty, $size) {

        $result = false;

		$q1 = "
				SELECT id 
				FROM carts 
				WHERE user_session_id = :uid 
				AND product_code = :type 
				AND product_id = :pid
				";

		$stmt = $dbc->prepare($q1);

		if ($stmt) {
			$stmt->bindParam(':uid', $uid);
			$stmt->bindParam(':type', $sp_type);
			$stmt->bindParam(':pid', $pid);

			$r1 = $stmt->execute();
			if ($r1) {
				$rows = $stmt->fetchAll();
			}
		}

		$howManyRows = count($rows);


		if ($howManyRows > 0) { //it means this user has already added THIS current item to cart, UPDATE
			$cid = $rows[0]['id'];

			//UPDATE cart for this item if this user has already added this item to his cart. Increment by 1				                        
			$q2 = "
						UPDATE carts 
						SET quantity = quantity + 1, date_modified = NOW() 
						WHERE id = :cid
						";
			$stmt = $dbc->prepare($q2);
			$stmt->bindParam(':cid', $cid);
			$r2 = $stmt->execute();

			if (!$r2) {
				exit("Error with Cart, str184");
			} else {
				//return TRUE; //UPDATE query successfull
				$result = TRUE;
			}
		} else {
			// INSERT new Row Record
			$q2 = "
						INSERT INTO carts (user_session_id, product_id, product_code, quantity, size) 
						VALUES (:uid, :pid, :type, :qty, :size)
						";
			$stmt = $dbc->prepare($q2);

			$stmt->bindParam(':uid', $uid);
			$stmt->bindParam(':type', $sp_type);
			$stmt->bindParam(':pid', $pid);
			$stmt->bindParam(':qty', $qty);
			$stmt->bindParam(':size', $size);

			$r2 = $stmt->execute();

			if (!$r2) {
				exit("Error with Cart, str204");
			} else {
				//return TRUE; //INSERT query successfull
				$result = TRUE;
			}
		}

		return $result;
       
    }

    /*
     * CALL remove_from_cart('$uid', '$type', $pid)")
     */

    public static function remove_from_cart($dbc, $uid, $sp_type, $pid) {

		$q = "
			DELETE FROM carts 
			WHERE user_session_id = :uid 
			AND product_code = :type 
			AND product_id = :pid
			";

		$stmt = $dbc->prepare($q);
		$stmt->bindParam(':uid', $uid);
		$stmt->bindParam(':type', $sp_type);
		$stmt->bindParam(':pid', $pid);
		$r = $stmt->execute();

		if (!$r) {
			return FALSE; //could no remove
		} else {
			return TRUE;
		}
        
    }

    /*
     * Delete everyting from cart. This method is called when user has paid for item successfully         
     */
    public static function clearCart($dbc, $uid) {

        
		//DELETE FROM carts WHERE user_session_id=uid;
		$q = " DELETE FROM carts WHERE user_session_id=:uid ";

		$stmt = $dbc->prepare($q);
		$stmt->bindParam(':uid', $uid);
		$r = $stmt->execute();

		if (!$r) {
			return FALSE; //could no remove
		} 
		else {
			return TRUE;
		}
        
    }

    /*
     *
     */

    public static function get_shopping_cart_contents($dbc, $uid) {

            $q = "
                SELECT CONCAT('G', p.id) AS sku, 
                c.quantity, 
                cat.category,
                c.size,
                c.product_id,
                c.product_code,
                p.name, 
                p.price, 
                p.stock, 
                p.image,
                s.size AS size_name,
                sales.price AS sale_price 
                FROM carts AS c 
                INNER JOIN products AS p ON c.product_id = p.id 
                INNER JOIN categories AS cat ON cat.id = p.category_id 
                INNER JOIN sizes AS s ON s.id = c.size 
                LEFT OUTER JOIN sales ON (sales.product_id = p.id                         
                        AND ((NOW() BETWEEN sales.start_date AND sales.end_date) 
                        OR (NOW() > sales.start_date AND sales.end_date IS NULL)) 
                ) 
                WHERE c.user_session_id = :uid
                ";

            $stmt = $dbc->prepare($q);
            $stmt->bindParam(':uid', $uid);
            $stmt->execute();
            //$r = $stmt->fetch(PDO::FETCH_ASSOC	);
            $r = $stmt->fetchAll();

            /*
              sku 	quantity 	category    size	name 	price 	stock 	sale_price   image
              G1 	4               Cardigans   s/m         item1 	650 	100 	500          xxx.jpg
             */

            return $r;
        
    }
    
    /*
    * Get all products from the shopping Basket, and list them to which categor they belong
    * output the ID of category
    */
    public static function getProductsByCategId($dbc, $uid) {
        
            $q = "                            
            SELECT 
            cat.id,
            cat.category
            FROM carts AS c 
            INNER JOIN products AS p ON c.product_id = p.id 
            INNER JOIN categories AS cat ON cat.id = p.category_id 
            WHERE c.user_session_id = :uid
            ";

            $stmt = $dbc->prepare($q);
            $stmt->bindParam(':uid', $uid);
            $stmt->execute();
            //$r = $stmt->fetch(PDO::FETCH_ASSOC	);
            $r = $stmt->fetchAll();
            return $r;

         
    }

}

//End Cart