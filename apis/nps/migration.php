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
      CREATE TABLE IF NOT EXISTS response
      (ID INT PRIMARY KEY     NOT NULL,
      cohort_slug           TEXT    NOT NULL,
      comment           TEXT    NOT NULL,
      score            INT     NOT NULL,
      user_id            INT     NOT NULL,
      created_at         datetime default current_timestamp);
EOF;

   $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "Table created successfully\n";
   }
   $db->close();