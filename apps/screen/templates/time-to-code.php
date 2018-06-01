<video controls loop autoplay>
  <source src="/apis/video/background-time_to_code" type="video/mp4">
  Your browser does not support the video tag.
</video>
<script type="text/javascript">
    window.addEventListener('keypress', function(e) {
        var video = document.querySelector('video')
        if (e.which == 32) {
            if (video.paused == true)
                video.play();
            else
                video.pause();
        }
    });
</script>