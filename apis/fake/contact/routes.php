<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

function addAPIRoutes($api){

    $scope = '';

	$api->get($scope.'/agenda', function(Request $request, Response $response, array $args) use ($api) {
		$agendas = [];
		$contacts = $api->db['sqlite']->fake_contact_list()->fetchAll();
		foreach($contacts as $c){
			if(!in_array($c["agenda_slug"], $agendas)) $agendas[] = $c["agenda_slug"];
		}

		return $response->withJson($agendas);
	});

	$api->get($scope.'/agenda/{agenda_slug}', function(Request $request, Response $response, array $args) use ($api) {
		$contacts = $api->db['sqlite']->fake_contact_list()
		                ->where('agenda_slug',$args['agenda_slug'])
		                ->fetchAll();

		return $response->withJson($contacts);
	});

	$api->get($scope.'/{contact_id}', function(Request $request, Response $response, array $args) use ($api) {

		$api->validate($args['contact_id'])->int();

        $row = $api->db['sqlite']->fake_contact_list()
			->where('id',$args['contact_id'])->fetch();
		return $response->withJson($row);
	});

	$api->post($scope.'/', function (Request $request, Response $response, array $args) use ($api) {

        $log = [];
        $parsedBody = $request->getParsedBody();
        if(!$parsedBody) throw new Exception('Invalid request body (check the request body json)', 400);

		$contacts = $api->db['sqlite']->fake_contact_list()
		                ->where('email',$parsedBody['email'])
		                ->where('agenda_slug',$parsedBody['agenda_slug'])
		                ->fetchAll();
		if($contacts) throw new Exception('The contact with email "'.$parsedBody['email'].'" already exists', 400);

        $contactToSave = [
			'full_name' => $api->validate($parsedBody['full_name'])->smallString(),
			'agenda_slug' => $api->validate($parsedBody['agenda_slug'])->smallString(),
			'email' => $api->validate($parsedBody['email'])->email(),
			'phone' => $api->validate($parsedBody['phone'])->smallString(),
			'address' => $api->validate($parsedBody['address'])->smallString(),
			'created_at' => date("Y-m-d H:i:s")
		];

		$row = $api->db['sqlite']->createRow( 'fake_contact_list', $contactToSave);
		$row->save();

        return $response->withJson($row);
	});

	$api->put($scope.'/{contact_id}', function (Request $request, Response $response, array $args) use ($api) {

        $log = [];
        $parsedBody = $request->getParsedBody();
        if(!$parsedBody) throw new Exception('Invalid request body (check the request body json)', 400);

        $contact = $api->db['sqlite']->fake_contact_list()
			->where('id',$args['contact_id'])->fetch();
		if(!$contact) throw new Exception('The contact does not exist', 404);

        $value = $api->optional($parsedBody,'full_name')->bigString();
        if($value) $contact->full_name = $value;

        $value = $api->optional($parsedBody,'email')->bigString();
        if($value) $contact->email = $value;

        $value = $api->optional($parsedBody,'phone')->bigString();
        if($value) $contact->phone = $value;

        $value = $api->optional($parsedBody,'address')->bigString();
        if($value) $contact->address = $value;

		$contact->save();

        return $response->withJson($contact);
	});

	$api->delete($scope.'/{contact_id}', function(Request $request, Response $response, array $args) use ($api) {

		if(empty($args['contact_id'])) throw new Exception('Invalid param contact_id', 400);

		if($args['contact_id'] == 'all'){
		    $contacts = $api->db['sqlite']->fake_contact_list()->fetchAll();
		    foreach($contacts as $c) $c->delete();
		}
		else{
    		$row = $api->db['sqlite']->fake_contact_list()->where('id',$args['contact_id'])->fetch();
    		if(!$row) throw new Exception('Contact with ID '.$args['contact_id'].' not found', 404);

    		$row->delete();
		}

		return $response->withJson([ "msg" => "ok" ]);
	});

	return $api;
}