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
      student: SurveyStore.getStudent(),
      answer: SurveyStore.getAnswerData()
    })
  }
  
  render() {
    if(!this.state.student) this.props.history.push('404/')
    return (
            <div className="survey-panel">
                <div className="row">
                  <div className="col-12">
                    {
                      (this.state.answer.rating > 7) ?
                        <div>
                          <img style={{maxHeight: '200px'}} src="http://assets.breatheco.de/apis/img/images.php?cat=funny&random&blob" />
                          <h1 className="text-success">Awesome!</h1>
                        </div>:''
                    }
                    <h3>You gave us {this.state.answer.rating} points out of 10</h3>
                    <h3 className="mb-4">Are you sure you want to send this results?</h3>
                    <Link to={"/survey/"+this.state.student.id} className="btn btn-secondary">Back to Editing</Link>
                    <button className="btn btn-success ml-5" onClick={()=>SurveyActions.sendSurvey(this.props.history)}>Confirm Send</button>
                  </div>
                </div>
            </div>
        )
  }
}