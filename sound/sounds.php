<?php 
	header("Content-type: application/json"); 
	if(strpos($_SERVER['HTTP_REFERER'], "breatheco") || strpos($_SERVER['HTTP_REFERER'], "4geeksacademy")){
		header("Access-Control-Allow-Origin: *");
	}		
?>{
	"fx" : [
		{ "id":1, "category":"game", "name":"Game Over", "url":"sounds/mario/fx_gameover.wav" },
		{ "id":2, "category":"game", "name":"Jump Super", "url":"sounds/mario/fx_jump_super.wav"},
		{ "id":3, "category":"game", "name":"Die", "url":"sounds/mario/fx_mariodie.wav"},
		{ "id":4, "category":"game", "name":"Pause", "url":"sounds/mario/fx_pause.wav"},
		{ "id":5, "category":"game", "name":"Stage Clear", "url":"sounds/mario/fx_stage_clear.wav"},
		{ "id":6, "category":"game", "name":"Warning", "url":"sounds/mario/fx_warning.wav"},
		{ "id":7, "category":"game", "name":"World Clear", "url":"sounds/mario/fx_world_clear.wav"},
		{ "id":8, "category":"game", "name":"Zelda Recorder", "url":"sounds/videogame/fx_recorder.wav"},
		{ "id":9, "category":"game", "name":"Duck-Duck-Duck Intro", "url":"sounds/other/fx_duck_intro.wav"},
		{ "id":10, "category":"other", "name":"Clock Ticking 1", "url":"sounds/other/clock-ticking1.wav" },
		{ "id":11, "category":"other", "name":"Clock Ticking 2", "url":"sounds/other/clock-ticking2.wav" }
	],
	"songs" : [
		{ "id":1, "category":"game", "name":"Mario Castle", "url":"sounds/mario/songs/castle.mp3" },
		{ "id":2, "category":"game", "name":"Mario Star", "url":"sounds/mario/songs/hurry-starman.mp3"},
		{ "id":3, "category":"game", "name":"Mario Overworld", "url":"sounds/mario/songs/overworld.mp3"},
		{ "id":4, "category":"game", "name":"Mario Stage 1", "url":"sounds/mario/songs/stage1.mp3"},
		{ "id":5, "category":"game", "name":"Mario Stage 2", "url":"sounds/mario/songs/stage2.mp3"},
		{ "id":6, "category":"game", "name":"Mario Star", "url":"sounds/mario/songs/starman.mp3"},
		{ "id":7, "category":"game", "name":"Mario Underworld", "url":"sounds/mario/songs/underworld.mp3"},
		{ "id":8, "category":"game", "name":"Mario Underwater", "url":"sounds/mario/songs/underwater.mp3"},
		{ "id":9, "category":"game", "name":"Zelda Castle", "url":"sounds/videogame/songs/zelda_castle.mp3"},
		{ "id":10, "category":"game", "name":"Zelda Outworld", "url":"sounds/videogame/songs/zelda_outworld.mp3"},
		{ "id":11, "category":"game", "name":"Zelda Titles", "url":"sounds/videogame/songs/zelda_title.mp3"},
		{ "id":11, "category":"game", "name":"Sonic Brain Zone", "url":"sounds/videogame/songs/sonic_brain-zone.mp3"},
		{ "id":11, "category":"game", "name":"Zelda Link To Past", "url":"sounds/videogame/songs/zelda_link-to-past.mp3"},
		{ "id":12, "category":"game", "name":"Dong KinKong Main", "url":"sounds/other/songs/dkng-main.mp3"},
		{ "id":13, "category":"game", "name":"Dong KinKong Other", "url":"sounds/other/songs/dkng-other.mp3"},
		{ "id":14, "category":"game", "name":"mega-man", "url":"sounds/other/songs/mega-man.mp3"},
		{ "id":15, "game":"cartoon", "name":"Flintstones", "url":"sounds/cartoons/songs/flintstones.mp3"},
		{ "id":16, "game":"cartoon", "name":"power-rangers", "url":"sounds/cartoons/songs/power-rangers.mp3"},
		{ "id":17, "game":"cartoon", "name":"simpsons", "url":"sounds/cartoons/songs/simpsons.mp3"},
		{ "id":18, "game":"cartoon", "name":"south-park", "url":"sounds/cartoons/songs/south-park.mp3"},
		{ "id":19, "game":"cartoon", "name":"thundercats", "url":"sounds/cartoons/songs/thundercats.mp3"},
		{ "id":20, "game":"cartoon", "name":"x-men", "url":"sounds/cartoons/songs/x-men.mp3"}
	]
}