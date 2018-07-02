import React from 'react';
import {loadReplits} from '../../action.js';


export default class SelectReplits extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            profile: '',
            cohort: ''
        };
    }

    render(){
        
        const optionSelect = this.props.cohorts.map((val, key)=>(
            <option value={val.slug} key={key}>{val.name}</option>
        ));

        return (
            <div className="form-row justify-content-md-center col-md-12 banner-form preload">
            <p className="align-middle title-preload">Cohort Name: </p>
                <div className="form-group col-md-4 no-margin no-padding">
                    <select 
                        className="custom-select" 
                        onChange={(event)=>this.setState({
                            cohort_slug: event.target.value
                        })}>
                        <option defaultValue="default">Select a cohort to edit the replits</option>
                        {optionSelect}
                    </select>
                </div>
                <div className="form-group btn-preload">
                    <button type="button" className="btn btn-primary" onClick={()=>loadReplits(this.state.cohort_slug)}>Load</button>
                </div>
                <div className="form-group btn-preload">
                    <button type="button" className="btn btn-light">Cancel</button>
                </div>
            </div>
        );
    }
}
