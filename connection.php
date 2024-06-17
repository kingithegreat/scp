<?php
    
 include"credentials.php";
 
 //create connection to our database
 //mysqli requires address,username,password and database name
 $connection = new mysqli('localhost',$user,$pw,$db);
 
 //create var to store record from our database
 $Records =$connection->prepare("select * from scp order by Item asc");
 //run sql query
 $Records->execute();
 //store result of query
 $Result = $Records ->get_result();
 
?>
