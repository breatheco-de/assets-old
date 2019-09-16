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
        const replits = Object.assign(this.state.replits);
        replits[key].updatedValue = value;
        this.setState({ replits });
        this.props.getData(replits);
    }

    render(){
        let form = Object.entries(this.props.replits).filter(data => data[1]).map((data, i)=>{
            const r = data[1];
            return(
                <div className="form-group row" key={i}>
                    <label className="col-md-2 col-form-label">{r.title}:</label>
                    <div className="col-md-10">
                    <input key={i}
                        readOnly={typeof r.value !== 'undefined'}
                        type="text"
                        className="form-control"
                        placeholder=""
                        value={r.value || r.updatedValue || r.base}
                        onChange={(e)=> typeof r.value === 'undefined' && this.handleChange(e.target.value, r.slug)} />
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
