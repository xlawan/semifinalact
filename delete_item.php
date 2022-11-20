<?php
    if(isset($_GET["row"]))
    {
        $rowdata=$_GET["row"];
        //establish a connection to the database
        $connection = mysqli_connect("localhost", "root", "", "inventory");					
        
        mysqli_query($connection, "delete from item where iid = '$rowdata'");
        echo "<p>Deleted successfully...</p>";
        mysqli_close($connection);	
    }
    else 
    {
        //echo "<p>Error connecting to DB.</p>";
    }
?>