<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
  <?php if(isset($_GET['program'])){ ?>
  <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap3.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <?php } else { ?>
  <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap4.min.css">
  <?php } ?>
</head>
<body data-spy="scroll" data-target="#navbar-example">
<?php if(isset($_GET['program'])){ ?>
  <div class="jumbotron bg-image" id="cover">
    <div class="container">
      <h1 class='syllabus-title'>Jumbotron Auto Viewport Height</h1>
      <p class="syllabus-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sequi pariatur laboriosam explicabo recusandae.</p>
      <p><a class="btn btn-primary btn-lg" href="#" onClick="$('html, body').animate({scrollTop: $('#section1').offset().top+10}, 1000);" role="button">Ver Syllabus</a></p>
    </div>
  </div>
  <div class="syllabus-wrapper" >
    <nav class="nav__wrapper" id="navbar-example">
      <ul class="nav">
        <li><h3 class="syllabus-title"></h3></li>
      </ul>
    </nav>
    <section class="section section1" id="section1">
    </section>
    <section class="section section2" id="section2">
      Section 2
    </section>
    <section class="section section3" id="section3">
      Section 3
    </section>
    <section class="section section4" id="section4">
      Section 4
    </section>
    <section class="section section5" id="section5">
      Section 5
    </section>
    <section class="section section6" id="section6">
      Section 6
    </section>
    <section class="section section7" id="section7">
      Scroll down or use the nav.
    </section>
    <section class="section section8" id="section8">
      Section 8
    </section>
    <section class="section section9" id="section9">
      Section 9
    </section>
    <section class="section section10" id="section10">
      Section 10
    </section>
    <section class="section section11" id="section11">
      Section 11
    </section>
    <section class="section section12" id="section12">
      Section 12
    </section>
    <section class="section section13" id="section13">
      Section 13
    </section>
    <section class="section section14" id="section14">
      Section 14
    </section>
    <section class="section section15" id="section15">
      Section 15
    </section>
    <section class="section section16" id="section16">
      Section 16
    </section>
  </div>
  <script type="text/javascript" src="assets/js/jquery.js"></script>
  <script type="text/javascript" src="../../assets/js/bootstrap3.min.js"></script>
  <script type="text/javascript" src="assets/js/timeline.js"></script>
  <script type="text/javascript" src="assets/js/script.js"></script>
<?php } else { ?>
  <div class="container text-center pd-5">
    <img class='mt-5' alt="breathe code logo" src="http://assets.breatheco.de/img/images.php?blob&random&cat=icon&tags=breathecode,128">
    <h1>Choose a Syllabus</h1>
    <select id="select-location" class="form-control">
      <option value='-1'>Select a syllabus</option>
      <option value='venezuela'>Venezuela</option>
      <option value='web-development'>Web Development in WordPress</option>
      <option value='full-stack-development'>Full Stack Development</option>
    </select>
  </div>
  <script type="text/javascript">
    window.onload = function(){
      document.querySelector('#select-location').addEventListener('change',function(evt){
         window.location.href = window.location.href +"?program="+evt.target.value; 
      });
    }
  </script>
<?php } ?>
</body>
</html>