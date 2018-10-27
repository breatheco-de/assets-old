import React from 'react';
import Flux from 'react-flux-dash';
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
    this.bindStore(ErrorStore, this.handleStoreChanges);
  }
  
  handleStoreChanges(){
    this.setState({
      errors: ErrorStore.getErrors()
    });
  }
  
  render() {
    
    let errors = this.state.errors.map((msg,i) => (<li key={i}>{ msg }</li>));
    const basename = process.env.BASENAME;
    return (
      <div className="container-fluid text-center">
        { (errors.length>0) ? (<div className="alert alert-danger mt-5">{errors}</div>) : ''}
        <BrowserRouter basename={basename}>
          <div>
            <Switch>
              <Route path="/survey/:id" component={Survey} />
              <Route path="/confirm" component={Confirm} />
              <Route path="/thanks" component={Thanks} />
              <Route path="/results" component={Results} />
              <Route render={() => (<h1>Not found!</h1>)} />
            </Switch>
          </div>
        </BrowserRouter>
      </div>
    );
  }
}
