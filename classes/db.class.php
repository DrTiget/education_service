<?php
class DB_class 
{
	private $db_host,$db_name,$db_user,$db_pass,$db;
	function __construct($db_host,$db_name,$db_user,$db_pass)
	{
		if (!$this->db) {
			$con = @ new mysqli($db_host, $db_user, $db_pass, $db_name);
			if (!$con->connect_error) {
				$this->db = true;
				$con->set_charset("utf8");
				$this->con = $con;
				return true;
			}else{
				echo $con->connect_error."<br>";
				return false;
			}
		}
	}

	/*
	форматирование данных перед записью в базу данных
	получает значение и тип в который надо форматировать
	*/
	private function format_value($value,$format) {
		switch ($format) {
	    	case "date":
	    		$value = date("Y-m-d",strtotime($value));
	        break;
	        case "time":
	    		$value = date("H:i:s",strtotime($value));
	        break;
	        case "int(11)":
	        	$value = intval($value);
	        break;
	        case "tinyint(4)":
	        	$value = intval($value);
	        break;
	        case "tinyint(1)":
	        	$value = boolval($value);
	        break;
	        case "bigint(20)":
	        	$value = intval($value);
	        break;
	    }
	    return $value;
	}


	/*
		входные данные
		$table - название таблицы
		выходные данные
		{
			"Field" => Название поля
			"Type"  => Тип поля
		}
	*/
	private function get_table_fields ($table) {
		$sql = "DESCRIBE ".$table;
		$u_query = $this->con->query($sql);
		if ($u_query->num_rows != 0) {
		    $count_row = 0;
			while ($query_row = $u_query->fetch_array(MYSQLI_ASSOC)) {
				$select_count = 0;
				$count_select = count($query_row);
				$select_array = NULL;
				while ($select_count < $count_select) {
					$return_array[$count_row]['Field'] = $query_row['Field'];
					$return_array[$count_row]['Type'] = $query_row['Type'];
					$select_count++; 
				}
				$count_row++;
			}
		}else{
			$return_array = 0;
		}
		return $return_array;
	}

	/*
		'table_name0' => {
			'fields' => {
				'field0',
				'field1',
				'field2',	
			}
			'where' => 'условие'
		},
		'table_name1' => {
			'fields' => {
				'field0',
				'field1',
				'field2',	
			}
			'where' => 'условие'
		}
		...
	*/
	function select_links($while,$select) {
		$connections = array(
			"auth_logs" => array(
				"users" => array("user_id", "auth_log_user_id"),
				"auth_logs_types" => array("auth_log_type_id","auth_log_type"),
			)
		);
		if (!is_array($select)) {
			return array("ERROR. select must be array");
		}
		//SELECT a.*, m.master_account_id FROM master m, accounts a WHERE m.master_account_id = a.account_id AND a.account_id=1
		$select_query = "";
		$where_query = array();
		$from_query = "";
		$select_array_keys = array_keys($select);
		for($i=0;$i<count($select);$i++) { // обход таблиц
			$from_query .= $select_array_keys[$i]." ".$select_array_keys[$i]."Table";
			if ($i+1 != count($select)) {
				$from_query.= ", ";
			}
			for($q=0;$q<count($select[$select_array_keys[$i]]['fields']);$q++) { // обход полей
				$select_query .= $select_array_keys[$i]."Table.".$select[$select_array_keys[$i]]['fields'][$q];
				if (($q+1 != count($select[$select_array_keys[$i]]['fields'])) OR ($i+1 != count($select))) {
					$select_query.=", ";
				}
			}
			if (isset($select[$select_array_keys[$i]]['where'])) {
				array_push($where_query, $select[$select_array_keys[$i]]['where']);
			}
			if (array_key_exists($select_array_keys[$i],$connections)) {
				for($q=0;$q<count($select);$q++) { // проходимся по всем связям
					if (array_key_exists($select_array_keys[$q],$connections[$select_array_keys[$i]])) {
						array_push($where_query, $select_array_keys[$q]."Table.".$connections[$select_array_keys[$i]][$select_array_keys[$q]][0]."=".$select_array_keys[$i]."Table.".$connections[$select_array_keys[$i]][$select_array_keys[$q]][1]);
					}
				}
			}
		}
		if (isset($where_query[0])) {
			$where_query_text = "";
			for($i=0;$i<count($where_query);$i++) {
				echo $where_query[$i];
				$where_query_text.= $where_query[$i];
				if ($i+1 != count($where_query)) {
					$where_query_text.=" AND ";
				}
				echo " ".$where_query_text."<br>";
			}
			$where_query = "WHERE ".$where_query_text;
		}
		$sql = "SELECT ".$select_query." FROM ".$from_query." ".$where_query."";
		echo $sql;
	}


	function select($while,$select,$from,$where = null,$order = null,$date_format='d.m.Y',$time_format='H:i',$debug = false, $type = null)
	{
		if ($where != NULL) {
			$where = "WHERE ".$where;
		}
		if ($order != NULL) {
			$order = "ORDER BY ".$order." DESC";
		}
		if (is_array($select)) {
			$select_query = implode(',', $select);
		}else{
			$select_query = $select;
		}
		$sql = "SELECT ".$select_query." FROM `".$from."` ".$where." ".$order."";
		if ($debug == true) {
		    print_r($sql);
		}
		$u_query = $this->con->query($sql);
		if ($u_query->num_rows != 0) {
		    $count_row = 0;
			while ($query_row = $u_query->fetch_array(MYSQLI_ASSOC)) {
				$select_count = 0;
				$count_select = count($query_row);
				$select_array = NULL;
				while ($select_count < $count_select) {
					if ($while == true) {
						$return_array[$count_row] = $query_row;
						$select_count++; 
					}else{
						$return_array = $query_row;
						$select_count++;
					}
				}
				$count_row++;
			}
			$fields = $this->get_table_fields($from);
			if ($while == true) {
				for($q=0;$q<count($return_array);$q++) {
					for($c=0;$c<count($fields);$c++) {
						if ($fields[$c]['Type'] == "date" AND isset($return_array[$q][$fields[$c]['Field']])) {
							$return_array[$q][$fields[$c]['Field']] = date($date_format,strtotime($return_array[$q][$fields[$c]['Field']]));
						}
						if ($fields[$c]['Type'] == "time" AND isset($return_array[$q][$fields[$c]['Field']])) {
							$return_array[$q][$fields[$c]['Field']] = date($time_format,strtotime($return_array[$q][$fields[$c]['Field']]));
						}
					}
				}
			}else{
				for($c=0;$c<count($fields);$c++) {
					if ($fields[$c]['Type'] == "date" AND isset($return_array[$fields[$c]['Field']])) {
						$return_array[$fields[$c]['Field']] = date($date_format,strtotime($return_array[$fields[$c]['Field']]));
					}
					if ($fields[$c]['Type'] == "time" AND isset($return_array[$fields[$c]['Field']])) {
						$return_array[$fields[$c]['Field']] = date($time_format,strtotime($return_array[$fields[$c]['Field']]));
					}
				}
			}
		}else{
			$return_array = 0;
		}
		return $return_array;
	}


	function update($from,$set,$where,$debug = false)
	{
		if ($where != NULL) {
			$where = "WHERE ".$where;
		}
		$fields = $this->get_table_fields($from);
		if (is_array($set)) {	
			for($i=0;$i<count($fields);$i++) {
				if (array_key_exists($fields[$i]['Field'],$set)) {
					$set[$fields[$i]['Field']] = $this->format_value($set[$fields[$i]['Field']],$fields[$i]['Type']);
				}
			}
		}
		if (is_array($set)) {
			$array_keys = array_keys($set);
			$set_query = "";
			for($i=0;$i<count($set);$i++) {
				if ($i+1 != count($set)) { 
					$set_query .= $array_keys[$i]."='".$set[$array_keys[$i]]."', ";
				}else{
					$set_query .= $array_keys[$i]."='".$set[$array_keys[$i]]."' ";					
				}
			}
		}else{
			$set_query = $set;
		}

		$update_sql = "UPDATE ".$from." SET ".$set_query." ".$where."";
		if ($debug == true) {
		    print_r($update_sql);
		}
		$update_query = $this->con->query($update_sql);
	}
	
	function delete($from,$where,$debug = false)
	{
		if ($where != NULL) {
			$where = "WHERE ".$where;
		}
		$delete_sql = "DELETE FROM ".$from." ".$where."";
		if ($debug == true) {
		    print_r($delete_sql);
		}
		$delete_query = $this->con->query($delete_sql);
		return $delete_query;
	}

	function insert($from,$insert,$check_array=array("*"),$debug = false)
	{
		$fields = $this->get_table_fields($from);
		if (is_array($insert)) {	
			for($i=0;$i<count($fields);$i++) {
				if (array_key_exists($fields[$i]['Field'],$insert)) {
					$insert[$fields[$i]['Field']] = $this->format_value($insert[$fields[$i]['Field']],$fields[$i]['Type']);
				}
			}
		}
		if (is_array($insert)) {
			$insert_query = array_keys($insert);
			for ($i=0;$i<count($insert_query);$i++) {
				$insert_query[$i] = "`".$insert_query[$i]."`";
			}
			$insert_query = implode(',',$insert_query);
			$values_query = array_values($insert);
			for ($i=0;$i<count($values_query);$i++) {
				$values_query[$i] = "'".$values_query[$i]."'";
			}
			$values_query = implode(',',$values_query);
		}else{
			return 0;
		}
		$insert_sql = "INSERT INTO ".$from." (".$insert_query.") VALUES (".$values_query.")";
		if ($debug == true) {
		    print_r($insert_sql);
		}
		$insert_query = $this->con->query($insert_sql);
		$where = array();
		$insert_keys = array_keys($insert);
		for($i=0;$i<count($insert);$i++) {
			array_push($where,$insert_keys[$i]."='".$insert[$insert_keys[$i]]."'");
		}
		$where = implode(" AND ",$where);
		$check = $this->select(false,$check_array,$from,$where);
		if ($debug == true) {
		    print_r($check);
		}
//		    print_r($check);
		return $check;
	}

	function __destruct()
	{
		mysqli_close($this->con);
		$this->db = false;
	}
}
?>