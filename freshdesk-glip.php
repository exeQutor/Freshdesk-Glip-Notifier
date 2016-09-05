<?php

require_once 'vendor/autoload.php';

// freshdesk
$endpoint = 'https://burgosoft.freshdesk.com/api/v2/tickets?include=requester';
$apikey = 'z5rMAyPBO97FLNR31wey';

// glip
$webhook = 'https://hooks.glip.com/webhook/cff12c5c-4805-4f8f-9357-4501cbdf899e';

// tickets priority
$priority = array(
	'UNKNOWN', 'LOW', '*MEDIUM*', '**HIGH**', '***URGENT***'
	);

// get tickets from freshdesk
$tickets = get_tickets();

foreach ($tickets as $ticket)
{
	$body = '';
	
	if (strtotime($ticket->created_at) >= strtotime("-1 minute") && $ticket->status == 2)
	{
		// post notification to glip
		$message = post_notification($ticket);
		
		echo '<pre>';
		print_r($message);
		echo '</pre>';
	}
}

function get_tickets()
{
	global $endpoint;
	global $apikey;
	
	$headers = array('Content-Type' => 'application/json');
	$body = Unirest\Request\Body::json($data);
	Unirest\Request::auth($apikey, 'X');
	$response = Unirest\Request::get($endpoint, $headers, $body);
	return $response->body;
}

function post_notification($ticket)
{
	global $webhook;
	global $priority;
	
	$headers = array('Content-Type' => 'application/json');
	
	$body .= $ticket->description_text;
	$body .= "\n\n";
	$body .= "> Ticket: https://burgosoft.freshdesk.com/helpdesk/tickets/{$ticket->id}\n";
	$body .= "> Priority: {$priority[$ticket->priority]}\n";
	$body .= "> Requester: {$ticket->requester->name} <{$ticket->requester->email}>\n";
	
	$data = array(
		'icon' => 'https://freshdesk.com/files/1813/9885/7672/favicon.ico',
		'activity' => "Freshdesk",
		'title' => "**{$ticket->subject}**",
		'body' => $body
		);
	
	$body = Unirest\Request\Body::json($data);
	$response = Unirest\Request::post($webhook, $headers, $body);
	
	return $response->body;
}