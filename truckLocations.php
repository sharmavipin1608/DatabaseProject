<?php
    require_once 'dbConnection.php';
    
    //$locationsQuery = "select * from location;";
    $locationsQuery = "SELECT A.driverId, B.latitude, B.longitude from driver A "
            . "left outer join location B on A.driverId = B.driverId order by "
            . "B.longitude DESC;";
    $locationsQueryResult = mysql_query($locationsQuery);
    $locations = array();
    while ($row = mysql_fetch_array($locationsQueryResult)) {
        $locations[] = $row;
    }
?>


<html>
    <head>
        <script type="text/javascript">
            function updateLocations(driverId){
                var latitude = document.getElementById(driverId+"latitude").innerHTML;
                var longitude = document.getElementById(driverId+"longitude").innerHTML
                
                if((latitude < 32 || latitude > 34) || (longitude < -119 || longitude > -117)){
                    alert("Please read the note at the bottom of the table carefully.");
                }
                else{
                    var ajaxRequest = new XMLHttpRequest();
    
                    ajaxRequest.onreadystatechange = function () {
                        if (ajaxRequest.readyState == 4) {
                            //alert(ajaxRequest.responseText);
                            //var ajaxDisplay = document.getElementById('release');
                            //ajaxDisplay.innerHTML = ajaxRequest.responseText;
                        }
                    }
                    var url = "queriesRepository.php?purpose=updateLocation&\n\
                                driverId="+driverId+"&latitude="+latitude+"\n\
                                &longitude="+longitude;
                    //alert(url);            
                    ajaxRequest.open("GET", url, true);
                    ajaxRequest.send(null);
                }
            }
        </script>
    </head>
    <body>
        <div class="panel-group" id='panel-group-1'>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="panel-title" data-toggle="collapse" data-parent="#panel-group-1" href="#panel-element">Update Truck Locations</a>
                </div>
            
                <div id="panel-element" class="panel-collapse collapse in">
                    <div class="panel-body" id="locations">
                        <?php
                            if(sizeof($locations) > 0){
                        ?>
                                <table class="table table-striped">
                                    <tr>
                                        <th>Driver ID</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th></th>
                                    </tr>
                                    <?php
                                    foreach ($locations as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['driverId']; ?></td>
                                        <td contenteditable="true" id='<?php echo $row['driverId']."latitude"; ?>'><?php echo $row['latitude']; ?></td>
                                        <td contenteditable="true" id='<?php echo $row['driverId']."longitude"; ?>'><?php echo $row['longitude']; ?></td>
                                        <td><button onclick="updateLocations(this.value)" value="<?php echo $row['driverId']; ?>">Update</button></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    
                                </table>
                        <?php
                            }
                        ?>
                        
                    </div>
                </div>  
                <span style="color:red"><i>Latitude should be between 32 and 34
                    Longitude should be between -117 and -119</i></span>
            </div>
        </div>
        

    </body>
</html>
