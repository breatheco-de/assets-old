import React from "react";
import Flux from "react-flux-dash";
import SurveyActions from '../actions/SurveyActions';
import SurveyStore from '../stores/SurveyStore';
import {Link} from "react-router-dom";
export default class Confirm extends Flux.View {
  
  constructor(){
    super();
    this.state = {
      student: null
    }
  }
  
  componentWillMount(){
    this.setState({
      student: SurveyStore.getStudent()
    })
  }
  
  render() {
    if(!this.state.student) this.props.history.push('404/')
    return (
            <div className="survey-panel">
                <div className="row">
                  <div className="col-12">
                    <h1>Are you sure you want to send this results?</h1>
                    <Link to={"/survey/"+this.state.student.id} className="btn btn-secondary">Back to Editing</Link>
                    <button className="btn btn-success ml-5" onClick={()=>SurveyActions.sendSurvey(this.props.history)}>Confirm Send</button>
                  </div>
                </div>
            </div>
        )
  }
}