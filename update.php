<?php
	$itemid = 0;
	if(isset($_GET["rec0"]))
	{
		$itemid = $_GET["rec0"];
		$description = $_GET["rec1"];
		$umsr = $_GET["rec2"];
		$category = $_GET["rec3"];
		$qty = $_GET["rec4"];
		$price = $_GET["rec5"];
		
	}


	if(isset($_POST["update"]))
	{
		$itemid2 = $_POST["iid2"];
		$description2 = trim($_POST["description2"]);
		$umsr2 = $_POST["umsr2"];
		$category2 = $_POST["category2"];
		$qty2 = trim($_POST["qtyonhand2"]);
		$price2 = trim($_POST["price2"]);
		
		//error trapping start
		if($description2 == "" || $umsr2 == "" || $category2 == "" || $qty2 == "" || $price2 == "")
		{
			echo "<p>Sorry, description, unit of measurement, category, quantity, and price must not be empty. Please reload the page.</p>";	
			//exit;			
		}
		else if(!is_numeric($qty2) || !is_numeric($price2))
		{
			echo "<p>Sorry, Quantity on Hand or Price must be a number. Please reload the page.</p>";
			//exit;
			//retainInputs();
		}
		else if($qty2 < 0 || $price2 < 0)
		{
			echo "<p>Sorry, Quantity on Hand or Price must not be less than to '0'. Please reload the page.</p>";
			//exit;
		}
		else if(($umsr2 == "pc" && fmod($qty2,1) !==0.00) || ($umsr2 == "set" && fmod($qty2,1) !==0.00) || ($umsr2 == "doz" && fmod($qty2,1) !==0.00))
		{
			echo "<p>Sorry, Quantity on Hand for pc, set, and dozen must not have decimal values. Please reload the page.</p>";
			//exit;
		}
		//error trapping end
		else 
		{
			$con = mysqli_connect("localhost", "root", "", "inventory");
			if($con)
			{
				$sql = "UPDATE item
						SET description='".$description2."',
							umsr='".$umsr2."',
							cid='".$category2."',
							qtyonhand='".$qty2."',
							price='".$price2."'
						WHERE iid=".$itemid2."";
				mysqli_query($con, $sql);
				echo "<p>Item was updated successfully...</p>";
			}
			else 
			{
				echo "<p>Error connecting to DB...</p>";
			}
			mysqli_close($con);
		}
		function retainInputs() {
			// $itemid = $itemid2;
			// $description = $_GET["rec1"];
			// $umsr = $_GET["rec2"];
			// $category = $_GET["rec3"];
			// $qty = $_GET["rec4"];
			// $price = $_GET["rec5"];
		}
	}

?>
	<body>
		<form method="POST" action="list_all.php">
			<table>
				<tr>
					<td>Item ID:</td>
					<td><input type="text" name="iid2" value="<?php echo $itemid?>" readonly /></td>
				</tr>
				<tr>
					<td>Description:</td>
					<td><input type="text" name="description2" value="<?php echo $description?>" required /></td>
				</tr>
				<tr>
					<td>Unit of Measure:</td>
					<td>
						<select name="umsr2">
							<option value="pc"  <?php if($umsr == "pc") 	echo "selected"; ?>>pc</option>
							<option value="set" <?php if($umsr == "set") 	echo "selected"; ?>>set</option>
							<option value="kl"  <?php if($umsr == "kl") 	echo "selected"; ?>>kilo</option>
							<option value="doz" <?php if($umsr == "doz") 	echo "selected"; ?>>dozen</option>
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
									echo "<select name='category2'>";
									while($row = mysqli_fetch_row($categories))
									{
										echo "<option value='".$row[0]."'";
										if($category == $row[1]) echo 'selected';
										echo ">".$row[1]."</option>";
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
					<td><input type="text" name="qtyonhand2" value="<?php echo $qty?>" required /></td>
				</tr>	
				<tr>
					<td>Price:</td>
					<td><input type="text" name="price2" value="<?php echo $price?>" required /></td>
				</tr>	
				<tr>
					<td></td>
					<td><button type="submit" name="update">UPDATE</button></td>
				</tr>				
			</table>
			
		</form>
	</body>