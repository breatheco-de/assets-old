import React from "react";
import Flux from "react-flux-dash";
import {Link} from "react-router-dom";
import SurveyActions from '../actions/SurveyActions';

import SurveyStore from '../stores/SurveyStore';

export default class Survey extends Flux.View {
  constructor(){
    super();
    this.state = {
      totalStars: 10,
      rating: 0,
      days: null,
      student: null,
      errorMessage: ''
    }
    this.comments = '';
    
    this.bindStore(SurveyStore, this.handleStoreChanges);
  }
  
  componentWillMount(){
    SurveyActions.getStudentData(this.props.match.params.id);
  }
  
  handleStoreChanges(){
    let student = SurveyStore.getStudent();
    let answers = SurveyStore.getStudentAnswers();
    
    if(student && typeof student.lastAnswer != 'undefined' && student.lastAnswer)
    {
      var today = new Date();
      var daysBetween = Math.floor(((today.getTime() - student.lastAnswer.getTime()) / 1000) / (60 * 60));
      if(daysBetween < 25) this.setState({ days: daysBetween, errorMessage: "You have already voted "+daysBetween+" days ago, please wait at least 25 days"});
      else this.setState({ student, answers });
    }
    else this.setState({ student, answers });
  }
  
  saveAnswerData(){
    
    if(this.state.rating == 0) this.setState({ errorMessage: 'Please give us a rating' });
    else if(this.comments == '') this.setState({ errorMessage: 'Please specify our oportunities or improovements' });
    else{
      SurveyActions.saveAnswerData({
        comments: this.comments,
        rating: this.state.rating
      });
      this.props.history.push('/confirm');
    } 
  }
  
  render() {
    if(this.state.student == null) return (<h1>Loading data...</h1>);
    
    let stars = [];
    for(let i=1; i<=this.state.totalStars;i++){
      let selected = true;
      if(this.state.rating<i) selected = false;
      stars.push(<li key={i} className={selected ? "selected":""} onClick={()=>this.setState({rating: i})}>{i}</li>);
    } 
    
    return (
            <div className="survey-panel">
                <div className="row">
                  <div className="col-12">
                    {(this.state.errorMessage != '') ? (<div className="alert alert-danger">{this.state.errorMessage}</div>) : ''}
                    <h1>How likely are you to recomend 4Geeks Academy to your friends and family?</h1>
                  </div>
                </div>
                <div className="row">
                  <div className="col-2 col-xl-3 text-right leyend">
                    Not at all likely
                  </div>
                  <div className="col-8 col-xl-6">
                    <ul className="npm-buttons">
                      {stars}
                    </ul>
                  </div>
                  <div className="col-2 col-xl-3 text-left leyend">
                    Extremelly Likely
                  </div>
                </div>
                <div className="row mb-4">
                  <div className="col-12">
                    <h1>What can we do to improove?</h1>
                    <textarea className="form-control"
                      onChange={ evt => this.comments = evt.target.value}
                      defaultValue={this.comments}></textarea>
                  </div>
                </div>
                <div className="row">
                  <div className="col-12">
                    <button 
                      onClick={() => this.saveAnswerData()} 
                      className="btn btn-success form-control">Send my answer</button>
                  </div>
                </div>
            </div>
        )
  }
}