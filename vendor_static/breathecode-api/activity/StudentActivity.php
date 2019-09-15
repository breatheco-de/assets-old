<?php

namespace BreatheCode\Activity;

class StudentActivity extends BCActivity{

    var $type = null;
    var $slug = "student_activity";
    var $possibleTypes = [
        "breathecode_login" => [
            "track_on_active_campaign" => true,
            "track_on_log" => true
        ],
        "online_platform_registration" => [
            "track_on_active_campaign" => true,
            "track_on_log" => true
        ],
        "public_event_attendance" => [
            "track_on_active_campaign" => true,
            "track_on_log" => true
        ],
        "classroom_attendance" => [
            "track_on_active_campaign" => false,
            "track_on_log" => true
        ],
        "classroom_unattendance" => [
            "track_on_active_campaign" => false,
            "track_on_log" => true
        ],
        //when a lessons is opened on the platform
        "lesson_opened" => [
            "track_on_active_campaign" => false,
            "track_on_log" => true
        ],
        //when the office raspberry pi detects the student
        "office_attendance" => [
            "track_on_active_campaign" => false,
            "track_on_log" => true
        ],
        //when a nps survey is answered by the student
        "nps_survey_answered" => [
            "track_on_active_campaign" => true,
            "track_on_log" => true
        ]
    ];

    public function filter($query, $filters){
        if(!empty($filters["slug"])) $query = $query->filter('slug', '=', $filters["slug"]);
        if(!empty($filters["cohort"])) $query = $query->filter('cohort', '=', $filters["cohort"]);
        if(!empty($filters["user_agent"])) $query = $query->filter('user_agent', '=', $filters["user_agent"]);
        if(!empty($filters["user_id"])){
            $query = $query->filter('user_id', '=', $filters["user_id"]);
        }
        if(!empty($filters["email"])) $query = $query->filter('email', '=', $filters["email"]);

        return $query;
    }

    public function decode($data){
        return [
            "user_id" => $data->user_id,
            "email" => $data->email,
            "data" => $data->data,
            "cohort"=>$data->cohort,
            "created_at" => $data->created_at,
            "user_agent" => $data->user_agent,
            "slug" => $data->slug,
        ];
    }

    public function encode($student, $data){
        $dataObj = isset($data['data']) ? json_decode($data['data'], true) : null;
        $activity = [
            'created_at' => new \DateTime(),
            'slug' => $data["slug"],
            'cohort' => isset($data["cohort"]) ? $data["cohort"] : null,
            'day' => isset($data["day"]) ? $data["day"] : null,
            'user_agent' => (empty($data["user_agent"])) ? null : $data["user_agent"],
            'data' => (empty($data["data"])) ? null : $data["data"]
        ];

        if(is_string($student)) $activity['email'] = $student;
        else{
            $student = (array) $student;
            if(!empty($student['id'])) $activity['user_id'] = (string) $student['id'];

            if(!empty($student['email'])) $activity['email'] = (string) $student['email'];
            else if(!empty($student['username'])) $activity['email'] = (string) $student['username'];
        }
        return $activity;
    }

}
