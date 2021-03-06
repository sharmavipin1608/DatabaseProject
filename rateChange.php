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
            function updateRate(rateId){
                    var ajaxRequest = new XMLHttpRequest();
    
                    ajaxRequest.onreadystatechange = function () {
                        if (ajaxRequest.readyState == 4) {
                            location.reload();
                    }
                }
                var url = "queriesRepository.php?purpose=updateRate&rateId="+rateId;
                //alert(url);
                ajaxRequest.open("GET", url, true);
                ajaxRequest.send(null);
            }
            
            function addRate(){
                var pricePerMin = document.getElementById('pricePerMin').innerHTML;
                var pricePerMile = document.getElementById('pricePerMile').innerHTML;
                var basePrice = document.getElementById('basePrice').innerHTML;
                alert("pricePerMin : "+pricePerMin);
                var ajaxRequest = new XMLHttpRequest();
    
                    ajaxRequest.onreadystatechange = function () {
                        if (ajaxRequest.readyState == 4) {
                            alert(ajaxRequest.responseText);
                            location.reload();
                        //alert(ajaxRequest.responseText);
                        //var ajaxDisplay = document.getElementById('release');
                        //ajaxDisplay.innerHTML = ajaxRequest.responseText;
                    }
                }
                var url = "queriesRepository.php?purpose=addRate&pricePerMin="
                        +pricePerMin+"&pricePerMile="+pricePerMile+"&basePrice="
                        +basePrice;
                //alert(url);
                ajaxRequest.open("GET", url, true);
                ajaxRequest.send(null);
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
                                        <td id="pricePerMin" contenteditable="true">Price per minute</td>
                                        <td id="pricePerMile" contenteditable="true">Price per mile</td>
                                        <td id="basePrice" contenteditable="true">Base Price</td>
                                        <td><button onclick="addRate()">  Add </button></td>
                                    </tr>
                                </table>
                        <?php
                            }
                        ?>
                        
                    </div>
                    
                    <span style="color:red"><i>Click on Activate button for the rate
                        you want to set. For anything different add a new rate in 
                        the bottom row.</i></span>
                </div>    
            </div>
        </div>
        

    </body>
</html>