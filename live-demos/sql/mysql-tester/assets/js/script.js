var dbName = 'chat';

$(document).ready(function(){
	$('#terminal').terminal(
		function(command) {
	        if (command !== '') {
	           	sendToPHP(command);
	        } else {
	        }
    	}, {
	        greetings: 'Type your SQL commands...',
	        name: 'js_demo',
	        height: 150,
	        prompt: 'breathecode> '
    	});

	$.terminal.defaults.formatters.push(function(string) {
	    return string.split(/((?:\s|&nbsp;)+)/).map(function(string) {
	        if (keywords.indexOf(string) != -1) {
	            return '[[b;orange;]' + string.toUpperCase() + ']';
	        } else {
	            return string;
	        }
	    }).join('');
	});

    loadBD();
});

// mysql keywords
var uppercase = [
    'ACCESSIBLE', 'ADD', 'ALL', 'ALTER', 'ANALYZE', 'AND', 'AS', 'ASC',
    'ASENSITIVE', 'BEFORE', 'BETWEEN', 'BIGINT', 'BINARY', 'BLOB',
    'BOTH', 'BY', 'CALL', 'CASCADE', 'CASE', 'CHANGE', 'CHAR',
    'CHARACTER', 'CHECK', 'COLLATE', 'COLUMN', 'CONDITION',
    'CONSTRAINT', 'CONTINUE', 'CONVERT', 'CREATE', 'CROSS',
    'CURRENT_DATE', 'CURRENT_TIME', 'CURRENT_TIMESTAMP', 'CURRENT_USER',
    'CURSOR', 'DATABASE', 'DATABASES', 'DAY_HOUR', 'DAY_MICROSECOND',
    'DAY_MINUTE', 'DAY_SECOND', 'DEC', 'DECIMAL', 'DECLARE', 'DEFAULT',
    'DELAYED', 'DELETE', 'DESC', 'DESCRIBE', 'DETERMINISTIC',
    'DISTINCT', 'DISTINCTROW', 'DIV', 'DOUBLE', 'DROP', 'DUAL', 'EACH',
    'ELSE', 'ELSEIF', 'ENCLOSED', 'ESCAPED', 'EXISTS', 'EXIT',
    'EXPLAIN', 'FALSE', 'FETCH', 'FLOAT', 'FLOAT4', 'FLOAT8', 'FOR',
    'FORCE', 'FOREIGN', 'FROM', 'FULLTEXT', 'GRANT', 'GROUP', 'HAVING',
    'HIGH_PRIORITY', 'HOUR_MICROSECOND', 'HOUR_MINUTE', 'HOUR_SECOND',
    'IF', 'IGNORE', 'IN', 'INDEX', 'INFILE', 'INNER', 'INOUT',
    'INSENSITIVE', 'INSERT', 'INT', 'INT1', 'INT2', 'INT3', 'INT4',
    'INT8', 'INTEGER', 'INTERVAL', 'INTO', 'IS', 'ITERATE', 'JOIN',
    'KEY', 'KEYS', 'KILL', 'LEADING', 'LEAVE', 'LEFT', 'LIKE', 'LIMIT',
    'LINEAR', 'LINES', 'LOAD', 'LOCALTIME', 'LOCALTIMESTAMP', 'LOCK',
    'LONG', 'LONGBLOB', 'LONGTEXT', 'LOOP', 'LOW_PRIORITY',
    'MASTER_SSL_VERIFY_SERVER_CERT', 'MATCH', 'MEDIUMBLOB', 'MEDIUMINT',
    'MEDIUMTEXT', 'MIDDLEINT', 'MINUTE_MICROSECOND', 'MINUTE_SECOND',
    'MOD', 'MODIFIES', 'NATURAL', 'NOT', 'NO_WRITE_TO_BINLOG', 'NULL',
    'NUMERIC', 'ON', 'OPTIMIZE', 'OPTION', 'OPTIONALLY', 'OR', 'ORDER',
    'OUT', 'OUTER', 'OUTFILE', 'PRECISION', 'PRIMARY', 'PROCEDURE',
    'PURGE', 'RANGE', 'READ', 'READS', 'READ_WRITE', 'REAL',
    'REFERENCES', 'REGEXP', 'RELEASE', 'RENAME', 'REPEAT', 'REPLACE',
    'REQUIRE', 'RESTRICT', 'RETURN', 'REVOKE', 'RIGHT', 'RLIKE',
    'SCHEMA', 'SCHEMAS', 'SECOND_MICROSECOND', 'SELECT', 'SENSITIVE',
    'SEPARATOR', 'SET', 'SHOW', 'SMALLINT', 'SPATIAL', 'SPECIFIC',
    'SQL', 'SQLEXCEPTION', 'SQLSTATE', 'SQLWARNING', 'SQL_BIG_RESULT',
    'SQL_CALC_FOUND_ROWS', 'SQL_SMALL_RESULT', 'SSL', 'STARTING',
    'STRAIGHT_JOIN', 'TABLE', 'TERMINATED', 'THEN', 'TINYBLOB',
    'TINYINT', 'TINYTEXT', 'TO', 'TRAILING', 'TRIGGER', 'TRUE', 'UNDO',
    'UNION', 'UNIQUE', 'UNLOCK', 'UNSIGNED', 'UPDATE', 'USAGE', 'USE',
    'USING', 'UTC_DATE', 'UTC_TIME', 'UTC_TIMESTAMP', 'VALUES',
    'VARBINARY', 'VARCHAR', 'VARCHARACTER', 'VARYING', 'WHEN', 'WHERE',
    'WHILE', 'WITH', 'WRITE', 'XOR', 'YEAR_MONTH', 'ZEROFILL'];
var keywords = uppercase.concat(uppercase.map(function(keyword) {
    return keyword.toLowerCase();
}));

function sendToPHP(value){
	$.ajax({
		url: "query.php",
		cache: false,
		dataType: 'json',
		data: { 
            sql: encodeURIComponent(value),
            db: dbName,
            tablestyles: getParameterByName('tablestyle'),
            debug: getParameterByName('debug')
        },
		success: function(data){

			if(data)
			{
                $('#preview').removeClass('success');
                $('#preview').removeClass('error');
                var appendContent = "";
                if(data.code==200){
                    appendContent = "&nbsp;Results... <br/>";
                    $('#preview').addClass('success');
                }
                if(data.code==400) $('#preview').addClass('error');
                $('#preview').html(appendContent+data.output);
			}

		},
		error: function(p1,p2,error)
		{
			alert(error);
		}
	});
}

function getParameterByName(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function loadBD(){
    var dbNameFromURL = getParameterByName('db');
    if(dbNameFromURL || dbNameFromURL!='')
    {
        var imgURL = 'assets/db/'+dbNameFromURL+'.png';
        if(imageExists(imgURL))
        {
            dbName = dbNameFromURL;//re-set the global dbName var
            $('#diagram').html('<i class="fa fa-database fa-1" aria-hidden="true"></i> <a target="_blank" href="'+imgURL+'">Review DB Diagram</a>');
        }
        else
        {
            imgURL = 'assets/db/'+dbName+'.png';
            $('#diagram').html('<i class="fa fa-database fa-1" aria-hidden="true"></i> <a target="_blank" href="'+imgURL+'">Review DB Diagram</a>');
        }
    }
    else
    {
        imgURL = 'assets/db/'+dbName+'.png';
        $('#diagram').html('<i class="fa fa-database fa-1" aria-hidden="true"></i> <a target="_blank" href="'+imgURL+'">Review DB Diagram</a>');
    }
}

function imageExists(image_url){

    var http = new XMLHttpRequest();

    http.open('HEAD', image_url, false);
    http.send();

    return http.status != 404;

}