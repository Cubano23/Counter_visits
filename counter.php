<?php
//tail -n 20 /var/log/apache2/error.log
    $servername = "localhost";
    $username = "user";
    $password = "xxxxxxxxxxxx";
    $dbname = "database_name";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $visits = "";
    $ip_client = "";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    //*************get ip**********************/
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_client = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_client = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_client = $_SERVER['REMOTE_ADDR'];
    }
    //*********Verify if ip exists************/ 
    function compairIp($conn,$ip_client){            
        $result = $conn->query("SELECT ip_address FROM ip where ip_address = '$ip_client'");        
                if($result->num_rows == 0){
                $sql= "INSERT INTO ip (ip_address)
                VALUES ('$ip_client')";
                $conn->query($sql);
                return 'new';
            }           
    }
    //***********If ip exists update visits*************/     
     if(compairIp($conn,$ip_client) == 'new'){
        $sql = "UPDATE counter SET visits = visits+1 WHERE id = 1";
        $conn->query($sql);
    }  
  $sql = "SELECT visits FROM counter WHERE id = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $visits = $row["visits"];
        }
    } else {
        echo "no results";
    }    
    $conn->close();
?>



