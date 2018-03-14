var Wrapper = (function(){
    
    let publicScope = {};
    let settings = { 
        host: '',
        token: ''
    }
    
    publicScope.init = function(HOST, TOKEN){
        settings.host = 'https://assets-alesanchezr.c9users.io/apis/nps/';
        settings.token = '4bbf8d2f67acfba271c51001e5cb7e1d583ca13e';
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
              console.log(error);  
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