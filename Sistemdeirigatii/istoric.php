<?php
	
	require_once('db.php');
	$query = "select * from activitate_pompa";
	$result = mysqli_query($conn,$query);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Istoric</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class = "continut">
<ul>
  <li><a href="index.html">Utilizare Pompa</a></li>
  <li><a class="active" href="istoric.php">Istoric </a></li>
</ul>

<div class = "chenar2">
	<div class="continer">
	<div class="container2">
	
	<table style="table-layout: fixed; width: 100%;">
		<thead>
			<tr>
				<th>Id Utilizare</th>
				<th>Id Pompa</th>
				<th>Id Teren</th>
				<th>Volum Utilizat</th>
				 <th style="width: 180px;">Data Utilizare</th>
				
			</tr>
		</thead>
		<tbody>
			
			<?php 
			
				while($row = mysqli_fetch_assoc($result))
				{
			?>
			<tr>
				<td><?php echo $row['id_activitate']; ?></td>
				<td><?php echo $row['id_pompa']; ?></td>
				<td><?php echo $row['id_teren']; ?></td>
				<td><?php echo $row['debit_apa']; ?></td>
				<td><?php echo $row['data_utilizare']; ?></td>
			</tr>
			<?php
			}
			?>
			
		</tbody>
	</table>
</div>
</div>
</div>

</div>
</body>
</html>