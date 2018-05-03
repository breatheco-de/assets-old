import React from 'react';

export default class NewQuestion extends React.Component {
	constructor(props){
		super(props);
		this.state = {
		}
    }
    
    newBadges(){
        this.props.onClick();    
    }

	render () {
		return (
			<div className="">
                <button 
                type="button" 
                className="btn btn-primary"
                onClick={this.newBadges.bind(this)}>
                    <i className="fas fa-plus-circle"></i> Add Badges
                </button>
            </div>
		);
	}
}