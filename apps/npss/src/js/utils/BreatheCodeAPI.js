var BreatheCodeAPI = (function(){
    
    let publicScope = {};
    let settings = { 
        host: '',
        token: ''
    }
    
    publicScope.init = function(HOST, TOKEN){
        settings.host = 'https://talenttree-alesanchezr.c9users.io/';
        settings.token = '4bbf8d2f67acfba271c51001e5cb7e1d583ca13e';
    };
    
    let request = function(method = 'get', url = ''){
        
        let options = {};
        if(method!='get')
        {
            options.headers = {'content-type': 'application/json'};
            method: method.toLowerCase() // *GET, POST, PUT, DELETE, etc.
        }

        let promise = fetch(settings.host+url+"?access_token="+settings.token, options)
            .then(response => response.json()); // parses response to JSON

        return promise;
    }
    
    publicScope.getStudent = function(id){
        return request('get','student/'+id);
    }
    
    return publicScope;
})();
export default BreatheCodeAPI;