<?php

class Functions{
    private static $_combinations = [];
    public static function permute($array, $size=null) {
        // initialize by adding the empty set
        $results = array(array( ));
    
        foreach ($array as $element)
            foreach ($results as $combination)
                array_push($results, array_merge(array($element), $combination));
    
        if($size){
            //asort($perms);
	        //self::$_combinations[implode("",$perms)] = $perms;
	        $combinations = [];
            foreach($results as $comb){
                if (count($comb) == $size && in_array('4geeks', $comb))
                    $combinations[] = $comb;
            }
            return $combinations;
        } 
        return $results;
    }
}