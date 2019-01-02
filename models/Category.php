<?php

class Category {
    
    public function __construct() {
        
    }
    
    public static function getCategory($dbc) {
        
            $q = 'SELECT id, category FROM categories ORDER BY category ASC';
            $smtp = $dbc->query($q);
            $r = $smtp->fetchAll();
            return $r;
            
                
    }
    
}//End Class