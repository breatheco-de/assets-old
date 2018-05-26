<?php
use Google\Cloud\Datastore\DatastoreClient;

class BreatheCodeLogger{
    
    static function logActivity($student, $activity){
        $datastore = new DatastoreClient([ 
            'projectId' => 'breathecode-197918',
            'keyFilePath' => '../../breathecode-efde1976e6d3.json'
        ]);
        
        $activity = array_merge($activity, [
            'created_at' => new DateTime(),
            'email' => $student
        ]);
        
        $npsAnswer = $datastore->entity('student_activity', $activity);
        $datastore->insert($npsAnswer);
    }
    
}