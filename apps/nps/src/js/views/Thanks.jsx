import React from "react";
import Flux from "react-flux-dash";
import SurveyStore from '../stores/SurveyStore';
export default class Thanks extends Flux.View {
  
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
                    <h1>Thank You!</h1>
                  </div>
                </div>
            </div>
        )
  }
}