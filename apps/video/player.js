(function(videotutorial,$,undefined) {

  var player,
  	  videoStringId,
  	  menuTitleValue,
  	  done = false,
      settings = {};

  videotutorial.initialize = function(theSettings){

    defaults(theSettings);
    loadInfoJSON(settings["project-slug"]);
  }

  function initializePlayer(){
    if (typeof(YT) == 'undefined' || typeof(YT.Player) == 'undefined') {
      window.onYouTubeIframeAPIReady = function() {
        loadPlayer(settings["selector"], videoStringId);
      };

      $.getScript('https://www.youtube.com/iframe_api');
    }
  }

  function loadPlayer() {
      console.log("Selector: ", settings["selector"]);
    player = new YT.Player(settings["selector"], {
		height: '100%',
		width: '100%',
		videoId: videoStringId,
		events: {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		},
		playerVars: {
          controls: 1,
          modestbranding: 1,
          rel: 0,
          showinfo: 0
        }
    });
  }

  // 4. The API will call this function when the video player is ready.
  function onPlayerReady(event) {
	   if(settings['autoplay']) player.playVideo();
  }

  // 5. The API calls this function when the player's state changes.
  //    The function indicates that when playing a video (state=1),
  //    the player should play for six seconds and then stop.
  function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PLAYING && !done) {
      done = true;
    }
  }

  videotutorial.jumpTo = function(seconds) {
	   player.seekTo(seconds, true);
  }

  function defaults(theSettings)
  {
    var _settings = {
    	'selector': 'player',
    	'autoplay': false,
    	'menu-selector': 'menu-items',
    	'video-url': '',
    	'menu-title': 'Menu'
    };

    var objAssign = function(a,b){
        var result = {};
        for( var key in b) result[key] = a[key];
        for( var key in b) result[key] = b[key];
        return result;
    }
    settings = objAssign(_settings , theSettings);

    menuTitleValue = $('#'+settings['menu-title']).html();
    $('#'+settings['menu-title']).html('Loading');

    return settings;
  }

  function renderTimeline(timeline){

  	var cont = 1;
  	timeline.forEach(function(elm){
  		$('#'+settings['menu-selector']).append(renderMenuItem(cont,elm.seconds,elm.description));
  		cont++;
  	});

  	addMenuListeners();
  }

  function renderMenuItem(index, seconds, description){
  	return '<li><i>'+displayTime(seconds)+' seg</i> <a class="dr-icon player-topic" data-seconds="'+seconds+'" dr-icon-number" href="#">'+description+'</a></li>';
  }

  function displayTime(seconds) {
    if(typeof seconds != 'string' || seconds.indexOf(':') != -1) return seconds;

    var hh = Math.floor(seconds / 3600);
    var mm = Math.floor((seconds % 3600) / 60);
    var ss = seconds % 60;

    let timeStr = '';
    if(hh==0) timeStr = pad(mm,2) + ":" + pad(ss,2);
    else timeStr = pad(hh,2) + ":" + pad(mm,2) + ":" + pad(ss,2);
    return timeStr;
  }

  function pad(n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
  }

  function addMenuListeners(){
  	$('.player-topic').click(function(){
  	  let seconds = $(this).data('seconds');
  	  if(typeof seconds == 'string' && seconds.indexOf(':') != -1){
  	    const timeArr = seconds.split(':').reverse();
  	    seconds = parseInt(timeArr[0]);
  	    if(typeof timeArr[1] != 'undefined') seconds += timeArr[1] * 60;
  	    if(typeof timeArr[2] != 'undefined') seconds += timeArr[2] * 3600;
  	  }
  		videotutorial.jumpTo(seconds);
  	});

  	$('.dr-menu').click(function(){
  	  $(this).toggleClass('dr-menu-open');
  	});
  }

  function loadInfoJSON(projectSlug){
      $.ajax({
        url: 'https://assets.breatheco.de/apis/project/'+projectSlug,
        cache: false,
        dataType: 'json',
        success: function(data){
          if(!data['video-id'] || data['video-id']==='')
          {
            alert('No video-id found');
          }
          else
          {
          	$('#'+settings['menu-title']).html(menuTitleValue);
          	if(data.timeline && data.timeline.length>0) renderTimeline(data.timeline);
            else $('#'+settings['menu']).hide();

          	videoStringId = data['video-id'];

          	if(data.menuname) $('#'+settings['menu-title']).html(data.menuname);
            initializePlayer();
          }
        },
        error: function(p1, p2,errorString){
          alert("Error loading the project video tutorial "+errorString);
        }
      });
	}
})( window.videotutorial = window.videotutorial || {}, jQuery );
