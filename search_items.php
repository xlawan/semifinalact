<?php
    if(isset($_GET["search"]))
    {
        $keyword = trim($_GET["keyword"]);
        
        //establish a connection to the database
        $connection = mysqli_connect("localhost", "root", "", "inventory");
    
        //check if the connection was successful
        if($connection)	
        {
            $sql = " select 
                            item.iid,
                            item.description,
                            item.umsr,
                            category.description,
                            item.qtyonhand,
                            item.price
						 from
							item left join category
								on item.cid = category.cid
                         where
                            item.iid like '%".$keyword."%' or
                            item.description like '%".$keyword."%' or
                            item.umsr like '%".$keyword."%' or
                            category.description like '%".$keyword."%' or
                            item.qtyonhand like '%".$keyword."%' or
                            item.price like '%".$keyword."%'
						 order by
                            item.iid,
							item.description
				";
            //get all the records from the student table
            $records = mysqli_query($connection, $sql);					
            
            //check if there are records retrieved
            if(mysqli_num_rows($records) > 0)
            {
                //form the table
                echo "<form method='POST' action='list_all.php'>";
                echo "<table border='1'>";
                echo "	<thead>";
                echo "		<tr>";
                echo "			<th>Seq. No.</th>";
                echo "		    <th>Item ID</th>";
                echo "		    <th>Description</th>";
                echo "		    <th>Unit of Measure</th>";
                echo "		    <th>Category</th>";
                echo "		    <th>Qty. on Hand</th>";
                echo "		    <th>Price</th>";
                echo "		    <th>Update</th>";
                echo "		    <th>Delete</th>";
                echo "		</tr>";
                echo "	</thead>";
                echo "	<tbody>";
                
                // loop each record $records variable and display it row by row
                $seq = 1;
                while($rec = mysqli_fetch_row($records))
                {
                    echo "<tr>";
                    echo "		<td>".$seq.".</td>";
                    echo "		<td>".$rec[0]."</td>";
                    echo "		<td>".$rec[1]."</td>";
                    echo "		<td>".$rec[2]."</td>";
                    if ($rec[3] == null) 
                    {
                        echo "		<td>FLD</td>"; //category
                    }
                    else
                    {
                        echo "		<td>".$rec[3]."</td>"; //category
                    }
                    echo "		<td align='right'>".$rec[4]."</td>";
                    echo "		<td align='right'>".$rec[5]."</td>";
                    echo "		<td><a href='list_all.php?rec0=$rec[0]&rec1=$rec[1]&rec2=$rec[2]&rec3=$rec[3]&rec4=$rec[4]&rec5=$rec[5]'>Update</a></td>";
                    echo "		<td><a href='list_all.php?row=$rec[0]'>Delete</a></td>";
                    echo "</tr>";
                    $seq++;
                }
                echo "	</tbody>";
                echo "</table>";
                echo "</form>";
                
            }
            else 
            {
                //no records retrieved
                echo "<p>No records retrieved.</p>";
            }
            mysqli_close($connection);					
        }
        else 
        {
            echo "<p>Error connecting to DB.</p>";
        }
    }
?>