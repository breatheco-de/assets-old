var Timeline = (function(){
    var pub = {};
    var settings = {
        data: [
            { label: "Week 1", days:[] },
            { label: "Week 2", days:[] },
        ],
        containerSelector: '',
        replitBaseURL: 'https://breatheco.de/replit/?r=',
        lessonBaseURL: 'https://breatheco.de/en/lesson/',
        quizBaseURL: 'https://assets.breatheco.de/apps/quiz/',
        projectBaseURL: 'https://breatheco.de/replit/?r=',
        assignmentBaseURL: '#',
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
        
        if(typeof this.settings.data == 'undefined') throw new Error('The JSON came empty or invalid');
        
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
            if(typeof(day.project.instructions) != 'undefined') theProject = `<a target="_blank" href="${day.project.instructions}">${day.project.title}</a>`;
            else theProject = day.project.title;
            if(typeof(day.project.solution) != 'undefined') theProject += ` <a target="_blank" href="${day.project.solution}">(solution)</a>`;
        }

        var theKeyConcepts = '';
        if(typeof(day['key-concepts'])!='undefined'){
            var popoverContent = '<ul>'; 
            day['key-concepts'].forEach((concept) => {
                popoverContent += `<li>- ${concept}</li>`;
            });
            popoverContent += '</ul>';
            theKeyConcepts = `<p><a href="#" data-html="true" data-container="body" data-toggle="popover" title="Key Concepts" data-placement="top" data-content="${popoverContent}">Key Concepts</a></p>`;
        } 
        
        return `<div class="day ${(day.label.toLowerCase() == 'weekend') ? 'weekend' : ''}">
          <h3 class="text-center">${day.label}</h3>
          <div class="day-topics">
            ${theKeyConcepts}
            ${day.instructions || day.description || 'No instructions for this particular day'}
            ${
                (typeof day.instructions_link != 'undefined') ?
                    `<a target="_blank" href="/apps/markdown-parser/?path=${day.instructions_link}">Full instructions</a>`
                    :''
            }
          </div>
          <div class="day-projects">
            <ul>
              <li><strong>Project:</strong> ${theProject || 'Work on previous projects'}</li>
              ${pub.getProjectHTML(day)}
              <p class="text-right">${pub.getLessonsHTML(day)} - ${pub.getReplitsHTML(day)} - ${pub.getAssignmentsHTML(day)} - ${pub.getQuizzesHTML(day)}</p>
            </ul>
          </div>
        </div>`;
    }
    
    pub.getProjectHTML = function(day){
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
                
                if(typeof(replit)=='object') popoverContent += `<li>- <a href='${settings.replitBaseURL+replit.slug}'>${replit.title}</a></li>`;
                else if(typeof(replit)=='string') popoverContent += `<li>- ${replit}</li>`;
                else popoverContent += `<li>- Invalid Replit</li>`;
            });
            popoverContent += '</ul>';
            content += `<a target="_blank" href="#" data-html="true" data-container="body" data-placement="top" data-toggle="popover" title="Replit Classes" data-content="${popoverContent}">Replits</a>`;
        } 
        return content;
    }
    
    pub.getAssignmentsHTML = function(day){
        var content = '';
        if(typeof(day.assignments)!=='undefined'){
            var popoverContent = '<ul>'; 
            console.log("Assignments: ",day.assignments);
            day.assignments.forEach((assignment) => {
                
                if(typeof(assignment)=='object') popoverContent += `<li>- <a href='${settings.assignmentBaseURL+assignment.slug}'>${assignment.title}</a></li>`;
                else if(typeof(assignment)=='string') popoverContent += `<li><a href='https://projects.breatheco.de/d/${assignment}#readme' target='_blank'>${assignment}</a></li>`;
                else popoverContent += `<li>- Invalid assignments</li>`;
            });
            popoverContent += '</ul>';
            content += `<a target="_blank" href="#" data-html="true" data-container="body" data-placement="top" data-toggle="popover" title="Assignments" data-content="${popoverContent}">Assignments</a>`;
        }
        return content;
    }
    
    pub.getLessonsHTML = function(day){
        var content = '';
        if(typeof(day['lessons'])!='undefined'){
            var popoverContent = '<ul>'; 
            day['lessons'].forEach((lesson) => {
                popoverContent += `<li>- <a href='${settings.lessonBaseURL+lesson.slug}'>${lesson.title}</a></li>`;
            });
            popoverContent += '</ul>';
            content += `<a target="_blank" href="#" data-html="true" data-container="body" data-placement="left" data-toggle="popover" title="BreatheCode Lessons" data-content="${popoverContent}">Lessons</a>`;
        } 
        return content;
    }
    
    pub.getQuizzesHTML = function(day){
        var content = '';
        if(typeof(day['quizzes'])!='undefined'){
            var popoverContent = '<ul>'; 
            day['quizzes'].forEach((quiz) => {
                popoverContent += `<li>- <a href='${settings.quizBaseURL+quiz.slug}'>${quiz.title}</a></li>`;
            });
            popoverContent += '</ul>';
            content += `<a target="_blank" href="#" data-html="true" data-container="body" data-placement="left" data-toggle="popover" title="BreatheCode Quizzes" data-content="${popoverContent}">Quizzes</a>`;
        } 
        return content;
    }
    
    return pub;
})();
