import React from 'react';

//include images into your bundle
import BannerHeader from './BannerHeader.js';
import ShowCohort from './ShowCohort.js';
import {tokens} from '../action';

//create your first component
export class Home extends React.Component{
    constructor(props){
        super(props);

        var url = new URL(window.location.href);
        this.state = {
            dataApiAllCohorts: [],
            errorMessage: null,
            optionSelected: '',
            show: false,
            cohort: url.searchParams.get("cohort") || null,
            teacher: url.searchParams.get("teacher") || null
        };
    }

    componentDidMount(){
        this.getApiCohort();
    }

    //Consulta API
	getApiCohort(){

        const endpoint = `${process.env.BC_HOST}/cohorts/${this.state.teacher ? 'teacher/'+this.state.teacher:''}`;
        fetch(endpoint+'?access_token='+tokens().bcToken)
            .then((resp) => {
                if(resp.status == 200) return resp.json();
                else{
                    if(resp.status == 401) throw new Error('Not authorized');
                }
            })
            .then((data) => {
                let dataCohort = data;
                this.setState({
                    dataApiAllCohorts: dataCohort.data
                });
            })
            .catch((error) => {
                console.log('Something is wrong', error);
                this.setState({ errorMessage: error.message || error });
            });
        if(this.state.cohort){
            const endpoint = `${process.env.BC_HOST}/cohort/${this.state.cohort}`;
            fetch(endpoint+'?access_token='+tokens().bcToken)
                .then((resp) => {
                    if(resp.status == 200) return resp.json();
                    else{
                        if(resp.status == 401) throw new Error('Not authorized');
                    }
                })
                .then(({data}) => this.setState({ optionSelected: data, show: true }))
                .catch((error) => {
                    console.log('Something is wrong', error);
                    this.setState({ errorMessage: error.message || error });
                });
        }
    }

    handleSubmit(event){
        event.preventDefault();
        this.setState({ show: true });
    }

    handleChange(event){
        const cohort = this.state.dataApiAllCohorts.find((c)=>c.slug == event.target.value);
        this.setState({
            optionSelected: cohort
        });
    }

    getDataNew(data){
        this.setState({
            optionSelected: data
        });
    }

    render(){

        const optionSelect = this.state.dataApiAllCohorts.map((val, key)=>(
            <option value={val.slug} key={key}>{val.name}</option>
        ));
        return (
            <div>
            { (this.state.errorMessage) ? <div className="alert alert-danger text-center rounded-0">{this.state.errorMessage}</div>:''}
            { (this.state.show) ?
                <ShowCohort data={{cohortSelected: this.state.optionSelected, allCohorts: this.state.dataApiAllCohorts}} />
                :
                <div>
                    <BannerHeader/>
                    <div className="alert alert-primary">
                        <h4 className="alert-heading">Type a cohort to update or create a new one:</h4>
                        <form onSubmit={(event)=> this.handleSubmit(event)}>
                        <div className="form-row justify-content-md-center banner-form">
                            <div className="form-group col-md-4 no-margin">
                                <select className="custom-select" onChange={(event)=>this.handleChange(event)}>
                                    <option defaultValue="default">Select a cohort to edit the replits</option>
                                    {optionSelect}
                                </select>
                            </div>
                            <div className="form-group col-3 no-margin">
                                <button type="submit" className="btn btn-light form-control">START</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            }
            </div>
        );
    }
}
