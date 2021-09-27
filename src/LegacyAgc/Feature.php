<?php declare(strict_types=1);
require_once dirname(__FILE__)."/SleekDB/Store.php";
/**
 * @license MIT
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 *
 * This file is part of the NIRSAL AGC project by Skylab, please read the license document
 * available in the root level of the project
 */
namespace Skylab\NirsalAgc\Plugins\LegacyAgc;

use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;

use Skylab\NirsalAgc\Plugins\NibssRequest\Endpoints as NibssEndpoint;

/**
 * class Feature.
 *
 * Feature Controller
 *
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since v0.0.1 27/09/2021 13:07
 */
class Feature
{
	public static function viewByPlotId(array $data=[]){
		$id = $data["plot_id"] ?? 0;

		$hash = crc32($id);

		$databaseDirectory = dirname(__FILE__) . "/legacy-agc-db";

		$db = new \SleekDB\Store("farmers", $databaseDirectory, ["primary_key"=>"_id"]);

		$info = $db->findById($hash);

		return $info;
	}
}