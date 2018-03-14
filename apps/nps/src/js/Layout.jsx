import React from 'react';
import Flux from "react-flux-dash";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import Survey from "./views/Survey.jsx";
import Confirm from "./views/Confirm.jsx";
import Thanks from "./views/Thanks.jsx";
import Results from "./views/Results.jsx";
import ErrorStore from './stores/ErrorStore';
export default class Layout extends Flux.View {
  
  constructor(){
    super();
    this.state = {
      errors: []
    };
    this.bindStore(ErrorStore);
  }
  
  handleStoreChanges(){
    this.setState({
      errors: ErrorStore.getErrors()
    });
  }
  
  render() {
    
    let errors = this.state.errors.map((msg,i) => (<li key={i}>{ msg }</li>));
    
    return (
      <div className="container text-center">
        { (errors.length>0) ? (<div className="alert alert-danger mt-5">{errors}</div>) : ''}
        <BrowserRouter basename={process.env.BASENAME}>
          <div>
            <Switch>
              <Route exact path="/survey/:id" component={Survey} />
              <Route exact path="/confirm" component={Confirm} />
              <Route exact path="/thanks" component={Thanks} />
              <Route exact path="/results" component={Results} />
              <Route render={() => (<h1>Not found!</h1>)} />
            </Switch>
          </div>
        </BrowserRouter>
      </div>
    );
  }
}
