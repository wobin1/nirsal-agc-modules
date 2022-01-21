		<?php declare(strict_types=1);
		/**
		 * @license MIT
		 * @author Samuel Adeshina <samueladeshina73@gmail.com>
		 *
		 * This file is part of the NIRSAL AGC project by Skylab, please read the license document
		 * available in the root level of the project
		 */
		namespace Skylab\NirsalAgc\Plugins\Agc;

		use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
		use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;

		use Skylab\NirsalAgc\Plugins\NibssRequest\Endpoints as NibssEndpoint;

		/**
		 * class Application.
		 *
		 * Application Controller
		 *
		 * @author Samuel Adeshina <samueladeshina73@gmail.com>
		 * @since v0.0.1 03/10/2021 14:56
		 */
		class Application
		{
			public static function createNewAgc(array $data) {
				$user = $data["userId"];

				$query = "INSERT INTO agc_application (user_id) VALUES ($user)";
				$db = DBConnectionFactory::getConnection();
				$result = $db->exec($query);

				if ($result){
					$agcId = $db->lastInsertId();
					$termsStage = self::startStage([
						"agcId"=>$agcId,
						"stage"=>"T&C"
					]);

					return ["agcId"=>$agcId, "termsStage"=>$termsStage];
				}

				return $result;
			}

			// public static function startStage(array $data){
			// 	$applicationId = $data["agcId"];
			// 	$stage = $data["stage"];
			// 	$stageStatus = -1;

			// 	$query = "INSERT INTO agc_application_stage (application_id, stage_name, stage_status) VALUES ($applicationId, '$stage', $stageStatus)";
			// 	$db = DBConnectionFactory::getConnection();
			// 	$result = $db->exec($query);

			// 	if ($result){
			// 		return ["stageId"=>$db->lastInsertId()];
			// 	}

			// 	return $result;
			// }

			// public static function completeStage(int $stageId){
			// 	$stageStatus = 1;

			// 	$query = "UPDATE agc_application_stage SET stage_status=$stageStatus, last_modified=CURRENT_TIMESTAMP WHERE application_stage_id=$stageId";
			// 	$result = DBConnectionFactory::getConnection()->exec($query);

			// 	return $result;
			// }

			// public static function activateNextStage(array $data){
			// 	$stageId = $data["completedStageId"];
			// 	$nextStage = $data["nextStage"];
			// 	$agcId = $data["agcId"];

			// 	$complete = self::completeStage((int)$stageId);
			// 	$start = self::startStage([
			// 		"agcId"=>$agcId,
			// 		"stage"=>$nextStage
			// 	]);

			// 	return [$complete, $start];
			// }

			// public static function getStages(int $agcId){
			// 	$stages = [];
			// 	$query = "SELECT * FROM agc_application_stage WHERE application_id = $agcId";
			// 	$result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

			// 	foreach($result as $stage){
			// 		$stages[$stage["stage_name"]] = $stage;
			// 	}

			// 	return $stages;
			// }

			// public static function getStage(array $data){
			// 	$stageName = $data["stage"];
			// 	$agcId = $data["agcId"];

			// 	$query = "SELECT * FROM agc_application_stage WHERE application_id = $agcId AND stage_name='$stageName'";
			// 	$result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

			// 	return $result[0] ?? [];
			// }

			// public static function newKylData(array $data){
			// 	$agcId = $data["agcId"];
			// 	$leaders = $data["leaders"] ?? [];

			// 	$sqlValues = [];
			// 	foreach ($leaders as $leader){
			// 		$leaderData = [
			// 			$agcId, 
			// 			QB::wrapString($leader["bvn"] ?? '', "'"), 
			// 			QB::wrapString($leader["type"] ?? '', "'"),
			// 			QB::wrapString($leader["state"] ?? '', "'"),
			// 			QB::wrapString($leader["lga"] ?? '', "'"),
			// 			QB::wrapString($leader["address"] ?? '', "'"),
			// 			QB::wrapString($leader["academicQualification"] ?? '', "'"),
			// 			QB::wrapString($leader["workExperience"] ?? '', "'"),
			// 			QB::wrapString(json_encode($leader["questionnaire"] ?? ''), "'")
			// 		];

			// 		$sqlValues[] = "(".implode(",", $leaderData).")";
			// 	}

			// 	$query = "INSERT INTO agc_application_kyl_data (application_id, leader_bvn, kyl_leader_type, residential_state, residential_lga, contact_address, academic_qualification, work_experience, leader_questionnaire) VALUES ".implode($sqlValues, ",");

			// 	$result = DBConnectionFactory::getConnection()->exec($query);

			// 	return $result;
			// }

			// public static function newKycData(array $data){
			// 	$agcId = $data["agcId"];
			// 	$agcName = $data["agcName"];
			// 	$commodityCategory = $data["commodityCategory"];
			// 	$commodityType = $data["crop"];
			// 	$totalLandSize = $data["agcCapacity"];
			// 	$totalFarmers = $data["agcTotalFarmers"];
			// 	$state = $data["agcLocationState"];
			// 	$lga = $data["agcLocationLga"];
			// 	$city = $data["agcLocationVillage"];

			// 	$query = "INSERT INTO agc_application_kyc_data (application_id, agc_name, commodity_category, commodity_type, total_land_size_hectares, total_farmers, agc_state, agc_lga, agc_city) VALUES ($agcId, '$agcName', '$commodityCategory', '$crop', $totalLandSize, $totalFarmers, '$state', '$lga', '$city')";

			// 	$result = DBConnectionFactory::getConnection()->exec($query);

			// 	return $result;		
			// }

			// public static function  kycBvnValidationComplete(int $agcId){
			// 	$query = "UPDATE agc_application_kyc_data SET is_bvn_validated = 1 WHERE application_id = $agcId";
			// 	$result = DBConnectionFactory::getConnection()->exec($query);

			// 	return $result;
			// }

			// public static function kycCrcComplete(int $agcId){
			// 	$query = "UPDATE agc_application_kyc_data SET is_bvn_validated = 1 WHERE application_id = $agcId";
			// 	$result = DBConnectionFactory::getConnection()->exec($query);

			// 	return $result;
			// }

			// public static function getApplicationStage(int $agcId){
			// 	$query = "SELECT * FROM agc_application_stage WHERE application_id = $agc_city			d";
			// 	$result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

			// 	return $result[0] ?? [];
			// }		

			// public static function addKycFarmers(array $data){
			// 	$agcId = $data["agcId"];
			// 	$farmers = $data["farmers"] ?? [];

			// 	$sqlValues = [];
			// 	foreach($farmers as $farmer){
			// 		$farmerData =  [
			// 			$agcId, 
			// 			QB::wrapString($farmer["bvn"] ?? '', "'")
			// 		];

			// 		$sqlValues[] = "(".implode(",", $farmerData).")";
			// 	}

			// 	$query = "INSERT INTO agc_application_kyc_farmers (application_id, farmer_bvn) VALUES ".implode($sqlValues, ",");

			// 	$result = DBConnectionFactory::getConnection()->exec($query);

			// 	self::kycBvnValidationComplete((int)$agcId);

			// 	return $result;
			// }

		}