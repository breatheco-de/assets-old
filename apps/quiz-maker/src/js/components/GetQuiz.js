import React from 'react';

import 'jquery';

export default class GetQuiz extends React.Component {
	constructor(){
        super();
        
        this.state = {
            value: 'https://assets.breatheco.de/apis/quiz/new'
        }
    }
    
    handleSubmit(event){
        event.preventDefault();
        this.props.onSelect(this.state.value);
        this.setState({
            value: ''
        });
    }

    handleChange(event){
        this.setState({
            value: event.target.value
        });
    }

	render () {
		return (
            <div className="alert alert-primary">
                <h4 className="alert-heading">Load Quiz from URL</h4>
                <form onSubmit={this.handleSubmit.bind(this)}>
                  <div className="form-row">
                    <div className="form-group col-md-8">
                        <input 
                            className="form-control" 
                            type="text" 
                            value={this.state.value} 
                            onChange={this.handleChange.bind(this)} 
                            placeholder="JSON URL HERE"
                        />
                    </div>
                    <div className="form-group col">
                        <button type="submit" className="btn btn-light form-control">Load Quiz</button>
                    </div>
                  </div>
                </form>
            </div>
		);
	}
}