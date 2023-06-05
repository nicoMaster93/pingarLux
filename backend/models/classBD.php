<?php
class classBD extends Database{
	
	function uuid(){
		try{
				$sql = 'SELECT UUID()';
				$query = $this->conn->prepare($sql);
				$query->execute();
				$uuid = $query->fetchColumn();
				return $uuid;
			}
			catch(PDOException $e){
				echo $e->getMessage();
				return false;
				}
	}
	function getDataTableBySql($sql,$params=[]){
		try{
				$query = $this->conn->prepare($sql);
				if(!empty($params)){
					foreach ($params as $k => &$v){
						$seq = ($k+1);
						$query->bindParam($seq, $v);
					}
				}
				$query->execute();
				// $query->debugDumpParams();/* Retorna log de la consulta */
				$row = $query->fetchAll(PDO::FETCH_ASSOC);
				return $row;
			}
			catch(PDOException $e){
				echo $e->getMessage();
				return false;
				}
	}
	function getDataTable($tabla,$condicion=[],$debug=false){
		try{
				$createSQL = $selects = $joins= $conditions = $groups = $order= $limit = null;
				if(isset($condicion['selects']) && !empty($condicion['selects'])){
					$selects = "SELECT {$condicion['selects']} FROM $tabla";
				}else{
					$selects = "SELECT * FROM $tabla";
				}
				if(isset($condicion['joins']) && !empty($condicion['joins'])){
					$joins = $condicion['joins'];
				}else{
					$joins = "";
				}
				if(isset($condicion['condition']) && !empty($condicion['condition'][0])){
					$conditions =  ["WHERE",$condicion['condition'][0]];
					$conditions = implode(" ", $conditions);
					$countConditions = substr_count($condicion['condition'][0],DOT);
					array_shift($condicion['condition']);
					if(count($condicion['condition']) != $countConditions){
						throw new Exception("Error en la condicion de la consulta ", 1);
					}
				}
				if(isset($condicion['groups']) && !empty($condicion['groups'])){
					$groups = "GROUP BY {$condicion['groups']}";
				}else{
					$groups = "";
				}
				if(isset($condicion['order']) && !empty($condicion['order'])){
					if(!is_array($condicion['order']) || count($condicion['order']) == 1){
						throw new Exception("El orden de la consulta es inválido", 1);
					}
					$order = "ORDER BY {$condicion['order'][0]} {$condicion['order'][1]}";
				}else{
					$order = "";
				}
				if(isset($condicion['limit']) && !empty($condicion['limit'])){
					$limit = "LIMIT {$condicion['limit']}";
				}else{
					$limit = "";
				}

				$createSQL = "$selects $joins $conditions $groups $order $limit";
				$query = $this->conn->prepare($createSQL);
				
				if(!is_null($conditions)){
					foreach ($condicion['condition'] as $k => &$v) {
						$seq = ($k+1);
						$query->bindParam($seq, $v);
				   }
				}
				$query->execute();
				if($debug){
					$query->debugDumpParams();/* Retorna log de la consulta */
				}
				$row = $query->fetchAll(PDO::FETCH_ASSOC);
				return $row;
			}
			catch(PDOException $e){
				echo $e->getMessage();
				return false;
				}
	}
	function updateDataTable($tabla,$post,$condicion) {
		try{
			/*
			$post = array('campoTabla' => 'valorAsignar')
			*/
			unset($post['val']);
			$arraySentencia = array();
			foreach ($post as $key => $value) {
				$sent = " `{$key}` = :{$key} ";
				array_push($arraySentencia, $sent);
			}
			$sentenciaSql =  "UPDATE {$tabla} SET ".implode(',', $arraySentencia) .$condicion;
			$sql = $this->conn->prepare($sentenciaSql);
			foreach ($post as $key2 => &$value2) {
				$sql->bindParam(':'.$key2, $value2);
			}
			// $sql->debugDumpParams();/* Retorna log de la consulta */
			$sql->execute();
			return true;
			}
			catch(PDOException $e){
				echo $e->getMessage();
				return false;
			}
	}
	function insertDataTable($tabla,$post) {
		try{
			/*
			$post = array('campoTabla' => 'valorAsignar')
			*/
			$sentenciaSql = "INSERT INTO `{$tabla}` (";
			$campos = array();
			foreach ($post as $key => $value) {
				array_push($campos, "`{$key}`");
			}
			
			$sentenciaSql .= implode(",", $campos)." ) VALUES ( ";
			$valuesEncode = array();
			foreach ($post as $key2 => $value2) {
				array_push($valuesEncode, ":{$key2}");
			}
			$sentenciaSql .= implode(",", $valuesEncode)." ) ";
			// echo $sentenciaSql;
			$sql = $this->conn->prepare($sentenciaSql);
			foreach ($post as $key3 => &$value3) {
				$sql->bindParam(':'.$key3, $value3);
			}
			// $query->debugDumpParams();/* Retorna log de la consulta */
			$sql->execute();
			$id = $this->getDataTableBySql("SELECT @@identity AS id");
			return $id[0]['id'];
		}
		catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	function saveTable($tabla,$post,$condicion=false){
		if(!$condicion){
			$save = $this->insertDataTable($tabla,$post);
		}else{
			$save = $this->updateDataTable($tabla,$post,$condicion);
		}
		return $save;
	}
	function deleteDataTable($table,$conditions=[]){
		try{
			$sql = "DELETE FROM {$table}  where {$conditions[0]}";
			array_shift($conditions);
			$query = $this->conn->prepare($sql);
			if(!empty($conditions)){
				foreach ($conditions as $k => &$v){
					$seq = ($k+1);
					$query->bindParam($seq, $v);
				}
			}
			$query->execute();
			// $query->debugDumpParams();/* Retorna log de la consulta */
			return true;
		}
		catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
}
?>