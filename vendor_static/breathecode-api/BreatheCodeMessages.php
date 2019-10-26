<?php

namespace BreatheCode;

use Aws\Ses\SesClient;
use Kreait\Firebase;
use Exception;
use DateTime;
//use Kreait\Firebase\Messaging\CloudMessage;
use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\Datastore\Query\Query;

class BreatheCodeMessages{

    private static $_messages = [];
    private static $datastore = null;

    private static $messageType = ['actionable','non-actionable'];
    private static $priorityType = ['HIGH','LOW'];


    public static function getType($type){
        if(!in_array(self::messageType)) throw new \Exception('Ivalid Message Type', 400);
        else return $type;
    }

    public static function connect($params){

        self::$_messages = [
            "nps_survey" => [
                "track_on_log" => true,
                "track_on_active_campaign" => true,
                "send_email" => true,
                "type" => 'actionable',
                "priority" => 'HIGH',
                "template" => [
                    "subject" => "Take 20 seconds to give us some feedback",
            		"intro" => "Hello! We need some feedback from your side, it's the only way to become a better academy and it's only one small question, we would really appreciate your answer",
            		"question" => "How likely are you to recommend 4Geeks Academy to your friends or colleagues?"
                ],
                "getURL" => function($student){
                    $id = '';
                    if(!empty($student->id)) $id = $student->id;
                    else if(is_string($student)) $id = $student;

                    return 'https://assets.breatheco.de/apps/nps/survey/'.$id;
                }
            ],
            "job_position" => [
                "track_on_log" => false,
                "track_on_active_campaign" => false,
                "send_email" => true,
                "type" => 'non-actionable',
                "priority" => 'LOW',
                "template" => [
                    "subject" => "Take 20 seconds to give us some feedback",
            		"intro" => "Hello! We need some feedback from your side, it's the only way to become a better academy and it's only one small question, we would really appreciate your answer",
            		"question" => "How likely are you to recommend 4Geeks Academy to your friends or colleagues?"
                ],
                "getURL" => function($student){
                    $id = '';
                    if(!empty($student->id)) $id = $student->id;
                    else if(is_string($student)) $id = $student;

                    return 'https://assets.breatheco.de/apps/nps/survey/'.$id;
                }
            ]
        ];

        self::$datastore = new DatastoreClient($params);
        \BreatheCode\BreatheCodeLogger::addMessagesToActivities(self::$_messages);
        \BreatheCode\BreatheCodeLogger::setDatastore(self::$datastore);
    }

    public static function sendMail($slug, $to, $subject, $data){

        $template = self::getEmailTemplate($slug);
        $client = SesClient::factory(array(
            'version'=> 'latest',
            'region' => 'us-west-2',
            'credentials' => [
                'key'    => S3_KEY,
                'secret' => S3_SECRET,
            ]
        ));

        try {
             $result = $client->sendEmail([
                'Destination' => [
                    'ToAddresses' => [
                        $to,
                    ],
                ],
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => 'UTF-8',
                            'Data' => $template["html"]->render($data),
                        ],
            			'Text' => [
                            'Charset' => 'UTF-8',
                            'Data' => $template["txt"]->render($data),
                        ],
                    ],
                    'Subject' => [
                        'Charset' => 'UTF-8',
                        'Data' => $subject,
                    ],
                ],
                'Source' => 'info@breatheco.de',
                //'ConfigurationSetName' => 'ConfigSet',
            ]);
             $messageId = $result->get('MessageId');
             return true;

        } catch (SesException $error) {
            throw new \Exception("The email was not sent. Error message: ".$error->getAwsErrorMessage()."\n");
        }
    }

    public static function markAsAnswred($messageKey, $data='No additional data'){

        if(!$messageKey) throw new \Exception('The missing message id');

        $key = self::$datastore->key('student_message', $messageKey);
        $entity = self::$datastore->lookup($key);

        if(!$entity) throw new \Exception("Message not found", 404);

        // Update the entity
        $entity['read'] = 'true';
        $entity['answered'] = 'true';

        if(is_string($data) || is_numeric($data) || is_bool($data)) $entity['data'] = (string) $data;
        else if(is_object($data) || is_array($data)) $entity['data'] = json_encode($data);
        else if(!$data) $entity['data'] = 'No additional data';
        else throw new \Exception("Invalid data format for message", 400);

        self::$datastore->update($entity);

        return $entity;
    }

    public static function markManyAs($status, $filters, $data='No additional data'){

        if($status !== 'read' and $status !== 'answered') throw new \Exception('invalid new status: '.$status, 400);
        if(empty($filters['user_id'])) throw new \Exception('invalid or missing user_id',400);

        if(is_string($data) || is_numeric($data) || is_bool($data)) $entity['data'] = (string) $data;
        else if(is_object($data) || is_array($data)) $entity['data'] = json_encode($data);
        else if(!$data) $entity['data'] = 'No additional data';
        else throw new \Exception("Invalid data format for message", 400);

        $query = self::$datastore->query()->kind('student_message');
        $query = self::filter($query, $filters);

        //exclude the ones that already have the status
        //if($status == 'answered') $query = $query->filter('answered', '=', 'false');
        //if($status == 'read') $query = $query->filter('read', '=', 'false');

        $items = self::$datastore->runQuery($query);

        $messages = [];
        foreach($items as $message) {
            if($status === 'read') $message['read'] = 'true';
            else if($status === 'answered'){
                $message['read'] = 'true';
                $message['answered'] = 'true';
            }
            $messages[] = $message->get();
            self::$datastore->update($message);
        }

        return $messages;
    }

    public static function markAsRead($messageKey, $data='No additional data'){

        if(!$messageKey) throw new \Exception('The missing message id');

        $key = self::$datastore->key('student_message', $messageKey);
        $entity = self::$datastore->lookup($key);

        if(!$entity) throw new \Exception("Message not found", 404);
        if($entity['type'] === 'actionable') throw new \Exception("This cannot be mark as read because it has a HIGH priority, it has to be answered.", 400);

        // Update the entity
        $entity['read'] = 'true';

        if(is_string($data) || is_numeric($data) || is_bool($data)) $entity['data'] = (string) $data;
        else if(is_object($data) || is_array($data)) $entity['data'] = json_encode($data);
        else if(!$data) $entity['data'] = 'No additional data';
        else throw new \Exception("Invalid data format for message", 400);

        self::$datastore->update($entity);

        return $entity;
    }

    public static function addCustomMessage($messageObject, $student, $priority=null){

        if($priority != null) $messageObject["priority"] = $priority;
        else if(!isset($messageObject["priority"])) $messageObject["priority"] = "LOW";

        if(!in_array(strtoupper($messageObject["priority"]), self::$priorityType)) throw new Exception('Invalid priority '.$messageObject["priority"]);

        if(!isset($messageObject["type"]) || !in_array(strtolower($messageObject["type"]), self::$messageType))
            throw new Exception('Invalid message type: '.$messageObject["type"]);

        if(!isset($messageObject["subject"]) || empty($messageObject["type"]))
            throw new Exception('Invalid or empty message subject');

        if($messageObject["type"] == 'actionable')
            if(!isset($messageObject["url"]))
                throw new \Exception('Missing to have url for the actionable message, add a url or make it non-actionable');

        $message = [
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'slug' => 'custom_message',
            'read' => false,
            'answered' => false,
            'priority' => $messageObject["priority"],
            'subject' => $messageObject["subject"],
            'type' => $messageObject["type"],
            'url' => $messageObject["url"]
        ];


        // $firebase = (new Firebase\Factory())->createMessaging();
        // $fmsMsg = CloudMessage::fromArray([
        //     'token' => 'euiptJWhvTQ:APA91bHcnaWMBQmJZkwcBoWTbRmK9Gvnm48XogH0dWDh2rhLkDBMx6PVfZM3_Gb9c3jfqLK6BHmauBn8aq5li0IHeHkJ3PLgLMiMkYeLlOhnyvQIh6e5O55aMuTYixO7mu22rFKdXhz-',
        //     'notification' => [
        //         'title' => $messageObject["subject"],
        //     ],
        //     'data' => $messageObject
        // ]);
        // $firebase->send($fmsMsg);


        if(is_string($student)) $message['email'] = $student;
        else{
            if(!empty($student->status) && !in_array($student->status, ['currently_active','studies_finished'])) throw new \Exception('This student is not currently_active or studies_finished', 400);
            if(!empty($student->id)) $message['user_id'] = (string) $student->id;

            if(!empty($student->email)) $message['email'] = (string) $student->email;
            else if(!empty($student->username)) $message['email'] = (string) $student->username;
        }

        $record = self::$datastore->entity('student_message', $message);
        $result = self::$datastore->insert($record);
    }

    public static function addMessage($messageSlug, $student, $priority=null, $data='No additional data'){

        if(!isset(self::$_messages[$messageSlug])) throw new \Exception('Invalid message slug '.$messageSlug);
        if(!isset(self::$_messages[$messageSlug]["type"])) throw new \Exception('The message '.$messageSlug.' has an invalid type');

        if(self::$_messages[$messageSlug]["type"] == 'actionable')
            if(!isset(self::$_messages[$messageSlug]["getURL"]) || !is_callable(self::$_messages[$messageSlug]["getURL"]))
                throw new \Exception('The message type: '.self::$_messages[$messageSlug]["type"].' needs to have a getURL method for its actions to be resolved');

        $url = self::$_messages[$messageSlug]["getURL"]($student);

        $token = '';
        if(!empty($data["token"])){
            $token = $data["token"];
            unset($data["token"]);
        }


        $message = [
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'slug' => $messageSlug,
            'read' => false,
            'answered' => false,
            'priority' => (!$priority) ? self::$_messages[$messageSlug]["priority"] : $priority,
            'subject' => $_messages[$messageSlug]["template"]["subject"],
            'type' => self::$_messages[$messageSlug]["type"],
            'data' => $data,
            'url' => $url
        ];

        if(is_string($student)) $message['email'] = $student;
        else{
            if(!empty($student->status) && !in_array($student->status, ['currently_active','studies_finished'])) throw new \Exception('This student is not currently_active or studies_finished', 400);
            if(!empty($student->id)) $message['user_id'] = (string) $student->id;

            if(!empty($student->email)) $message['email'] = (string) $student->email;
            else if(!empty($student->username)) $message['email'] = (string) $student->username;
        }

        $record = self::$datastore->entity('student_message', $message);
        $result = self::$datastore->insert($record);

        if($result){
            if(self::$_messages[$messageSlug]["send_email"]){
                $template = self::$_messages[$messageSlug]["template"];
                $template["url"] = $url;
                if($token != '') $template["url"] .= ((strpos($template["url"],'?')) ? '&':'?').'access_token='.$token;

                self::sendMail($messageSlug, $student->email, $template["subject"], $template);
            }
            \BreatheCode\BreatheCodeLogger::logActivity([
                "slug" => $messageSlug,
                "data" => $data
            ], $student);
        }
    }

    private static function filter($query, $filters){
        if(!empty($filters["slug"])) $query->filter('slug', '=', (string) $filters["slug"]);
        if(!empty($filters["user_id"])){
            $query->filter('user_id', '=', (string) $filters["user_id"]);
        }
        if(!empty($filters["email"])) $query->filter('email', '=', (string) $filters["email"]);
        if(!empty($filters["type"])) $query->filter('type', '=', (string) $filters["type"]);
        if(!empty($filters["slug"])) $query->filter('slug', '=', (string) $filters["slug"]);
        if(!empty($filters["priority"])) $query = $query->filter('priority', '=', (string) $filters["priority"]);

        return $query;
    }

    public static function getMessages($filters=[]){

        $query = self::$datastore->query()->kind('student_message');
        $items = self::$datastore->runQuery(self::filter($query, $filters));

        $results = [];
        foreach($items as $ans) {
            $message = $ans->get();
            $message['key'] = $ans->key()->pathEndIdentifier();
            unset($message['email']);
            $results[] = $message;
        }
        return $results;
    }

    public static function deleteMessages($filters=[]){

        $query = self::$datastore->query()->kind('student_message');
        //$query = $query->order('created_at', Query::ORDER_DESCENDING);
        $messages = self::$datastore->runQuery(self::filter($query, $filters));

        $transaction = self::$datastore->transaction();
        foreach($messages as $msg){
            $transaction->delete($msg->key());
        }
        $transaction->commit();

        return true;
    }

    public static function getEmailTemplate($slug){

        $basePath = '../message/_templates/';
        if(!file_exists($basePath.$slug.'.html')) throw new Error('Missing HTML template for slug: '.$slug);
        if(!file_exists($basePath.$slug.'.txt')) throw new Error('Missing TXT template for slug: '.$slug);

        else{
            $loader = new \Twig_Loader_Filesystem($basePath);
            $twig = new \Twig_Environment($loader);
            return [
                "html" => $twig->load($slug.'.html'),
                "txt" => $twig->load($slug.'.txt')
            ];
        }
    }

    public static function getTemplates($slug=null){

        if(!$slug) return self::$_messages;
        else if(!empty(self::$_messages[$slug])) return self::$_messages[$slug];
        else throw new \Exception("Inalid message type", 400);
    }

}