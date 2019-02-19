import React, { Component } from 'react';
import ReactDOM from 'react-dom'
import { Route, Link, BrowserRouter as Router, Switch } from 'react-router-dom';
import createBrowserHistory from 'history/createBrowserHistory'

import ImgIcon from "./component/imgIcon"
import Notfound from "./component/notFound"
import Certificate from "./component/certificate"

import floridaDepartament from './image/floridadepartment.png'

export default class App extends Component{

  render(){
    const RenderCertificate = (props) => {
      const student_id = props.match.params.student_id;
      const cohort_slug = props.match.params.cohort_slug;
      return (
        <Certificate student_id={student_id} cohort_slug={cohort_slug}/>
      )
    }

    const Prueba = () => {
      return (
        <p>Hola!</p>
      )
    }
    
    const NotFound = () => {
      return (
        <p>No hay nada!</p>
      )
    }

    return (
      <Router basename={'/assets/apps/certificate'}>
        <div>
          <Switch>
            <Route path="/student/:student_id/cohort/:cohort_slug" component={RenderCertificate}/>
            <Route path="/" component={Prueba}/>
            <Route path='*' component={NotFound} />
          </Switch>
        </div>
      </Router>
    );
  }
}