import React from 'react';

export default class FormCohort extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            replits: {}
        }
    }


    static getDerivedStateFromProps(nextProps, prevState){
        if (nextProps.replits != prevState.replits) {
            return { 
                replits: nextProps.replits
            }
        }
        return null;
    }

    handleChange(value, key){
        const newReplits = Object.assign(this.state.replits)
        newReplits[key] = value
        
        this.setState({
            replits: newReplits
        });
        
        this.props.getData(newReplits);
    }

    render(){
        let form = Object.entries(this.props.replits).map((value, i)=>{
            return(
                <div className="form-group row" key={i}>
                    <label className="col-md-2 col-form-label">{value[0]}:</label>
                    <div className="col-md-10">
                    <input key={i}
                        type="text" 
                        className="form-control" 
                        placeholder=""
                        value={value[1]}
                        onChange={(event)=>this.handleChange(event.target.value, value[0])} />
                    </div>
                </div>
            )
        });

        return (
            <div className="alert alert-primary section-slug-show no-margin">
                <div className="row justify-content-md-center">
                    <div className="col-md-8">
                        <form>
                            {form}
                        </form>
                    </div>
                </div>
            </div>
            
        );
    }
}
