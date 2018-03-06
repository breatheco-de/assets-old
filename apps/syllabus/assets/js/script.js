// SMOOTH SCROLLING SECTIONS
$(document).ready(function(){

    // Cache selectors
    var lastId,
        topMenu = $("nav.nav__wrapper"),
        topMenuHeight = topMenu.outerHeight(),
        // All list items
        menuItems = topMenu.find("a"),
        queryString = getUrlVars(),
        // Anchors corresponding to menu items
        scrollItems = menuItems.map(function(){
          var item = $($(this).attr("href"));
          if (item.length) { return item; }
        });

    // Bind click handler to menu items
    // so we can get a fancy scroll animation
    menuItems.click(function(e){
      var href = $(this).attr("href");
      console.log(href+", offset: "+$(href).offset().top);
      var offsetTop = (href === "#") ? 0 : $(href).offset().top+1;
      $('html, body').stop().animate({ 
          scrollTop: offsetTop
      }, 300);
      e.preventDefault();
    });

    // Bind to scroll
    $(window).scroll(function(){
        
       // Get container scroll position
       var fromTop = $(this).scrollTop()+topMenuHeight;
       
       // Get id of current scroll item
       var cur = scrollItems.map(function(){
         if ($(this).offset().top < fromTop)
           return this;
       });
       // Get the id of the current element
       cur = cur[cur.length-2];
       var id = cur && cur.length ? cur[0].id : "";
       
       if (lastId !== id) {
           lastId = id;
           // Set/remove active class
           menuItems
             .parent().removeClass("active")
             .end().filter("[href='#"+lastId+"']").parent().addClass("active");
       }                   
    });
    
    if(typeof(queryString['program']) =='undefined') alert('Please Specify a Syllabus on the QueryString ?program=<slug>');
    else
    {
        $.get('data/'+queryString['program']+'.json').done(function(data){
            console.log('AJAX Incoming');
            Timeline.init({
                containerSelector: 'section',
                menuContainerSelector: '.nav__wrapper .nav',
                data: data.weeks
            });
            
            $('.syllabus-title').html(data.label);
            
            let allTechnologies = flatten(data.weeks.map(function(week){
                let weekTechnologies = [];
                let dayTechnologies = week.days.map(function(day){ return day.technologies; });
                dayTechnologies = dayTechnologies.filter(function(item){return typeof(item)!='undefined';});
                return weekTechnologies.concat(dayTechnologies);
            }));
            
            var uniqueTechnologies = [];
            allTechnologies.forEach(function(tech, index){
                if(uniqueTechnologies.indexOf(tech) === -1) uniqueTechnologies.push(tech);
            });
            document.querySelector('#syllabus-technologies').innerHTML = "<h2>"+uniqueTechnologies.map(function(tech){ return wrap(tech); }) + "</h2>";
            $('.syllabus-description').html(data.description || "No Description for this syllabus");
            
            topMenu = $("nav.nav__wrapper");
            topMenuHeight = topMenu.outerHeight();
            // All list items
            menuItems = topMenu.find("a");
            // Anchors corresponding to menu items
            scrollItems = menuItems.map(function(){
              var item = $($(this).attr("href"));
              if (item.length) { return item; }
            });
            
        }).fail(function(msg){
            console.log('Error!!', msg);      
        });
    }

});

const wrap = function(value){
    return '<span class="badge badge-secondary">'+value+'</span>';
}
const flatten = function(arr, result = []) {
  for (let i = 0, length = arr.length; i < length; i++) {
    const value = arr[i];
    if (Array.isArray(value)) {
      flatten(value, result);
    } else {
      result.push(value);
    }
  }
  return result;
};


function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function getUrlVars()
{
    var vars = [], hash;
    var currentURL = window.location.href.split('#')[0];
    var hashes = currentURL.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}