import Flux from "react-flux-dash";

class ErrorStore extends Flux.Store{
    
    constructor(){
        super();
        this.state = {
            errors: []
        };
    }
    
    _addError(msg){ 
        this.setStoreState({ 
            errors: this.state.errors.concat([msg])
        }).emit(); 
    }
    getErrors(){ return this.state.errors; }
    
}
export default new ErrorStore();