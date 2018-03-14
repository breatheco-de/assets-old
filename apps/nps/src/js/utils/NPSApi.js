var Wrapper = (function(){
    
    let publicScope = {};
    let settings = { 
        host: '',
        token: ''
    }
    
    publicScope.init = function(HOST, TOKEN){
        if(typeof(HOST) === 'undefined' || HOST=='') throw new Error("Undefined NPS API Host");
        if(typeof(TOKEN) === 'undefined' || TOKEN=='') throw new Error("Undefined NPS API TOKEN");
        settings.host = HOST;
        settings.token = TOKEN;
    };
    
    let request = function(method = 'get', url = '', data){
        
        let options = {};
        if(method!='get')
        {
            options.headers = {'content-type': 'application/json'};
            options.method = method.toLowerCase(); // *GET, POST, PUT, DELETE, etc.
            options.body = JSON.stringify(data);
        }
        
        let promise = fetch(settings.host+url+"?access_token="+settings.token, options)
            .then(response => response.json()) // parses response to JSON
            .catch(function(error){
               if(process.env.DEBUG) throw new Error(error.message);
               else throw new Error("Something went wrong when trying to communicate with the NPS API");
            });
        return promise;
    }
    
    publicScope.getStudentAnswers = function(id){
        return request('get','student_answers/'+id);
    }
    
    publicScope.getResults = function(){
        return request('get','answers');
    }
    
    publicScope.saveStudentAnswer = function(data){
        
        return request('put','answer',{
        	user_id: data.student.id,
        	answer: data.answer.rating,
        	cohort: data.student.cohorts[0],
        	comments: data.answer.comments
        });
    }
    
    return publicScope;
})();
export default Wrapper;