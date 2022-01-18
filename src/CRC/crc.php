<?php
	

	public static function crcDetailsInsert(array $data){
		$db = $conn;
		$name = $data["name"];
		$bvn = $data["bvn"];
		$phoneNumber = $data["phoneNumber"]
		

		$query = "INSERT INTO crc_member(agcId, name, bvn, phoneNumber)"
		$result = $db->exec($query);

		if($result){
			return ["name"=>$db->lastInsertId()]
		}
	}

	public static function updateCrcDetails(array $data, int $crc_id){
		$db = $conn;
		$name = $data[""];
		$bvn = $data[""];
		$phoneNumber = $data[""];

		$query = "UPDATE crc_member SET name=$name, bvn=$bvn, phoneNumber=$phoneNumber WHERE crcDetailsInsert_id=$crc_id";
		$result = $db->exec($query)

		if($result){
			return ["name"=>$]
		}
	}


?>