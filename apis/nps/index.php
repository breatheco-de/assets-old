<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require_once('../JsonPDO.php');
	require_once('../SlimAPI.php');
	require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
	require('routes.php');
	
	use Carbon\Carbon;
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new SlimAPI([
		'debug' => API_DEBUG
	]);
	
	$pdo = new \PDO( 'sqlite:db.sqlite3' );
	$db = new \LessQL\Database( $pdo );
	$db->setPrimary( 'response', ['user_id'] );
	$api->addDB('sqlite', $db);
	
    use \BreatheCode\BCWrapper;
    BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
    BCWrapper::setToken(BREATHECODE_TOKEN);

	$api = addAPIRoutes($api);
	$api->run(); 