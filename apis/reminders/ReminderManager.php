<?php
use BreatheCode\BCWrapper as BC;
use BreatheCode\SlackWrapper;
use GuzzleHttp\Client;
use MomentPHP\MomentPHP as Moment;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;

class ReminderManager{
    
    static $api;
    static $path = './_reminders';
    static $executionInfo = null;
    static $dateFormat = "Y-m-d";
    static $db = null;
    
    public static function init($basePath=''){
        self::$db = new JsonPDO($basePath.'_data/','{}',false);
        self::$path = $basePath.self::$path;
        self::$executionInfo = self::$db->getJsonByName('execution_info');
    }

    public static function getReminders(){
        $reminders = self::getRawReminders();
        return array_filter($reminders, function($file){
            try{
                self::validate($file);
                return true;
            }
            catch(Exception $e){
                return false;
            }
        });
    }

    public static function getRawReminders(){
        $files = self::_getDirectoryTree(self::$path,'php'); 
        $reminders = [];
        foreach($files as $file) $reminders[] = self::_getMetadata($file);
        return $reminders;
    }
    
    public static function getPending(){
        $pending = [];
        foreach(self::$executionInfo as $name => $info){
            $metadata = self::_getMetadata($name);
            $lastExecution = new Moment($info->last_execution, self::$dateFormat);
            $nextExecution = new Moment($info->last_execution, self::$dateFormat);
            
            if($freq = self::_parseFrequency($metadata["frequency"])){
                $nextExecution->add(intval($freq[0]), $freq[1]);
                if($nextExecution->isBefore(new Moment(new DateTime()))){
                    $metadata["last_execution"] = $lastExecution->fromNow();
                    $metadata["next_execution"] = $nextExecution->fromNow();
                    $metadata["next_execution_timestamp"] = $nextExecution->timestamp();
                    $metadata["expired"] = true;
                    $pending[] = $metadata;
                }
            }
        }
        usort($pending, function($a, $b){
            return $a["next_execution_timestamp"] > $b["next_execution_timestamp"];
        });
        
        return $pending;
    }
    
    public static function execute($name, $silent=true){
        $metadata = self::_getMetadata($name);
        include(self::$path.'/'.$name);
        try{
            if(!is_callable($metadata["function"])) throw new Exception('The function "'.$metadata["function"].'" does not seem to be callable');
            else{
                call_user_func_array($metadata["function"], []);
                if(isset(self::$executionInfo[$name])) self::$executionInfo[$name]->last_execution = date(self::$dateFormat);
                else self::$executionInfo[$name] = [
                    "last_execution" => date(self::$dateFormat)
                ];
                self::$db->toFile('execution_info')->save(self::$executionInfo);
                return true;
            } 
        }
        catch(Exception $e){
            $errors = self::$db->getJsonByName('errors');
            $errors[] = [
                "reminder" => $name,
                "date" => new DateTime(),
                "error" => $e->getMessage()
            ];
            self::$db->toFile('errors')->save($errors);
            throw $e;
        }
    }
    
    private static function _parseFrequency($frequency){
        if(preg_match_all('/(\d{1,3}) (seconds|day|month|year){1}/m',$frequency,$matches)){
            if(count($matches[0]) == 0) return false;
            return [$matches[1][0], $matches[2][0]];
        }
        else return false;
    }
    
    public static function validate($reminder){
        if(empty($reminder['title'])) throw new Exception('Missing title');
        if(empty($reminder['name'])) throw new Exception('Missing name');
        if(empty($reminder['frequency'])) throw new Exception('Missing frequency');
        if($reminder['function'] != str_replace(".php","",$reminder['name'])) throw new Exception('The file must contain a function with the same name that represents the beginning of the execution');
        else if(!self::_parseFrequency($reminder['frequency'])) throw new Exception('Invalid frequency: '.$reminder['frequency']);
        
        return true;
    }
    
    private static function _getMetadata($filename)
    {
        $metadata = [
            "name" => $filename
        ];
    
        $fileContents = file_get_contents(self::$path.'/'.$filename);
        
        //get comments
        $docComments = array_filter(token_get_all($fileContents), function($entry)
        {
            return $entry[0] == T_COMMENT;
        });
        $fileDocComment = array_shift($docComments);
        preg_match_all("/\@(.*)\:(.*)/", $fileDocComment[1], $matches);
        for($i = 0; $i < sizeof($matches[0]); $i++)
            $metadata[$matches[1][$i]] = trim($matches[2][$i]);
            
        //get function name
        if(preg_match_all("/function (.*)\(/", $fileContents, $matches)){
            $metadata["function"] = $matches[1][0];
        }else $metadata["function"] = null;
    
        return $metadata;
    }
    
    private static function _getDirectoryTree( $outerDir , $x = null){ 
        $dirs = array_diff( scandir( $outerDir ), [".", ".."] ); 
        $dir_array = []; 
        foreach( $dirs as $d ){ 
            if( is_dir($outerDir."/".$d)  ){ 
                $dir_array[ $d ] = self::_getDirectoryTree( $outerDir."/".$d , $x); 
            }else{ 
                if (!$x or preg_match('/'.$x.'$/',$d)) $dir_array[ $d ] = $d; 
            } 
        } 
        return $dir_array; 
    } 
    
}

function emailReminder($to,$subject=null,$message=null){
    
    if(!$subject or !$message) throw new Exception('Missing subject or message for email reminder');
    
    $msgHeader ="------------- $subject ------------------\n\n";
    $message = $msgHeader . $message;
    
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
                // 'Html' => [
                //     'Charset' => 'UTF-8',
                //     'Data' => $message,
                // ],
    			'Text' => [
                    'Charset' => 'UTF-8',
                    'Data' => $message,
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