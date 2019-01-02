<?php
class DeliverySlots {

    function __construct() {
        
    }
    
    
	
	public static function isDelivDateAvail($dbc, $dayId, $slotId) {
		
		$q = " SELECT COUNT(* ) AS bookings
				FROM days_slots
				WHERE day_id = :dayID AND slot_id = :slotID 
			";
			
		$stmt = $dbc->prepare($q);                 
		$stmt->bindParam(':dayID', $dayId);
		$stmt->bindParam(':slotID', $slotId);
		$stmt->execute();		
		// $stmt = $dbc->query($q);
		
		$r = $stmt->fetchAll();
		return $r;
	}
	
	public static function displayDates($dbc) {
		
		//Select days which are bigger than today. The earliest a user can choose delivery slot is next day
		// We want users to choose today's delivery until 5pm. After 5 All option should be unavailable
		// Easiest way is not to display the POST today in dropdown if time < 5pm

		//$q = "SELECT id, day FROM days WHERE day > DATE_SUB(NOW(), INTERVAL 1 DAY)  "; //Display days from yesterday
		$q = "SELECT id, day 
		FROM days
		WHERE DAY > DATE_SUB( NOW( ) , INTERVAL 17 HOUR )  "; //We don't want to displya today s Date if time is 15:00. Subtract 15 HOURS from today
		$stmt = $dbc->query($q);
		$r = $stmt->fetchAll();

		return $r;
	}
    
    

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

