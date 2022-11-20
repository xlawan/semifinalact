<?php
	$description = "";
	$umsr = "";
	$category = "";
	$qty = "";
	$price = "";
	
	
	if(isset($_POST["save"]))
	{
		//check if the elements or inputs are still there
		if(!isset($_POST["description"]) || !isset($_POST["umsr"]) || !isset($_POST["category"]) || !isset($_POST["qtyonhand"]) || !isset($_POST["price"]))
		{
			echo "<p>Something is wrong with the form. A Hacker defaced it. Page was being reloaded.</p>";					
		} 
		else
		{	
			$description = trim($_POST["description"]);
			$umsr = $_POST["umsr"];
			$category = $_POST["category"];
			$qty = trim($_POST["qtyonhand"]);
			$price = trim($_POST["price"]);
			
			//error trapping start
			if($description == "" || $umsr == "" || $category == "" || $qty == "" || $price == "")
			{
				echo "<p>Sorry, description, unit of measurement, category, quantity, and price must not be empty. Please reload the page.</p>";	
				exit;			
			}
			if(!is_numeric($qty))
			{
				echo "<p>Sorry, Quantity on Hand must be a number. Please reload the page.</p>";
				exit;
			}
			if($qty < 0)
			{
				echo "<p>Sorry, Quantity on Hand must not be less than to '0'. Please reload the page.</p>";
				exit;
			}
			if(($umsr == "pc" && fmod($qty,1) !==0.00) || ($umsr == "set" && fmod($qty,1) !==0.00) || ($umsr == "doz" && fmod($qty,1) !==0.00))
			{
				echo "<p>Sorry, Quantity on Hand for pc, set, and dozen must not have decimal values. Please reload the page.</p>";
				exit;
			}
			//error trapping end
			$con = mysqli_connect("localhost", "root", "", "inventory");
			if($con)
			{
				$sql = "insert into item (description, umsr, cid, qtyonhand, price) 
						values ('".$description."', '".$umsr."', ".$category.", ".$qty.", ".$price.") ";
				mysqli_query($con, $sql);
				echo "<p>Item was saved successfully...</p>";
				
			}
			else 
			{
				echo "<p>Error connecting to DB...</p>";
			}
			mysqli_close($con);
		}
	}

?>
<html>
	<head>
		<title>Add Item</title>
	</head>
	<body>
		<form method="POST" action="list_all.php">
			<table>
				<tr>
					<td>Description:</td>
					<td><input type="text" name="description" required /></td>
				</tr>
				<tr>
					<td>Unit of Measure:</td>
					<td>
						<select name="umsr">
							<option value="pc">pc</option>
							<option value="set">set</option>
							<option value="kl">kilo</option>
							<option value="doz">dozen</option>
						</select>
					</td>
				</tr>	
				<tr>
					<td>Category:</td>
					<td>
						<?php
							$con = mysqli_connect("localhost", "root", "", "inventory");
							if($con)
							{
								$categories = mysqli_query($con, "select * from category order by description");
								if(mysqli_num_rows($categories) > 0)
								{
									echo "<select name='category'>";
									while($row = mysqli_fetch_row($categories))
									{
										echo "<option value='".$row[0]."'>".$row[1]."</option>";
									}
									echo "</select>";
								}
							}
							else 
							{
								echo "<p>Error DB Connection</p>";
							}
							
						?>
					</td>
				</tr>
				<tr>
					<td>Quantity on Hand:</td>
					<td><input type="text" name="qtyonhand" required /></td>
				</tr>	
				<tr>
					<td>Price:</td>
					<td><input type="text" name="price" required /></td>
				</tr>	
				<tr>
					<td></td>
					<td><button type="submit" name="save">Save</button></td>
				</tr>				
			</table>
			
		</form>
	</body>
</html>