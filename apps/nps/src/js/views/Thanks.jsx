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
                    <h1>Thank You!</h1>
                    { 
                      (this.state.answer.rating > 7) ?
                        <div className="">
                          <p>Would you mind giving us a review on Switchup or Course Report?</p>
                          <a className="btn btn-light" href="https://www.coursereport.com/schools/4geeks-academy#/reviews/write-a-review">
                            Course Report
                          </a>
                          <span>or</span>
                          <a className="btn btn-light" href="https://www.switchup.org/bootcamps/4geeks-academy">
                            Write a review on Switchup
                          </a>
                        </div> : ''
                    }
                  </div>
                </div>
            </div>
        )
  }
}