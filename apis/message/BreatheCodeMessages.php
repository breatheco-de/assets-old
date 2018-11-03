<?php
use Aws\Ses\SesClient;
use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\Datastore\Query\Query;

class BreatheCodeMessages{
    
    private static $_messages = [
        "nps_survey" => [ 
            "track_on_log" => true,
            "type" => 'actionable',
            "priority" => 'HIGH'
        ]
    ];
    
    private static $messageType = ['actionable','non-actionable'];
    
    public static function getType($type){
        if(!in_array(self::messageType)) throw new Exception('Ivalid Message Type');
        else return $type;
    }
    
    private static function connect(){
        return new DatastoreClient([ 
            'projectId' => 'breathecode-197918',
            'keyFilePath' => '../../breathecode-efde1976e6d3.json'
        ]);
    }
    
    public static function sendMail($slug, $to, $subject, $data){
        
        $templates = self::getTemplateName($slug);
        $client = SesClient::factory(array(
            'version'=> 'latest',     
            'region' => 'us-west-2',
            'credentials' => [
                'key'    => S3_KEY,
                'secret' => S3_SECRETE,
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
                            'Data' => $templates["html"]->render($data),
                        ],
            			'Text' => [
                            'Charset' => 'UTF-8',
                            'Data' => $templates["txt"]->render($data),
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
            throw new Exception("The email was not sent. Error message: ".$error->getAwsErrorMessage()."\n");
        }
    }
    
    public static function markAsAnswred($messageKey, $data='No additional data'){
        
        if(!$messageKey) throw new Exception('The missing message id');
        
        $datastore = self::connect();
        $key = $datastore->key('student_message', $messageKey);
        $entity = $datastore->lookup($key);
        
        if(!$entity) throw new Exception("Message not found", 404);
        
        // Update the entity
        $entity['read'] = 'true';
        $entity['answered'] = 'true';
        
        if(is_string($data) || is_numeric($data) || is_bool($data)) $entity['data'] = (string) $data;
        else if(is_object($data) || is_array($data)) $entity['data'] = json_encode($data);
        else if(!$data) $entity['data'] = 'No additional data';
        else throw new Exception("Invalid data format for message", 400);
        
        $datastore->update($entity);
        
        return $entity;
    }
    
    public static function markAsRead($messageKey, $data='No additional data'){
        
        if(!$messageKey) throw new Exception('The missing message id');
        
        $datastore = self::connect();
        $key = $datastore->key('student_message', $messageKey);
        $entity = $datastore->lookup($key);
        
        if(!$entity) throw new Exception("Message not found", 404);
        
        // Update the entity
        $entity['read'] = 'true';
        
        if(is_string($data) || is_numeric($data) || is_bool($data)) $entity['data'] = (string) $data;
        else if(is_object($data) || is_array($data)) $entity['data'] = json_encode($data);
        else if(!$data) $entity['data'] = 'No additional data';
        else throw new Exception("Invalid data format for message", 400);
        
        $datastore->update($entity);
        
        return $entity;
    }
    
    public static function addMessage($messageSlug, $student, $priority='LOW', $data='No additional data'){
        
        if(!isset(self::$_messages[$messageSlug])) throw new Exception('Invalid message slug '.$messageSlug);
        if(!isset(self::$_messages[$messageSlug]["type"])) throw new Exception('The message '.$messageSlug.' has an invalid type');
        
        $datastore = self::connect();
        
        $message = [
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'slug' => $messageSlug,
            'read' => false,
            'answered' => false,
            'priority' => isset(self::$_messages[$messageSlug]["priority"]) ? self::$_messages[$messageSlug]["priority"] : $priority,
            'type' => self::$_messages[$messageSlug]["type"],
            'data' => $data
        ];

        if(is_string($student)) $message['email'] = $student;
        else{
            //print_r($student); die();
            if(!empty($student->id)) $message['user_id'] = (string) $student->id;
            
            if(!empty($student->email)) $message['email'] = (string) $student->email;
            else if(!empty($student->username)) $message['email'] = (string) $student->username;
        }
        
        $record = $datastore->entity('student_message', $message);
        return $datastore->insert($record);
    }
    
    private static function filter($query, $filters){
        if(!empty($filters["slug"])) $query->filter('slug', '=', (string) $filters["slug"]);
        if(!empty($filters["user_id"])){
            $query->filter('user_id', '=', (string) $filters["user_id"]);
        } 
        if(!empty($filters["email"])) $query->filter('email', '=', (string) $filters["email"]);
        if(!empty($filters["priority"])) $query = $query->filter('priority', '=', (string) $filters["priority"]);
        
        return $query;
    }
    
    public static function getMessages($filters=[]){
        $datastore = self::connect();
        
        $query = $datastore->query()->kind('student_message');
        $items = $datastore->runQuery(self::filter($query, $filters));

        $results = [];
        foreach($items as $ans) {
            $results[] = $ans->get();
        }
        return $results;
    }
    
    public static function deleteMessages($filters=[]){
        $datastore = self::connect();
        
        $query = $datastore->query()->kind('student_message');
        //$query = $query->order('created_at', Query::ORDER_DESCENDING);
        $messages = $datastore->runQuery(self::filter($query, $filters));
        
        $transaction = $datastore->transaction();
        foreach($messages as $msg){
            $transaction->delete($msg->key());
        }
        $transaction->commit();
        
        return true;
    }
    
    public static function getTemplateName($slug){
        
        $basePath = './templates/';
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
    
}