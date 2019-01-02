
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            
            
            <table id="customers" class="table">
                <thead>
                  <tr>
                    <th>id</th>
                    <th>email</th>
                    <th>first name</th>
                    <th>last name</th>
                    <th>Address 1</th>
                    <th>Address 2</th>
                    <th>City</th>
                    <th>Post Code</th>
                    <th>Phone</th>
                    <th>Date Created</th>
                    <th>Delivery Slot</th>                
                  </tr>
                </thead>
                <tbody>

            
            <?php
            $tableRows = '';
            
            if (is_array($rows) || is_object($rows)) {            

                foreach ($rows as $k => $array) {
                    $tableRows .= '
                    <tr>
                        <td>' . htmlentities($array['id']) .'</td>
                        <td>' . htmlentities($array['email']) .'</td>  
                        <td>' . htmlentities($array['first_name']) .'</td>    
                        <td>' . htmlentities($array['last_name']) .'</td>
                        <td>' . htmlentities($array['address1']) . '</td>        
                        <td>' . htmlentities($array['address2']) . '</td>    
                        <td>' . htmlentities($array['city']). '</td>    
                        <td>' . htmlentities($array['post_code']). '</td>    
                        <td>' . htmlentities($array['phone']). '</td>    
                        <td>' . htmlentities($array['date_created']). '</td>    
                        <td>' . htmlentities($array['delivery_slot']). '</td>    
                    </tr>';

                } //End foreach()              
            }
            echo $tableRows;
            ?>
                  
                  
                </tbody>
              </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script type="text/javascript"> 
    // Enable Datatables:
    $(function() { 
        $("#customers").dataTable();
    }); 
</script>