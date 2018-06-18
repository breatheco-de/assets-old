import React from 'react';
import PropTypes from 'prop-types';


class InputWithAdd extends React.Component {
    constructor(props){
        super(props);
        
        this.state = {
            value:''
        };
    }
    
    updateInput(e){
        this.setState({
            value: e.target.value
        });
    }
    
    checkForEnter(e){
        if (e.charCode === 13){
            this.submitInput();
        }
    }
    
    submitInput(){
        this.props.onAdd(this.state.value);
        this.setState({
            value: ''
        });
        this.focusableInput.focus();
    }
    
    render(){
        
        return(
            <div className="input-group mb-3">
                <input 
                    type="text" 
                    className="form-control" 
                    onChange={this.updateInput.bind(this)}
                    onKeyPress={(e) => this.checkForEnter(e)}
                    value={this.state.value} 
                    ref={(input) => { this.focusableInput = input; }}/>
                <div className="input-group-append">
                    <button className="btn btn-outline-secondary" onClick={(e) => this.submitInput()} type="button">ADD</button>
                </div>
            </div>
        );
    }
}

InputWithAdd.propTypes = {
   onAdd: PropTypes.func
};

export default InputWithAdd;