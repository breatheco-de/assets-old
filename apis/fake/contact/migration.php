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
      CREATE TABLE IF NOT EXISTS fake_contact_list(
      id                      INTEGER PRIMARY KEY NOT NULL,
      agenda_slug             TEXT     NOT NULL,
      full_name               TEXT     NULL,
      email                   TEXT     NULL,
      phone                   TEXT     NULL,
      address                 TEXT     NULL,
      created_at              datetime default current_timestamp);
EOF;

   $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "Table created successfully\n";
   }
   $db->close();