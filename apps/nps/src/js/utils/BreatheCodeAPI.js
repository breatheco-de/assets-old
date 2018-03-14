var BreatheCodeAPI = (function(){
    
    let publicScope = {};
    let settings = { 
        host: '',
        token: ''
    }
    
    publicScope.init = function(HOST, TOKEN){
        if(typeof(HOST) === 'undefined' || HOST=='') throw new Error("Undefined BreatheCode API Host");
        if(typeof(TOKEN) === 'undefined' || TOKEN=='') throw new Error("Undefined BreatheCode API TOKEN");
        settings.host = HOST;
        settings.token = TOKEN;
    };
    
    let request = function(method = 'get', url = ''){
        
        let options = {};
        if(method!='get')
        {
            options.headers = {'content-type': 'application/json'};
            method: method.toLowerCase() // *GET, POST, PUT, DELETE, etc.
        }

        let promise = fetch(settings.host+url+"?access_token="+settings.token, options)
            .then(response => response.json()) // parses response to JSON
            .catch(function(error){
                if(process.env.DEBUG) throw new Error(error.message);
                else throw new Error("Something went wrong when trying to communicate with the BreatheCode API");
            });

        return promise;
    }
    
    publicScope.getStudent = function(id){
        return request('get','student/'+id);
    }
    
    return publicScope;
})();
export default BreatheCodeAPI;