<?php


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
require('./ZapManager.php');

use \BreatheCode\BCWrapper as BC;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

\AC\ACAPI::start(AC_API_KEY);
\AC\ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);

require('../BreatheCodeMessages.php');
BreatheCodeMessages::connect([
    'projectId' => GOOGLE_PROJECT_ID,
    'keyFilePath' => '../../breathecode-47bde0820564.json'
]);

function addAPIRoutes($api){

    $api->addTokenGenerationPath();

	$api->get('/{zap_slug}/actions', function (Request $request, Response $response, array $args) use ($api) {

        if(empty($args['zap_slug'])) throw new Exception('You need to specify the zap_slug status', 400);

        $actions = ZapManager::getActionsFromZap($args['zap_slug']);

        $actions = array_map(function($act){
            $aux = ZapManager::getActionsDetails($act);
            $aux['slug'] = $act;
            unset($aux['method']);
            unset($aux['api_scope']);
            return $aux;
        }, $actions);
        return $response->withJson($actions);
	})->add($api->auth());

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {

        $zaps = ZapManager::getZaps($withDetails=true);
        return $response->withJson($zaps);

	})->add($api->auth());

	$api->post('/execute/nps_survey_cohort', function (Request $request, Response $response, array $args) use ($api) {

        $payload = $request->getParsedBody();
        if(empty($payload['cohort_slug'])) throw new Exception('Invalid or missing cohort_slug');

		$token = str_replace("JWT ","",$request->getHeader('Authorization'));
		if(is_array($token)) $token = $token[0];

    	$students = BC::getStudentsFromCohort(['cohort_id' => $payload['cohort_slug']]);
    	$cohort = BC::getCohort(['cohort_id' => $payload['cohort_slug']]);
    	$count = 0;
    	$errors = 0;
    	$ignored = 0;
		foreach($students as $std){
			if(in_array($std->status,["currently_active", "studies_finished"])){
				$count++;
				try{
				    $messages = BreatheCodeMessages::getMessages([ 'email' => "a.alejo@gmail.com", 'slug' => 'nps_survey' ]);
				    if(count($messages) == 0) BreatheCodeMessages::addMessage('nps_survey', $std, 'HIGH', [ 'token' => $token, 'cohort_stage' => $cohort->stage ]);
				}catch(Exception $e){
				    $errors++;
				}
			}
			else{
				$ignored++;
			}
		};

    	return $response->withJson([ "msg" => "$count students notified, $ignored ignored (there are not currently_active or studies_finished) and $errors gave errors", "total" => $count]);

	});//->add($api->auth());

	return $api;
}