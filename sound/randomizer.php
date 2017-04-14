<?php 
	header("Content-type: application/json"); 
	if(strpos($_SERVER['HTTP_REFERER'], "breatheco") || strpos($_SERVER['HTTP_REFERER'], "4geeksacademy")){
		header("Access-Control-Allow-Origin: *");
	}		
?>{
	"fx" : [
		{ "id":1, "category":"game", "name":"Game Over", "url":"mario/fx_gameover.wav" },
		{ "id":2, "category":"game", "name":"Jump Super", "url":"mario/fx_jump_super.wav"},
		{ "id":3, "category":"game", "name":"Die", "url":"mario/fx_mariodie.wav"},
		{ "id":4, "category":"game", "name":"Pause", "url":"mario/fx_pause.wav"},
		{ "id":5, "category":"game", "name":"Stage Clear", "url":"mario/fx_stage_clear.wav"},
		{ "id":6, "category":"game", "name":"Warning", "url":"mario/fx_warning.wav"},
		{ "id":7, "category":"game", "name":"World Clear", "url":"mario/fx_world_clear.wav"},
		{ "id":8, "category":"game", "name":"Zelda Recorder", "url":"zelda/fx_recorder.wav"},
		{ "id":9, "category":"game", "name":"Duck-Duck-Duck Intro", "url":"other/fx_duck_intro.wav"},
		{ "id":10, "category":"other", "name":"Clock Ticking 1", "url":"other/clock-ticking1.wav" },
		{ "id":11, "category":"other", "name":"Clock Ticking 2", "url":"other/clock-ticking2.wav" }
	],
	"songs" : [
		{ "id":1, "category":"game", "name":"Mario Castle", "url":"mario/songs/castle.mp3" },
		{ "id":2, "category":"game", "name":"Mario Star", "url":"mario/songs/hurry-starman.mp3"},
		{ "id":3, "category":"game", "name":"Mario Overworld", "url":"mario/songs/overworld.mp3"},
		{ "id":4, "category":"game", "name":"Mario Stage 1", "url":"mario/songs/stage1.mp3"},
		{ "id":5, "category":"game", "name":"Mario Stage 2", "url":"mario/songs/stage2.mp3"},
		{ "id":6, "category":"game", "name":"Mario Star", "url":"mario/songs/starman.mp3"},
		{ "id":7, "category":"game", "name":"Mario Underworld", "url":"mario/songs/underworld.mp3"},
		{ "id":8, "category":"game", "name":"Mario Underwater", "url":"mario/songs/underwater.mp3"},
		{ "id":9, "category":"game", "name":"Zelda Castle", "url":"zelda/songs/castle.mp3"},
		{ "id":10, "category":"game", "name":"Zelda Outworld", "url":"zelda/songs/outworld.mp3"},
		{ "id":11, "category":"game", "name":"Zelda Titles", "url":"zelda/songs/title.mp3"},
		{ "id":12, "category":"game", "name":"Dong KinKong Main", "url":"other/songs/dkng-main.mp3"},
		{ "id":13, "category":"game", "name":"Dong KinKong Other", "url":"other/songs/dkng-other.mp3"},
		{ "id":14, "category":"game", "name":"mega-man", "url":"other/songs/mega-man.mp3"},
		{ "id":15, "game":"cartoon", "name":"Flintstones", "url":"cartoons/songs/flintstones.mp3"},
		{ "id":16, "game":"cartoon", "name":"power-rangers", "url":"cartoons/songs/power-rangers.mp3"},
		{ "id":17, "game":"cartoon", "name":"simpsons", "url":"cartoons/songs/simpsons.mp3"},
		{ "id":18, "game":"cartoon", "name":"south-park", "url":"cartoons/songs/south-park.mp3"},
		{ "id":19, "game":"cartoon", "name":"thundercats", "url":"cartoons/songs/thundercats.mp3"},
		{ "id":20, "game":"cartoon", "name":"x-men", "url":"cartoons/songs/x-men.mp3"}
	]
}