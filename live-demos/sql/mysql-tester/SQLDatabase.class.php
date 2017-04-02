<?php
require_once('config.php');
class SQLDatabase{

	/**
	 * Administrator for the database
	 * @var [Object]
	 */
	private $link;
	/**
	 * Only for selecting the datbase
	 * @var [Object]
	 */
	private $softLink;

	/**
	 * Show trace of activities
	 * @var [boolean]
	 */
	private $debug;

	/**
	 * To append to recently created temporary tables
	 * @var [string]
	 */
	private $prefix;

	/**
	 * Tables that can be CRUD
	 * @var array
	 */
	private $tableNames;

	/**
	 * The response sent to the end-user
	 * @var [type]
	 */
	private $outputHTML;
	/**
	 * For debugging purposes
	 * @var array
	 */
	private $logs;
	
	/**
	 * HTML Style for the reponse tables
	 * @var string
	 */
	private $tableStyle;

	private $dbSample;


	function __construct($settings){

		$this->outputHTML = "";
		$this->tableNames = array();
		$this->logs = array();

		if(isset($settings) and isset($settings['debug'])) $this->debug = $settings['debug'];
		else $this->debug = false;

		if(isset($settings) and isset($settings['table-style'])) $this->tableStyle = $settings['table-style'];
		else $this->tableStyle = "hor-minimalist-b";

		if(isset($settings) and isset($settings['db'])){
			if($this->setDBName($settings['db']==false)) $this->dbSample = "chat";
		}
		else $this->dbSample = "chat";

		if(isset($settings) and isset($settings['prefix'])) $this->prefix = $settings['prefix'];

		$this->link = $this->connect(MYSQL_USER,MYSQL_PASSWORD);
		$this->link = $this->selectDB($this->link,true);//force to create the db if not found
		$this->close($this->link);
	}

	public function getLink(){ return $this->link; }
	public function getLogs(){ return $this->logs; }

	public function setDBName($value)
	{
		if(file_exists(CONFIG_URL_PATH.$dbSample.'.sql')) $this->dbSample = $value;
		else return false;
	}

	public function executeSQL($sqlString)
	{
		if(!$sqlString or $sqlString==="") throw new Exception("Empty query.", 1);
		if(substr($sqlString, -1)!==";") throw new Exception("Missing semicolon.", 1);
		
		$this->softLink = $this->connect(TESTING_USER,TESTING_PASSWORD);
		$this->softLink = $this->selectDB($this->softLink,false);

		$sqlString = str_replace(";","",$sqlString);
		$sqlString = strtoupper($sqlString);
		if($this->createTemporalTables())
		{
			$sqlString = $this->fakeTheQueryWithTemporal($sqlString);
			$cursor = mysqli_query($this->softLink, $sqlString);
			if($cursor)
			{
				if($this->isSelect($sqlString)) $this->fetchToHTML($cursor);
				else if($this->isShow($sqlString)) $this->fetchToHTML($cursor);
				else $this->output($this->getSuccessMessage());

				$this->close($this->softLink);

				return true;
			}
			else{
				throw new Exception("SQL Error: ".mysqli_error($this->softLink),1);
				return false;
			}
		}

	}

	private function fakeTheQueryWithTemporal($sql){

		foreach($this->tableNames as $tableName)
		{
			//'/\bHello\b/'
			$tableName = strtoupper($tableName);
			$tableNameRegex = '/\b'.$tableName.'\b/';
			$sql = preg_replace($tableNameRegex,$this->prefix.'_'.$tableName,$sql);
		}

		return $sql;
	}

	private function connect($user,$password)
	{
		// Connect to MySQL
		$link = mysqli_connect(MYSQL_SERVER, $user, $password);
		if (!$link) {
		    throw new Exception('Could not connect: ' . mysqli_error($link), 1);
		}

		return $link;
	}

	private function selectDB($link, $force)
	{
		// Make my_db the current database
		$db_selected = mysqli_select_db($link,MYSQL_DATABASE);
		if (!$db_selected and $force) {
			// If we couldn't, then it either doesn't exist, or we can't see it.
			$sql = 'CREATE DATABASE '.MYSQL_DATABASE;
			$cursor = mysqli_query($link, $sql);
			if ($cursor) {
				$this->logMessage("Database ".MYSQL_DATABASE." created successfully");

				$sqlConfig = file_get_contents(CONFIG_URL_PATH.$dbSample.'.sql');
				$cursor = mysqli_query($link,$sqlConfig);
				if (!$cursor) throw new Exception('Error creating database: ' . mysqli_error($link), 1);
				else $this->logMessage("DB Configuration loaded successfully");

			} else {
				throw new Exception('Error creating database: ' . mysqli_error($link), 1);
			}
		}
		else if(!$db_selected)
		{
			throw new Exception('could not select db',1);
		}
		//else if() reviso si tiene tablas para la bd que queremos probar

		return $link;
	}

	private function logMessage($msg){
		if($this->debug){
			$msg = preg_replace("/".$this->prefix.'_/',"",$msg);
			array_push($this->logs, $msg);
		}
	}

	private function fetchToArrayTable($link){
		$headersSet = false;
		$resultsHeaders = array();
		$resultsRows = array();
		while($row = mysqli_fetch_array($link)){
			$cont = 0;
			$resultCol = array();
			foreach($row as $key => $value){
				if(!is_integer($key) and !$headersSet) array_push($resultsHeaders,$key);
				if(!is_integer($key) and $value) array_push($resultCol,$value);
			}
			if(!$headersSet)
			{
				$headersSet = true;
				array_push($resultsRows,$resultsHeaders);
			}
			if(count($resultCol)>0) array_push($resultsRows,$resultCol);
		}
		return $resultsRows;
	}

	private function fetchToHTML($link)
	{	
		$headersSet = false;
		$arrayTable = $this->fetchToArrayTable($link);
		$this->output("<table id='".$this->tableStyle."'>");
		foreach($arrayTable as $row)
		{
			if(!$headersSet) $this->output("<thead> \n");
			
			if(count($row)>0){
				$this->output("<tr>");
				foreach($row as $col)
				{
					if($headersSet) $this->output("<td>".substr(trim($col),0,20)."</td>");
					else $this->output("<th>".substr(trim($col),0,20)."</th>");
				}
				$this->output("</tr> \n");
			}
			if(!$headersSet)
			{
				$this->output("</thead> \n");
				$this->output("<tbody> \n");
				$headersSet = true;
			}
		}
		if($headersSet) $this->output("</tbody> \n");

		if(count($arrayTable)==0) $this->output("<tr><td>No data.</td></tr>");
		$this->output("</table> \n");

		$this->logMessage("Query rendered as HTML");
		
	}

	private function isSelect($sql)
	{
		preg_match_all('/((FROM|JOIN) (.*))/',$sql,$matches);

		if(!$matches[3] or count($matches[3])==0) return false;
		else return true;
	}

	private function isShow($sql)
	{
		preg_match_all('/SHOW TABLES/',$sql,$matches);
		if(!$matches[0] or count($matches[0])==0) return false;
		else return true;
	}

	private function createTemporalTables(){

		$sql = "SELECT table_name FROM information_schema.tables where table_schema='".MYSQL_DATABASE."'";
		if($results = mysqli_query($this->softLink, $sql))
		{
			while($row = mysqli_fetch_array($results))
			{
				array_push($this->tableNames,$row['table_name']);
				$temporalTableName = $this->prefix."_".$row['table_name'];
				$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS $temporalTableName AS (SELECT * FROM ".$row['table_name'].")";
				$softLink = mysqli_query($this->softLink,$sql);
				if(!$softLink)
				{
					throw new Exception("Error creating temporary tables: ".mysqli_error($this->softLink),1);
					return false;
				}
			}
		}

		$this->logMessage("Temporary tables successfully created");
		return $this->softLink;
	}

	public function close($link)
	{
		mysqli_close($link);
	}

	private function output($htmlString)
	{
		$this->outputHTML .= $htmlString;
	}

	public function getHTML()
	{
		return $this->outputHTML;
	}

	private function getSuccessMessage()
	{
		$messages = array(
			"<h1>Perfect!!! Keep it going cowboy!</h1>"
			);

		return $messages[rand(0,count($messages)-1)];
	}

}