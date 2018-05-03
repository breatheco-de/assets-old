<?php

   class MyDB extends SQLite3 {
      function __construct() {
         $this->open('db.sqlite3');
      }
   }
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } else {
      echo "Opened database successfully\n";
   }

   $sql =<<<EOF
   CREATE TABLE 'event' (
      'description' TEXT NOT NULL, 
      'title' TEXT NOT NULL, 
      'url' TEXT NOT NULL, 
      'capacity' INTEGER NOT NULL, 
      'logo_url' TEXT NOT NULL, 
      'invite_only' INTEGER NOT NULL, 
      'created_at' DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
      'id'  INTEGER PRIMARY KEY NOT NULL  
   );
EOF;

   $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "Table created successfully\n";
   }
   $db->close();