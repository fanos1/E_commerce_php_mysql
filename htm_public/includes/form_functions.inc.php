<?php
//code taken from effortless ecommerce book

// This function generates a form INPUT or SELECT tag.
// It takes up to five arguments:
// - The name to be given to the element.
// - The type of element (text, password, select).
// - An array of errors.
// - Where existing values are to be found (POST, SESSION).
// - HTML extras (autocomplete="off", readonly="readonly")
function create_form_input($name, $type, $errors = array(), $values = 'POST', $options = array()) {
	
	// Assume no value already exists:
	$value = false;

	// Get the existing value, if it exists:
	if ($values === 'SESSION') 
	{	
            //exit('form functi icinde');
            
                if ( isset($_SESSION[$name])) {
                    $value = htmlspecialchars($_SESSION[$name], ENT_QUOTES, 'UTF-8');
                }
                    		
	} 
	elseif ($values === 'POST') 
	{	
            //echo "<h1>POST RE</h1>";
            //exit('cik');
            
		if (isset($_POST[$name])) {
                    $value = htmlspecialchars($_POST[$name], ENT_QUOTES, 'UTF-8');
                }
                    
		// Strip slashes if Magic Quotes is enabled:
		if ($value && get_magic_quotes_gpc()) $value = stripslashes($value);
	}

	// Conditional to determine what kind of element to create:
	if ( ($type === 'text') || ($type === 'password') ) { // Create text or password inputs.
		
		// Start creating the input:
		echo '<input class="form-control" type="' . $type . '" name="' . $name . '" id="' . $name . '"';

		// Add the value to the input:
		if ($value) echo ' value="' . $value . '"';

		// Check for any extras:
		if (!empty($options) && is_array($options)) {
			foreach ($options as $k => $v) {
				echo " $k=\"$v\"";
			}
		}

		// Check for an error:
		if (array_key_exists($name, $errors)) {
			echo 'class="has-error" /> 
                            <div class="alert alert-danger">' . $errors[$name] . '</div>';
		} else {
			echo ' />';		
		}
		
	} elseif ($type === 'select') { // Select menu.
		
		if (($name === 'state') || ($name === 'cc_state')) { // Create a list of states.
			
			$data = array('AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming');
			
		} elseif ($name === 'cc_exp_month') { // Create a list of months.

			$data = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',  'September', 'October', 'November', 'December');
			
		} elseif ($name === 'cc_exp_year') { // Create a list of years.
			
			$data = array();
			$start = date('Y'); // Start with current year.
			for ($i = $start; $i <= $start + 5; $i++) { // Add five more.
				$data[$i] = $i;
			}
			
		} // End of $name IF-ELSEIF.
		
		// Start the tag:
		echo '<select class="form-control" name="' . $name  . '"';
	
		// Add the error class, if applicable:
		if (array_key_exists($name, $errors)) echo ' class="error"';

		// Close the tag:
		echo '>';		
	
		// Create each option:
		foreach ($data as $k => $v) {
			echo "<option value=\"$k\"";
			
			// Select the existing value:
			if ($value === $k) echo ' selected="selected"';
			
			echo ">$v</option>\n";
			
		} // End of FOREACH.
	
		// Complete the tag:
		echo '</select>';
		
		// Add an error, if one exists:
		if (array_key_exists($name, $errors)) {
			echo '<br /><span class="error">' . $errors[$name] . '</span>';
		}
		
	} elseif ($type === 'textarea') { // Create a TEXTAREA.

		// Display the error first: 
		if (array_key_exists($name, $errors)) echo ' <span class="has-error">' . $errors[$name] . '</span><br />';

		// Start creating the textarea:
		echo '<textarea class="form-control" name="' . $name . '" id="' . $name . '" rows="5" cols="75"';

		// Add the error class, if applicable:
		if (array_key_exists($name, $errors)) {
			echo ' class="error">';
		} else {
			echo '>';		
		}

		// Add the value to the textarea:
		if ($value) echo $value;

		// Complete the textarea:
		echo '</textarea>';

	} // End of primary IF-ELSEIF.

} // End of the create_form_input() function.

// Omit the closing PHP tag to avoid 'headers already sent' errors!