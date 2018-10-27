import React from 'react';
var moment = require('moment');

export default class DetailEvent extends React.Component{
    constructor(props){
        super(props)
    }

    render(){
        return(
            <div className="background-form">
                <div className="row justify-content-center full-width no-margin">
                    <div className="col-md-8 col-sm-10 col-11 pt-5 pb-5">
                        <form>
                            <div className="form-group row">
                                <label className="col-md-2 col-form-label">Title Event:</label>
                                <input
                                    type="text" 
                                    className="form-control"
                                    placeholder={this.props.dataEvent[0].title} disabled/>
                                <label className="col-md-2 col-form-label">Address:</label>
                                <input
                                    type="text" 
                                    className="form-control"
                                    placeholder={this.props.dataEvent[0].address} disabled/>
                                <label className="col-md-2 col-form-label">Date:</label>
                                <input
                                    type="text" 
                                    className="form-control"
                                    placeholder={moment(this.props.dataEvent[0].event_date).format('MMMM Do YYYY, h:mm:ss a')} disabled/>
                                <label className="col-md-2 col-form-label">Capacity</label>
                                <input
                                    type="text" 
                                    className="form-control"
                                    placeholder={this.props.dataEvent[0].capacity} disabled/>
                            </div>
                        </form>
                    </div>
                </div>      
            </div>
        )
    }
}