<?php

class Product {

    public $id = null;

    public function __construct() {
        
    }

    /* Select Categories */

    public static function select_categories($dbc, $sp_type) {

            if ($sp_type == 'coffee') {
                $q = "SELECT * FROM general_coffees ORDER by category";
            } else {
                $q = "SELECT * FROM categories ORDER by category";
            }
            $stmt = $dbc->query($q);
            $r = $stmt->fetchAll();
            return $r;
        
    }

    /* Select Products */

    public static function select_products($dbc, $catID) {
        

		 $q = "
				SELECT 
				cat.description AS g_description, 
				cat.image AS g_image, 
				cat.h1_title,
				CONCAT('G', p.id) AS sku, 
				p.id AS product_id, 
				p.name, 
				p.description, 
				p.image, 
				p.price, 
				p.stock, 
				s.size,
				s.id AS size_id,
				p.product_code,
				sales.price AS sale_price
				FROM products AS p 
				INNER JOIN sizes AS s ON s.id = p.size
				INNER JOIN product_code AS pc ON pc.id = p.product_code
				INNER JOIN categories AS cat ON cat.id = p.category_id 
				
				LEFT OUTER JOIN sales ON (sales.product_id = p.id                             
						AND ((NOW() BETWEEN sales.start_date AND sales.end_date) 
						OR (NOW() > sales.start_date AND sales.end_date IS NULL)) 
				)
				WHERE category_id = :catID 
				ORDER by date_created DESC
				";

		// $stmt = $dbc->query($q);
		// $r = $stmt->fetchAll();                      
		// return $r;
		
		$stmt = $dbc->prepare($q);                 
		$stmt->bindParam(':catID', $catID);                
		$stmt->execute();
		//$result = $stmt->fetch(PDO::FETCH_ASSOC);
		//$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$r = $stmt->fetchAll();
		return $r;
       
    }
    
    /* 
     * qUERY for specific item details
     * this query is similar to select_product() method above, but will also display size options
     */
    public static function select_product_byID($dbc, $itemID)  {
      
            $q = "
            SELECT ncp.id, 
            ncp.category_id, 
            ncp.name, ncp.description, 
            ncp.image, ncp.price, 
            ncp.stock, 
            ncp.id AS product_id,
            s.size, 
            s.id AS size_id,
            ncp.date_created,
            ncp.product_code,
            CONCAT('G', ncp.id) AS sku,
            sales.price AS sale_price
            FROM products AS ncp
            INNER JOIN sizes AS s ON s.id = ncp.size
            LEFT OUTER JOIN sales ON (sales.product_id = ncp.id                     
                    AND ((NOW() BETWEEN sales.start_date AND sales.end_date) 
                    OR (NOW() > sales.start_date AND sales.end_date IS NULL)) 
                )
            WHERE ncp.id = :itemID           
            ";    
       

            $stmt = $dbc->prepare($q);                 
            $stmt->bindParam(':itemID', $itemID);                
            $stmt->execute();
            //$result = $stmt->fetch(PDO::FETCH_ASSOC);
            // $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $r = $stmt->fetchAll();
            return $r;

       
        
    }

    

    /* Select Products */

    public static function select_sale_items($dbc, $get_all = true) { //Default is to get All sale items
        
            if ($get_all) { //if TRUE
                $q = "
                SELECT CONCAT('G', ncp.id) AS sku, 
                    sa.price AS sale_price, 
                    ncc.category, 
                    ncp.image, 
                    ncp.name, 
                    ncp.price AS price, 
                    ncp.stock, 
                    ncp.description 
                    FROM sales AS sa 
                    INNER JOIN products AS ncp ON sa.product_id = ncp.id 
                    INNER JOIN categories AS ncc ON ncc.id = ncp.non_coffee_category_id 
                    WHERE sa.product_type = 'goodies' 
                            AND ((NOW() BETWEEN sa.start_date AND sa.end_date) 
                            OR (NOW() > sa.start_date AND sa.end_date IS NULL) 
                    )
                  
                ";
            } else { // Fetch random 2 sale items
                $q = "
                (    
                SELECT CONCAT('G', ncp.id) AS sku, 
                CONCAT('£', FORMAT(sa.price/100, 2)) AS sale_price, 
                    ncc.category, 
                    ncp.image, 
                    ncp.name 
                    FROM sales AS sa 
                    INNER JOIN products AS ncp ON sa.product_id=ncp.id 
                    INNER JOIN categories AS ncc ON ncc.id=ncp.non_coffee_category_id 
                    WHERE sa.product_type='goodies' 
                            AND ((NOW() BETWEEN sa.start_date 
                            AND sa.end_date) OR (NOW() > sa.start_date AND sa.end_date IS NULL) ) 
                    ORDER BY RAND() LIMIT 2
                    ) 
                 
                ";
            }

            $stmt = $dbc->query($q);
            $r = $stmt->fetchAll();
            return $r;
        
    }

   
    
    /* Get size from size table
     * 
     */
    public static function getSizes($dbc) {
        
		$q = 'SELECT id, size FROM sizes ORDER BY size ASC';  
		$stmt = $dbc->query($q);
		$r = $stmt->fetchAll();
		return $r;        
    }
    
    public static function add_new_product($dbc, $cat_id, $name, $desc, $img, $price, $stock, $size, $prod_code) {
       
		$q = 'INSERT INTO products (category_id, name, description, image, price, stock, size, product_code) 
				VALUES (:cat_id, :name, :descrip, :image, :price, :stock, :size, :product_code)';
		
		$smtp = $dbc->prepare($q);
		$smtp->bindParam(':cat_id', $cat_id);
		$smtp->bindParam(':name', $name);
		$smtp->bindParam(':descrip', $desc);
		$smtp->bindParam(':image', $img);
		$smtp->bindParam(':price', $price);
		$smtp->bindParam(':stock', $stock);
		$smtp->bindParam(':size', $size);            
		$smtp->bindParam(':product_code', $prod_code);  
		$r = $smtp->execute();
		
		if($r) {
			return $r; //OUT:: bool(true) 
		} else {
			return false;
		}
         
    }
    
    
    public static function getProductCodes($dbc) {        
	
		$q = 'SELECT * FROM product_code';
		
		$smtp = $dbc->query($q);            
		$r = $smtp->fetchAll();
		return $r;
        
    }
    
}//End class