import React from 'react';
import Flux from '@4geeksacademy/react-flux-dash';
import {store, loadReplits, loadTemplates} from '../action.js';

import RowTitle from './show/RowTitle.jsx';
import FormSlug from './show/FormSlug.jsx';
import FormCohort from './show/FormCohort.jsx';
import BannerHeader from './BannerHeader.jsx';
import SelectReplits from './show/SelectReplits.jsx';

export default class ShowCohort extends Flux.DashView{
    constructor(props){
        super(props);

        this.state = {
            typeProfile: '',
            typeCohort: '',
            cohortLabel: [],
            cohortDataInput: [],
            cohortDataSlug: '',
            forJsonCohort: [],
            allCohorts: [],
            showPreLoad: false,
            show: false
        };
    }

    static getDerivedStateFromProps(nextProps, prevState){
        
        if (nextProps.data.cohortSelected.profile_slug != prevState.typeProfile 
            && 
            nextProps.data.cohortSelected.slug != prevState.typeCohort) {
            return { 
                    typeProfile: nextProps.data.cohortSelected.profile_slug, 
                    typeCohort: nextProps.data.cohortSelected.slug,
                    allCohorts: nextProps.data.allCohorts
            }
        }

        return null;
    }

    componentDidMount(){
        
        loadReplits(this.state.typeCohort);
        this.subscribe(store, 'replits', (data)=>{
            this.setState({ cohortDataInput: data, forJsonCohort: data });
        });
        
        if(!this.state.typeProfile){
            alert("This cohort is missing the profile_slug, therefor is impossible to load its template");
        }
        else{
            loadTemplates(this.state.typeProfile);
            this.subscribe(store, 'templates', (data)=>{
                this.setState({ cohortLabel: data });
            });
        }

        // this.getApiProfile(this.state.typeProfile);
    }

    getApiProfile(profile){
        
        let endpoint = process.env.hostAssets+'/apis/replit/template/'+profile;
    		fetch(endpoint)
    		.then((response) => {
    			return response.json();
    		})
    		.then((data) => {
                console.log(data);
                this.setState({ cohortLabel: data });
    		})
    		.catch((error) => {
    			console.log('error', error);
            })
    }

    getDataFormSlug(data){
        this.setState({
            cohortDataSlug: data,
            typeCohort: data
        });
    }

    getDataFormCohort(data){
        this.setState({
            forJsonCohort: data,
        });
    }

    openRow(){
        this.setState({
            showPreLoad: true
        })
    }

    getDataSelectReplits(data){
        this.props.getData(data);
    }
    
    render(){

        let selectReplits = (this.state.showPreLoad) ? <SelectReplits cohorts={this.props.data.allCohorts.filter((c)=>c.profile_slug == this.state.typeProfile)}/> : ''
        return (
            <div>
            <BannerHeader button="downloadProgress" createJson={[this.state.typeCohort ,this.state.forJsonCohort]} jsonByPost={this.state.forJsonCohort[1]}/>
            <RowTitle title="General Cohort Information"/>
            <FormSlug input={this.state.typeCohort} getData={(data)=>this.getDataFormSlug(data)}/>
            
            <div className="container-fluid p-0">
                <div className="row">
                    <div className="col-12">
                        <nav className="navbar navbar-dark bg-dark">
                            <a className="navbar-brand" href="#">
                                Replits
                            </a>
                            <button 
                                type="button" 
                                className="btn btn-primary float-right"
                                onClick={()=>this.openRow()}
                                >pre-load values from previous cohort</button>
                                {selectReplits}
                        </nav>
                    </div>
                </div>
            </div>
            <FormCohort 
                replits={this.state.cohortDataInput}
                getData={(data)=>this.getDataFormCohort(data)}
                />
            </div>
        );
    }
}
