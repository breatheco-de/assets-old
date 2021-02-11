var Timeline = (function(){
    var pub = {};
    var settings = {
        data: [
            { label: "Week 1", days:[] },
            { label: "Week 2", days:[] },
        ],
        containerSelector: '',
        fullMode: false,
        replitBaseURL: 'https://assets.breatheco.de/apps/replit/?r=',
        lessonBaseURL: 'http://assets.breatheco.de/apis/lesson/redirect/',
        quizBaseURL: 'https://assets.breatheco.de/apps/quiz/',
        projectBaseURL: 'https://projects.breatheco.de/d/',
        assignmentBaseURL: '#',
        menuContainerSelector: ''
    };
    let SyllabusDay = function(_day){
        return Object.assign(_day, {
            getInstructions: function(){
                if(typeof(_day.project.instructions) != 'undefined') return _day.project.instructions;
                if(typeof(_day.project.teacher_instructions) != 'undefined') return _day.project.teacher_instructions;
                else return false;
            },
            getSolution: function(){
                if(typeof(_day.project.solution) != 'undefined') return _day.project.solution;
                else false;
            },
            getKeyConcepts: function(){
                if(Array.isArray(_day['key-concepts']) && _day['key-concepts'].length > 0) return _day['key-concepts'];
                else false;
            },
            getAvoidConcepts: function(){
                if(Array.isArray(_day['avoid-concepts']) && _day['avoid-concepts'].length > 0) return _day['avoid-concepts'];
                else false;
            }
        });
    };

    pub.wrapDays = function(days){
        var content = '<div class="timeline-container">';
        content += `<h2 class="text-center">Timeline</h2>`;
        days.forEach((day, i) => {
            content += pub.renderDay(day, this.settings.fullMode, i);
        });
        content += '</div>';
        return content;
    };

    pub.init = function(incomingSettings){
        this.settings = $.extend(this.settings,incomingSettings);

        var sections = $(this.settings.containerSelector);
        var cont = 0;

        if(typeof this.settings.data == 'undefined') throw new Error('The JSON came empty or invalid');

        $(sections[cont]).html(pub.wrapDays(this.settings.data.days));
        // this.settings.data.forEach(function(day){
        //     var marginTop = 70;
        //     $(sections[cont]).find('.day').each(function(){
        //        $(this).css('top', marginTop);
        //        var topicsHeight = $(this).find('.day-topics').first().height();
        //        var projectsHeight = $(this).find('.day-projects').first().height();

        //        if(topicsHeight > projectsHeight){
        //             $(this).height(topicsHeight+30);
        //             marginTop += topicsHeight+15;
        //        }
        //        else
        //        {
        //             $(this).height(projectsHeight+30);
        //             marginTop += projectsHeight+15;
        //        }

        //     });
        //     cont++;
        // });

        $(this.settings.menuContainerSelector).append(pub.renderMenu(this.settings.data, this.settings.containerSelector));

        $('[data-toggle="popover"]').popover();
        $('[data-toggle="popover"]').click(function(){
           return false;
        });

    }

    pub.renderMenu = function(syllabus, weekContainerId){
        var content = '';
        var cont = 1;
        syllabus.days.forEach(function(day){
            content += `<li role="presentation"><a href="#day${cont.toString()}">
            <span class="nav__counter">Day ${cont}</span>
            <h3 class="nav__title">${day.label}</h3>
            <!-- <p class="nav__body">${(typeof day.summary != 'undefined') ? day.summary:''}</p> -->
          </a></li>`;
          cont++;
        });
        return content;
    }

    pub.renderDay = function(rawDay, full, index){
        const fullMode = typeof full === 'undefined' ? false : full;
        const day = SyllabusDay(rawDay)
        var theProject = '';
        if(typeof(day.project)=='object')
        {
            if(fullMode && day.getInstructions()) theProject = `<a target="_blank" href="${day.getInstructions()}">${day.project.title}</a>`;
            else theProject = day.project.title;
            if(fullMode && day.getSolution()){
                if(day.getInstructions()) theProject += ' -> ';
                theProject += ` <a target="_blank" href="${day.project.solution}">(model solution)</a>`;
            }
        }

        var theKeyConcepts = '<p>';
        if(day.getKeyConcepts() || day.getAvoidConcepts()) theKeyConcepts += '<strong>Most important </strong>';
        if(day.getKeyConcepts()){
            const popoverContent = '<ul>'+day['key-concepts'].map((concept) => `<li>- ${concept}</li>`).join('')+'</ul>';
            theKeyConcepts += `<a href="#" data-html="true" data-container="body" data-toggle="popover" title="Key Concepts" data-placement="top" data-content="${popoverContent}">key Concepts</a>`;
        }
        if(day.getAvoidConcepts()){
            const popoverContent = '<ul>'+day['key-concepts'].map((concept) => `<li>- ${concept}</li>`).join('')+'</ul>';
            if(day.getKeyConcepts()) theKeyConcepts += ' and ';
            theKeyConcepts += `<a href="#" data-html="true" data-container="body" data-toggle="popover" title="Key Concepts" data-placement="top" data-content="${popoverContent}">what to avoid</a>`;

        }
        theKeyConcepts += `</p>`;

        return `<div class="day ${(day.label.toLowerCase() == 'weekend') ? 'weekend' : ''}" id="day${index}">
            <div class="day-topics">
            ${fullMode ? theKeyConcepts : ''}
            
            ${fullMode ?
                "<strong>Teacher instructions:</strong>" + (day.instructions || day.teacher_instructions || day.description || 'No instructions for this particular day')
                :
                !Array.isArray(day['key-concepts']) ? 'No new concepts today' : '<ul>'+day['key-concepts'].map((concept) => `<li>${concept}</li>`).join('')+'</ul>'
            }
            ${
                (!fullMode ? '' : typeof day.instructions_link != 'undefined') ?
                `<a target="_blank" href="/apps/markdown-parser/?path=${day.instructions_link}">Full Instructions</a>`
                :''
            }
            </div>
            <div class="day-timeline"><h3 class="text-center">${day.label}</h3><p class="text-center day-label">Day ${index+1}</p></div>
            <div class="day-projects">
                <ul>
                <li><strong>Project:</strong> ${theProject || 'Work on previous projects'}</li>
                ${pub.getProjectHTML(day)}
                ${!fullMode ? '': `<p><strong>For students:</strong><br /> ${pub.getLessonsHTML(day)} - ${pub.getReplitsHTML(day)} - ${pub.getAssignmentsHTML(day)} - ${pub.getQuizzesHTML(day)}</p>`}
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
