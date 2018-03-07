var Timeline = (function(){
    var pub = {};
    var settings = {
        data: [
            { label: "Week 1", days:[] },
            { label: "Week 2", days:[] },
        ],
        containerSelector: '',
        menuContainerSelector: ''
    };
    
    pub.renderWeek = function(week){
        var content = '<div class="timeline-container">';
        content += `<h2 class="text-center">${week.label}</h2>`;
        week.days.forEach((day) => {
            content+=pub.renderDay(day);
        });
        content += '</div>';
        return content;
    }
    
    pub.init = function(incomingSettings){
        this.settings = $.extend(this.settings,incomingSettings);
        
        var sections = $(this.settings.containerSelector);
        var cont = 0;
        
        this.settings.data.forEach(function(week){
            $(sections[cont]).html(pub.renderWeek(week));
            var marginTop = 70;
            $(sections[cont]).find('.day').each(function(){
               $(this).css('top', marginTop);
               var topicsHeight = $(this).find('.day-topics').first().height();
               var projectsHeight = $(this).find('.day-projects').first().height();
               
               if(topicsHeight > projectsHeight){
                    $(this).height(topicsHeight+30);
                    marginTop += topicsHeight+15;
               } 
               else
               {
                    $(this).height(projectsHeight+30);
                    marginTop += projectsHeight+15;
               }
               
            });
            cont++;
        });
        
        $(this.settings.menuContainerSelector).append(pub.renderMenu(this.settings.data, this.settings.containerSelector));
        
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="popover"]').click(function(){
           return false; 
        });
        
    }
    
    pub.renderMenu = function(weeks, weekContainerId){
        var content = '';
        var cont = 1;
        weeks.forEach(function(week){
            content += `<li role="presentation"><a href="#${weekContainerId+cont.toString()}">
            <span class="nav__counter">${week.label}</span>
            <h3 class="nav__title">${week.topic}</h3>
            <p class="nav__body">${(typeof week.summary != 'undefined') ? week.summary:''}</p>
          </a></li>`;
          cont++;
        });
        return content;
    }
    
    pub.renderDay = function(day){
        
        var theProject = '';
        if(typeof(day.project)=='object')
        {
            if(typeof(day.project.url) != 'undefined') theProject = `<a target="_blank" href="${day.project.url}">${day.project.title}</a>`;
            else theProject = day.project.title;
        }

        var theKeyConcepts = '';
        if(typeof(day['key-concepts'])!='undefined'){
            var popoverContent = '<ul>'; 
            day['key-concepts'].forEach((concept) => {
                popoverContent += `<li>- ${concept}</li>`;
            });
            popoverContent += '</ul>';
            theKeyConcepts = `<p><a href="#" data-html="true" data-container="body" data-toggle="popover" title="Key Concepts" data-placement="top" data-content="${popoverContent}">Click for Key Concepts</a></p>`;
        } 
        
        return `<div class="day ${(day.label.toLowerCase() == 'weekend') ? 'weekend' : ''}">
          <h3 class="text-center">${day.label}</h3>
          <div class="day-topics">
            ${theKeyConcepts}
            ${day.description || 'No description for this particular day'}
          </div>
          <div class="day-projects">
            <ul>
              <li><strong>Project:</strong> ${theProject || 'Work on previous projects'}</li>
              ${pub.getHomeworkHTML(day)}
              <p class="text-right">${pub.getReplitsHTML(day)} - ${pub.getBreatheCodeHTML(day)}</p>
            </ul>
          </div>
        </div>`;
    }
    
    pub.getHomeworkHTML = function(day){
        var theHomeWork = '';
        if(typeof(day.homework)!='undefined'){
            var specialNote =  '';
            if(typeof(day.homework) === 'string') 
            {
                theHomeWork = `<li><strong>Homework:</strong> ${day.homework}`;
            }
            else
            {
                if(typeof(day.homework.note) != 'undefined') specialNote = `data-toggle="popover" title="Special Note" data-content="${day.homework.note}`;
                theHomeWork = `<li><strong>Homework:</strong> <a target="_blank" href="#" ${specialNote}>${day.project.title}</a></li>`;
            }
        } 
        return theHomeWork;
    }
    
    pub.getReplitsHTML = function(day){
        var content = '';
        if(typeof(day.replits)!='undefined'){
            var popoverContent = '<ul>'; 
            console.log(day.replits);
            day.replits.forEach((replit) => {
                
                if(typeof(replit)=='object') popoverContent += `<li>- <a href='${replit.url}'>${replit.title}</a></li>`;
                else if(typeof(replit)=='string') popoverContent += `<li>- ${replit}</li>`;
                else popoverContent += `<li>- Invalid Replit</li>`;
            });
            popoverContent += '</ul>';
            content += `<a target="_blank" href="#" data-html="true" data-container="body" data-placement="top" data-toggle="popover" title="Replit Classes" data-content="${popoverContent}">Replits</a>`;
        } 
        return content;
    }
    
    pub.getBreatheCodeHTML = function(day){
        var content = '';
        if(typeof(day['breathecode-lessons'])!='undefined'){
            var popoverContent = '<ul>'; 
            day['breathecode-lessons'].forEach((lesson) => {
                popoverContent += `<li>- <a href='${lesson.url}'>${lesson.title}</a></li>`;
            });
            popoverContent += '</ul>';
            content += `<a target="_blank" href="#" data-html="true" data-container="body" data-placement="left" data-toggle="popover" title="BreatheCode Lessons" data-content="${popoverContent}">Lessons</a>`;
        } 
        return content;
    }
    
    return pub;
})();
