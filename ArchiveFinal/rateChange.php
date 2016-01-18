<?php
    require_once 'dbConnection.php';
    
    $query = "select * from rate order by status;";
    $result = mysql_query($query);
    $rate = array();
    
    while($row = mysql_fetch_array($result))
        $rate[] = $row;
?>

<html>
    <head>
        <script type="text/javascript">
            function updateLocations(driverId){
                //alert("hello");
                var latitude = document.getElementById(driverId+"latitude").innerHTML;
                var longitude = document.getElementById(driverId+"longitude").innerHTML
                
                alert(latitude + " " + longitude);
                
                if((latitude < 32 || latitude > 34) || (longitude < -119 || longitude > -117)){
                    alert("incorrect");
                }
                else{
                    var ajaxRequest = new XMLHttpRequest();
    
                    ajaxRequest.onreadystatechange = function () {
                        if (ajaxRequest.readyState == 4) {
                            alert(ajaxRequest.responseText);
                            //var ajaxDisplay = document.getElementById('release');
                            //ajaxDisplay.innerHTML = ajaxRequest.responseText;
                        }
                    }
                    var url = "queriesRepository.php?purpose=updateLocation&\n\
                                driverId="+driverId+"&latitude="+latitude+"\n\
                                &longitude="+longitude;
                    alert(url);            
                    ajaxRequest.open("GET", url, true);
                    ajaxRequest.send(null);
                }
            }
        </script>
    </head>
    <body>
        <div class="panel-group" id='panel-group-2'>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="panel-title" data-toggle="collapse" data-parent="#panel-group-2" href="#panel-element-1">Rate</a>
                </div>
            
                <div id="panel-element-1" class="panel-collapse collapse in">
                    <div class="panel-body" id="rateList">
                        <?php
                            if(sizeof($locations) > 0){
                        ?>
                                <table class="table table-striped">
                                    <tr>
                                        <th>Price per Minute</th>
                                        <th>Price per Mile</th>
                                        <th>Base Price</th>
                                        <th></th>
                                    </tr>
                                    <?php
                                    foreach ($rate as $row) {
                                    ?>
                                    <tr>
                                        <td contenteditable="true"><?php echo $row['pricePerMinute']; ?></td>
                                        <td contenteditable="true"><?php echo $row['pricePerMile']; ?></td>
                                        <td contenteditable="true"><?php echo $row['basePrice']; ?></td>
                                        <td>
                                            <?php
                                            if($row['status'] != 'A'){
                                            ?>
                                                <button onclick="updateRate(this.value)" value="<?php echo $row['rateId']; ?>">Activate</button>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <td id="pricePerMin" contenteditable="true"><i>Price per minute</i></td>
                                        <td id="pricePerMile" contenteditable="true"><i>Price per mile</i></td>
                                        <td id="basePrice" contenteditable="true"><i>Base Price</i></td>
                                        <td><button>  Add </button></td>
                                    </tr>
                                </table>
                        <?php
                            }
                        ?>
                        
                    </div>
                    
                    lets check out
                </div>    
            </div>
        </div>
        

    </body>
</html>