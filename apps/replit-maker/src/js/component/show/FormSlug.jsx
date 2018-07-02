import React from 'react';
import 'jquery';

export default class FormSlug extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            cohortSlug: this.props.input
        }
    }

    handleChange(event){
        this.setState({
            cohortSlug: event.target.value
        });
        this.props.getData(event.target.value);
    }

    render(){
        return (
            <div className="alert alert-primary section-slug-show no-margin">
                <div className="row justify-content-md-center">
                    <div className="col-md-10">
                        <h4 className="alert-heading">Cohort Slug:</h4>
                    </div>
                </div>
                <form>
                <div className="form-row justify-content-md-center banner-form">
                    <div className="form-group col-md-10 no-margin">
                        <input 
                            type="text" 
                            className="form-control" 
                            placeholder="Cohort slug"
                            value={this.state.cohortSlug} 
                            onChange={(event)=>this.handleChange(event)}/>
                    </div>
                </div>
                </form>
            </div>
        );
    }
}
