<?php declare(strict_types=1);
/**
 * @license MIT
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 *
 * This file is part of the NIRSAL AGC project by Skylab, please read the license document
 * available in the root level of the project
 */
namespace Skylab\NirsalAgc\Plugins\Bvn;

use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;

use Skylab\NirsalAgc\Plugins\NibssRequest\Endpoint as NibssEndpoint;

/**
 * class Search.
 *
 * Search Controller
 *
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since v0.0.1 26/07/2021 18:47
 */
class Search
{
	private static function interceptRequest(array $data) {
		$bvnList = $data["bvnList"];

		$forNibss = [];
		$notForNibss = [];

		$localBvn = self::getLocalBvn($bvnList);

		foreach ($localBvn as $bvn) {
			$notForNibss = $bvn["bvn"];
		}

		$forNibss = array_diff($bvnList, $notForNibss);

		$searchResponse = self::retrieveFromNibss($forNibss);

		return $searchResponse;
	}

	private static function retrieveFromNibss(array $bvnList) {
		$bvnListAsString = implode(",", $bvnList);
		$nibssResponse = NibssEndpoint::getMultipleBvn(["bvns"=>$bvnListAsString]);
		$response = [];

		foreach($nibssResponse as $bvn => $resp){
			if ($resp["ResponseCode"] == "00"){ //means bvn search returned valid data
				unset($resp["ResponseCode"], $resp["BVN"]);

				$response[$bvn] = self::indexBvnData($bvn, $resp);
			}
			else {
				$response[$bvn] = $resp;
			}
		}

		return $response;
	}

	private static function getLocalBvn(array $bvnList) {
		$bvnListAsString = implode(",", $bvnList);
		$query = "SELECT bvn FROM bvn_retrieved_bvns WHERE bvn IN ($bvnListAsString)";

		$result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
	}

	private static function indexBvnData(string $bvn, array $bvnData){
		$inserts = [];
		foreach($bvnData as $field => $value){
			$inserts[] = "('$bvn', $field', '$value')";
		}

		$query = "INSERT INTO bvn_retrieved_bvns (bvn, data_field, data_value) VALUES ".implode(",", $inserts);
		$response = DBConnectionFactory::getConnection()->exec($query);

		return $response;
	}

	public static function getBvnData(array $data)
	{
		$presearchData = self::interceptRequest($data);
		$requestedFields = $data["fields"];

		$response = [];
		$validBvns = [];

		foreach ($presearchData as $bvn=>$resp) {
			if ($resp == true) {
				$validBvns[] = $bvn;
			}
			else {
				$response[$bvn] = ["status"=>0, "reason"=>$resp];
			}
		}

		$bvnListAsString = implode(",", $validBvns);
		$fieldsAsString = implode(",", $requestedFields);

		$query = "SELECT a.bvn, a.data_field, a.data_value FROM bvn_retrieved_bvn_data a WHERE (a.data_field IN ($fieldsAsString)) AND (a.bvn IN $bvnListAsString)";

		$queryResult = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

		foreach ($queryResult as $result){
			$bvn = $result["bvn"];
			$field = $result["data_field"];
			$value = $result["data_value"];

			if (!isset($response[$bvn])){
				$response[$bvn] = [
					"status"=>1,
					"data"=>[]
				];
			}

			$response[$bvn]["data"][$field] = $value;
		}

		return $response;
	}
}