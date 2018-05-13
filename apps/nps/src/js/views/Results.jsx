import React from "react";
import Flux from "react-flux-dash";
import {Link} from "react-router-dom";
//import {PieChart, Pie, Legend, Tooltip, Cell} from 'recharts';
import SurveyStore from '../stores/SurveyStore';
import SurveyActions from '../actions/SurveyActions';
export default class Results extends Flux.View {
  
  constructor(){
    super();
    this.state = {
      results: [],
      cohorts: [],
      average: 0,
      groups: {},
      searchToken: ''
    };
    this.filterCohorts = null;
    
    this.bindStore(SurveyStore);
  }
  
  componentWillMount(){
    SurveyActions.getResults();
  }
  
  handleStoreChanges(){
    let results = SurveyStore.getResults();
    let data = this.calculateGroups(results);
    this.setState({
      results: results,
      groups: data.groups,
      cohorts: data.cohorts,
      average: data.average
    });
  }
  
  filterValues(){
    let data = this.calculateGroups(this.state.results);
    this.setState(data);
  }
  
  calculateGroups(rslts){
    let groups = {};
    let sum = 0;
    let cohorts = [];
    for(let i = 0; i<rslts.length;i++)
    {
      sum += parseInt(rslts[i].score);
      if(cohorts.indexOf(rslts[i].cohort_slug) == -1) cohorts.push(rslts[i].cohort_slug);
      
      if(this.filterCohorts && rslts[i].cohort_slug != this.filterCohorts) continue; 
      if(typeof groups[rslts[i].score] == 'undefined') groups[rslts[i].score] = 1;
      else groups[rslts[i].score]++;
    }
    
    const average = Math.round(sum/rslts.length);
    return { groups, cohorts, average  };
  }
  
  tableFilter(item){
    
    if(this.filterCohorts){
      if(item.cohort_slug != this.filterCohorts) return false;
    } 
    else if(this.state.searchToken.length<4) return true;
    
    let valid = (item.email.indexOf(this.state.searchToken) !== -1);
    if(valid) return true;
    valid = (item.cohort_slug.indexOf(this.state.searchToken) !== -1);
    if(valid) return true;
    
    return false;
  }
  
  render() {
    //if(!this.state.results) return (<div><h1>Awating results...</h1></div>);
    
    const resultRows = this.state.results.filter(this.tableFilter.bind(this)).map(function(item, i){
      return (<tr key={i} className="text-left">
              <td>{item.user_id}</td>
              <td className="email">{item.email}</td>
              <td className="score">{item.score}</td>
              <td className="cohort">{item.cohort_slug}</td>
              <td className="date">{item.created_at.substring(0, 10)}</td>
              <td className="comments">{item.comment}</td>
            </tr>);
    });
    
    const cohortElements = this.state.cohorts.map((item,i) => (<option key={i} value={item}>{item}</option>));
    
    let pieData = [];
    for (var name in this.state.groups) pieData.push({name: name + ' points', value: this.state.groups[name]});
    const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042'];
                  
    return (
      <div className="results">
          <nav className="navbar fixed-top navbar-light bg-light">
            <a className="navbar-brand" href="#">
              <img src="https://assets.breatheco.de/apis/img/images.php?blob&amp;random&amp;cat=icon&amp;tags=breathecode,64" width="30" height="30" className="d-inline-block align-top mr-2" alt="" />
              NPS
            </a>
          </nav>
          <div className="filters">
            <select className="form-control"
              onChange={(elm) => {
                this.filterCohorts = (elm.target.value != 'all') ? elm.target.value:null;
                this.filterValues();
              }}>
              <option value="all">Filter by cohort</option>
              {cohortElements}
            </select>
          </div>
          <div className="row kpis no-gutters">
            <div className="col-3 text-center">
              <span className="avg">Avg: {this.state.average}</span>
            </div>
            <div className="col-9 text-center">
              <input placeholder="search..." className="form-control" type="text" onChange={(e) => this.setState({searchToken: e.target.value.toLowerCase()})} value={this.state.searchToken} />
            </div>
          </div>
          <div className="row no-gutters">
            <div className="col-12">
              <div className="table-responsive">
                <table className="table table-hover table-striped">
                  <thead className="thead-dark">
                    <tr className="text-left">
                      <td>ID</td>
                      <td>Email</td>
                      <td>Score</td>
                      <td>Cohort</td>
                      <td>Created</td>
                      <td>Comments</td>
                    </tr>
                  </thead>
                  <tbody>
                    {resultRows}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
    )
  }
}