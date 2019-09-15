<?php

namespace BreatheCode\Activity;

class ErrorActivity extends BCActivity{

    var $type = null;
    var $slug = "coding_error";
    var $possibleTypes = [
        //errors made during compilation time, normally when building projects
        "compilation_error" => [
            "track_on_active_campaign" => false,
            "track_on_log" => true
        ],
        //errors made during testing of a small exercise like a replit
        "exercise_error" => [
            "track_on_active_campaign" => false,
            "track_on_log" => true
        ],
    ];

    public function filter($query, $filters){
        if(!empty($filters["slug"])) $query = $query->filter('slug', '=', $filters["slug"]);
        if(!empty($filters["language"])) $query = $query->filter('language', '=', $filters["language"]);
        if(!empty($filters["user_agent"])) $query = $query->filter('language', '=', $filters["user_agent"]);
        if(!empty($filters["builder"])) $query = $query->filter('language', '=', $filters["builder"]);
        if(!empty($filters["framework"])) $query = $query->filter('framework', '=', $filters["framework"]);
        if(!empty($filters["cohort"])) $query = $query->filter('cohort', '=', $filters["cohort"]);
        if(!empty($filters["day"])) $query = $query->filter('day', '=', $filters["day"]);
        if(!empty($filters["user_id"])){
            $query = $query->filter('user_id', '=', $filters["user_id"]);
        }
        return $query;
    }

    public function decode($data){
        return [
            "user_id" => $ans->user_id,
            "slug" => $ans->slug,
            "data" => (empty($data["data"])) ? 'No additional data' : $data["data"],
            "message" => $ans->message,
            "name" => $ans->name,
            "language" => $ans->language,
            "framework" => $ans->framework,
            "user_agent" => $ans->user_agent,
            "builder" => $ans->builder,
            "severity" => $ans->severity,
            "created_at" => $data->created_at,
            "details" => $ans->error,
            "cohort" => $ans->cohort,
            "day" => $ans->day,
        ];
    }

    public function encode($student, $data){
        return [
            'created_at' => new \DateTime(),
            'slug' => $data["slug"],
            'data' => (empty($data["data"])) ? 'No additional data' : $data["data"],
            'user_id' =>  $student,
            'message' => $data["message"],
            'name' => $data["name"],
            'user_agent' => (empty($data["user_agent"])) ? null : $data["user_agent"],
            'language' => (empty($data["language"])) ? null : $data["language"],
            'framework' => (empty($data["framework"])) ? null : $data["framework"],
            'builder' => (empty($data["builder"])) ? null : $data["builder"],
            'cohort' => (empty($data["cohort"])) ? null : $data["cohort"],
            'day' => (empty($data["day"])) ? null : $data["day"],
            'severity' => $data["severity"],
            'details' => $data["details"],
        ];
    }

}