<?php
use Aws\Ses\SesClient;
use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\Datastore\Query\Query;

class BreatheCodeMessages{
    
    private static $_messages = [
        "nps_survey" => [ 
            "track_on_log" => true,
            "type" => 'actionable'
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
    
    public static function addMessage($messageSlug, $student, $data='No additional data'){
        
        if(!isset(self::$_messages[$messageSlug])) throw new Exception('Invalid message slug '.$messageSlug);
        if(!isset(self::$_messages[$messageSlug]["type"])) throw new Exception('The message '.$messageSlug.' has an invalid type');
        
        $datastore = self::connect();
        
        $message = [
            'created_at' => new DateTime(),
            'slug' => $messageSlug,
            'read' => false,
            'answered' => false,
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
        $datastore->insert($record);
    }
    
    public static function getMessage($filters){
        $datastore = self::connect();
        
        $query = $datastore->query()->kind('student_message');
        if(!empty($filters["slug"])) $query = $query->filter('slug', '=', $filters["slug"]);
        if(!empty($filters["user_id"])){
            $query = $query->filter('user_id', '=', $filters["user_id"]);
        } 
        if(!empty($filters["email"])) $query = $query->filter('email', '=', $filters["email"]);
        //$query = $query->order('created_at', Query::ORDER_DESCENDING);
        $items = $datastore->runQuery($query);
        $results = [];
        foreach($items as $ans) {
            $results[] = [
                "user_id" => $ans->user_id,
                "email" => $ans->email,
                "data" => $ans->data,
                "read" => $ans->read,
                "answered" => $ans->answered,
                "type" => $ans->type,
                "created_at" => $ans->created_at,
                "slug" => $ans->slug,
            ];
        }
        return $results;
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