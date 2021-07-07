<?php declare(strict_types=1);
/**
 * @license MIT
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 *
 * This file is part of the NIRSAL AGC project by Skylab, please read the license document
 * available in the root level of the project
 */
namespace Skylab\Nirsal\NibssRequest;

use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;

/**
 * class Endpoints.
 *
 * Endpoints Controller
 *
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since v0.0.1 07/07/2021 14:28
 */
class Endpoints
{
	public static function reset()
	{
		
	}

	public static function verifySingleBvn(array $data)
	{
		$request = new XHttpRequest();
		$response = $request->httpPostRequest("/VerifySingleBvn", [
			"BVN"=>$data["bvn"]
		]);

		return $response;
	}
	
	public static function verifyMultipleBvn(array $data)
	{
		
	}

	public static function getSingleBvn(array $data)
	{
		
	}
	
	public static function getMultipleBvn(array $data)
	{
		
	}
}