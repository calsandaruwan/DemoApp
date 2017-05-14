<?php
	$conn = mysqli_connect('localhost','root','','users') or die('Cannot connect to the data base');
	//get all departments from department table
	$get_dep = "SELECT `id`,`department` FROM `departments`";
	$get_users = "SELECT `user_data`.`id`,`f_name`,`l_name`,`email`,`department` FROM `user_data` JOIN `departments` WHERE `user_data`.`dep`=`departments`.`id` ";
	
	$departments = $conn->query($get_dep);
	$dep_array=$result='';
	while($row_dep = mysqli_fetch_assoc($departments)){
		$dep_array[] = $row_dep;
	}
	
	$url_segments = explode('/',$_SERVER['REQUEST_URI']);
	//echo'<pre>';print_r(($_SERVER));echo '</pre>';
	print_r($url_segments);
	if(isset($url_segments[4])&&($url_segments[3])=='edit'){
		echo 'done';
	}
	if(isset($_POST['submit'])){
		$post = $_POST['post'];
		$sql_insert = "INSERT INTO `user_data` (`id`,`f_name`,`l_name`,`dep`,`email`,`password`,`deleted`) VALUES ('NULL','".$post['f_name']."','".$post['l_name']."','".$post['dep']."','".$post['email']."','".sha1($post['password'])."','0')";
		if($conn->query($sql_insert)){
			echo 'You have successfully inserted new record';	
		}else{
			echo 'Error';
		}
	}

	$users = $conn->query($get_users);
	while($row_user = mysqli_fetch_assoc($users)){
		$result[] = $row_user;
	}
	
?>
<html>
	<head>
	</head>
	<body>
		<div>
			<form method='post' name='form_userdata'>
				<table>
					<!--<thead>
						<tr>
							<th></th>
						</tr>
					</thead>
					-->
					<tbody>
						<tr>
							<td>
								First Name
							</td>
							<td>
								<input type='text' name='post[f_name]' >
							</td>
						</tr>
						<!-- end of row-->
						<tr>
							<td>
								Last Name
							</td>
							<td>
								<input type='text' name='post[l_name]' >
							</td>
						</tr>
						<!-- end of row-->
						<tr>
							<td>
								Email Address
							</td>
							<td>
								<input type='email' name='post[email]' >
							</td>
						</tr>
						<!-- end of row-->
						<tr>
							<td>
								Password
							</td>
							<td>
								<input type='password' name='post[password]' >
							</td>
						</tr>
						<!-- end of row-->
						<tr>
							<td>
								Department
							</td>
							<td>
								<select name='post[dep]'>
									<?php foreach($dep_array as $row ) {?>
									<option value='<?=$row["id"]?>'><?= $row['department']?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<!-- end of row-->
						<tr>
							<td>

							</td>
							<td>
								<input type='submit' name='submit'>
							</td>
						</tr>
						<!-- end of row-->

					</tbody>
				</table>
			</form>
		</div>
		<br></hr>
		<div>
			<table border='1' cellspacing='0' cellpadding='3'>
					<thead>
						<tr>
							<th>ID</th>
							<th>First Name</th>
							<th>Last name</th>
							<th>Email</th>
							<th>Department</th>
							<th>edit/Delete</th>
						</tr>
					</thead>
					<tbody>
						<?php if($result!=''){foreach($result as $row){?>
						<tr>
							<td><?=$row['id']?></td>
							<td><?=$row['f_name']?></td>
							<td><?=$row['l_name']?></td>
							<td><?=$row['email']?></td>
							<td><?=$row['department']?></td>
							<td><a href=''>edit</a>&nbsp;&nbsp;<a href=''>dlt</a></td>
						</tr>
						<?php }}else{echo "No Record Found";} ?>
						<!-- end of row-->

					</tbody>
				</table>
		</div>
	</body>
</html>