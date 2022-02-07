	public static function getCost(int $agcId){
		$query = "SELECT cost FROM cost_incured WHERE application_id = $agcId";
		$result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
	}

	public static function updateCost(int $agcId, int $updatedCost){
		$query = "UPDATE cost_incured SET cost = $updatedCost WHERE application_id = $agcId";
		$result = DBConnectionFactory::getConnection()->exec($query);

		return $result;
	}


		foreach($farmerData as $data){
			$agcId = $data["application_id"];
			$crsStatus = $data["CRC"];

			$values[] = "('$crsStatus')";
			$values2 = "($agcId)";
			
	}



		$data = implode($values, ',');
	
		$query = "UPDATE agc_application_farmers_data SET crc_status =".implode($values, ","). "WHERE application_id =".implode($values2. ",");

		$result = DBConnectionFactory::getConnection()->exec($query);
		return $result;