import React from "react";
import Flux from "react-flux-dash";
import {PieChart, Pie, Legend, Tooltip, Cell} from 'recharts';
import SurveyStore from '../stores/SurveyStore';
import SurveyActions from '../actions/SurveyActions';
export default class Results extends Flux.View {
  
  constructor(){
    super();
    this.state = {
      results: [],
      cohorts: [],
      groups: {},
    }
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
      cohorts: data.cohorts
    });
  }
  
  filterValues(){
    let data = this.calculateGroups(this.state.results);
    this.setState({
      groups: data.groups,
      cohorts: data.cohorts
    });
  }
  
  calculateGroups(rslts){
    let groups = {};
    let cohorts = [];
    for(let i = 0; i<rslts.length;i++)
    {
      if(cohorts.indexOf(rslts[i].cohort) == -1) cohorts.push(rslts[i].cohort);
      
      if(this.filterCohorts && rslts[i].cohort != this.filterCohorts) continue; 
      if(typeof groups[rslts[i].answer] == 'undefined') groups[rslts[i].answer] = 1;
      else groups[rslts[i].answer]++;
    }
    
    return { groups, cohorts };
  }
  
  render() {
    //if(!this.state.results) return (<div><h1>Awating results...</h1></div>);
    
    const resultRows = this.state.results.filter((item) => (!this.filterCohorts || item.cohort == this.filterCohorts)).map(function(item, i){
      return (<tr key={i}>
              <td>{item.user_id}</td>
              <td>{item.answer}</td>
              <td>{item.cohort}</td>
              <td>{item.created_at.date}</td>
              <td>{item.comments}</td>
            </tr>);
    });
    
    const cohortElements = this.state.cohorts.map((item,i) => (<option key={i} value={item}>{item}</option>));
    
    let pieData = [];
    for (var name in this.state.groups) pieData.push({name: name + ' points', value: this.state.groups[name]});
    const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042'];
                  
    return (
      <div className="results">
          <h1>NPS Score Results</h1>
          <div className="row mb-5">
            <div className="col-6">
              <PieChart width={250} height={200}>
                <Tooltip />
                <Pie data={pieData} cx={110} cy={100} innerRadius={40} outerRadius={80} fill="#82ca9d" label>
                {pieData.map((entry, index) => <Cell key={index} fill={COLORS[index % COLORS.length]}/>)}
                </Pie>
              </PieChart>
            </div>
            <div className="col-6">
              <select className="form-control"
                onChange={(elm) => {
                  this.filterCohorts = (elm.target.value != 'all') ? elm.target.value:null;
                  this.filterValues();
                }}>
                <option value="all">All cohorts</option>
                {cohortElements}
              </select>
            </div>
          </div>
          <div className="row">
            <div className="col-12">
              <table className="table table-hover table-striped">
                <thead className="thead-dark">
                  <tr>
                    <td>User ID</td>
                    <td>Answer</td>
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
    )
  }
}