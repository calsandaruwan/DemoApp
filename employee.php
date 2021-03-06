<?php 
class employee{
	public $_conn;
	public $_msg;
	public $_bkt;
	public $_action='Insert';
	public function __construct(){
		$this->_conn = mysqli_connect('localhost','root','','emp_db')or die('Damn, Connection error');
	}

	public function manage(){
		$uri_segs = explode('/',$_SERVER['REQUEST_URI']);
		if(isset($uri_segs[3])&&isset($uri_segs[4])&&$uri_segs[3]=='update'){
			$this->_action='Update';
			$get_qry = "SELECT `tbl_users`.`id`,`f_name`,`l_name`,`address`,`email`,`department` FROM `tbl_users` JOIN `tbl_department` ON `tbl_users`.`department` = `tbl_department`.`id` WHERE `tbl_users`.`id`=".$uri_segs[4];
			$result = $this->get_result($get_qry);
				if($result!=''){
					$this->_bkt = $result[0];
				}else{
					$this->_msg="The user is deleted or not exixt";
				}
			if(isset($_POST['submit']) && $_POST['submit']=='Update'){
				$post=$_POST['post'];
				$upd_qry = "Update `tbl_users` SET `f_name`='".$post['f_name']."',`l_name`='".$post['l_name']."',`address`='".$post['address']."',`email`='".$post['email']."',`password`='".sha1($post['password'])."',`department`='".$post['department']."' WHERE `id`='".$uri_segs[4]."'";
				if($this->exec_qry($upd_qry)){
					$this->_msg="User '".$post['f_name']."' Updated";
				}else{
					$this->_msg="Oops something wrong";
				}
			}	

		}else if(isset($uri_segs[3])&&isset($uri_segs[4])&&$uri_segs[3]=='delete'){
			$this->_action='Delete';
		}else{
			$this->_action='Insert';
			if(isset($_POST['submit']) && $_POST['submit']=='Insert'){
				$post=$_POST['post'];
				$ins_qry = "INSERT INTO `tbl_users` (`id`,`f_name`,`l_name`,`address`,`email`,`password`,`department`) VALUES ('NULL','".$post['f_name']."','".$post['l_name']."','".$post['address']."','".$post['email']."','".sha1($post['password'])."','".$post['department']."')";
				if($this->exec_qry($ins_qry)){
					$this->_msg="User '".$post['f_name']."' added";
				}else{
					$this->_msg="Oops something wrong";
				}
			}
		}
	}

	public function get_result($qry){
		$result = $this->_conn->query($qry);
		$result_array=array();
		if(($result->num_rows)>0){
			while($row = mysqli_fetch_assoc($result)){
				$result_array[] = $row;
			}
		}
		return $result_array;
	}

	public function exec_qry($qry){
		if($this->_conn->query($qry)){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function get_bkt_item($key){
		if(isset($this->_bkt[$key])){
			return $this->_bkt[$key];
		}else{
			return '';
		}
	}
}
$emp_obj = new employee();
$emp_obj->manage();
$res_dep = $emp_obj->get_result("SELECT * FROM `tbl_department`");
$res_emp = $emp_obj->get_result("SELECT `tbl_users`.`id`,`f_name`,`l_name`,`address`,`email`,`d_name` FROM `tbl_users` JOIN `tbl_department` ON `tbl_users`.`department` = `tbl_department`.`id`");
$action = $emp_obj->_action;
?>
<html>
	<head>
		<title>manage Employee</title>
	</head>
	<body>
		</div>
		<?= $emp_obj->_msg;?>
			<form method='post'>
				<table>
					<tbody>
						<tr>
							<td>First Name</td>
							<td><input type="text" value="<?=$emp_obj->get_bkt_item('f_name')?>" name="post[f_name]"></td>
						</tr>
						<tr>
							<td>Last Name</td>
							<td><input type="text" value="<?=$emp_obj->get_bkt_item('l_name')?>" name="post[l_name]"></td>
						</tr>
						<tr>
							<td>Address</td>
							<td><input type="text" value="<?=$emp_obj->get_bkt_item('address')?>" name="post[address]"></td>
						</tr>
						<tr>
							<td>Email</td>
							<td><input type="email" value="<?=$emp_obj->get_bkt_item('email')?>" name="post[email]"></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input type="password" name="post[password]"></td>
						</tr>
						<tr>
							<td>Department</td>
							<td>
								<select name='post[department]' >
									<?php  if($res_dep!=''){foreach($res_dep as $row){ ?>
									<option <?= $emp_obj->get_bkt_item('department')==$row['id']?"selected='selected'":''?> value="<?= $row['id'] ?>"><?= $row['d_name'] ?></option>
									<?php }} ?>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" name="<?=$action?>" value="<?= $action ?>"></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<div>
			<table border="1" cellspacing="0">
				<thead>
					<tr>
						<th>#</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Address</th>
						<th>Email</th>
						<th>Department</th>
						<th>Edt / Dlt</th>
					</tr>
				</thead>
				<tbody>
					<?php  if($res_emp!=''){foreach($res_emp as $row){ ?>
					<tr>
						<td><?=$row['id']?></td>
						<td><?=$row['f_name']?></td>
						<td><?=$row['l_name']?></td>
						<td><?=$row['address']?></td>
						<td><?=$row['email']?></td>
						<td><?=$row['d_name']?></td>
						<td>
							<a href="http://localhost<?= $_SERVER['SCRIPT_NAME'].'/update/'.$row['id'] ?>">edt</a>&nbsp;
							<a href="http://localhost<?= $_SERVER['SCRIPT_NAME'].'/delete/'.$row['id'] ?>">dlt</a>
						</td>
					</tr>
					<?php }} ?>
				</tbody>
			</table>
		</div>
	</body>
</html>