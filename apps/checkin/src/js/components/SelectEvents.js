import React from 'react';
var moment = require('moment');

export default class SelectEvents extends React.Component{
    constructor (props) {
        super(props);
        this.state = {
            dataEventsToDay: [],
            eventSelected: []
        };
    }

    componentDidMount(){
        this.getAllEventsToDay();
    }
    
    getAllEventsToDay(){
        const endpoint = process.env.BREATHECODE+'all?status=upcoming';
        fetch(endpoint)
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            var now = moment().format('YYYY-MM-DD');
            // const eventFiltered = data.filter((c)=> c.event_date >= now).map((c, k)=>{
            const eventFiltered = data.map((c, k)=>{
                return {
                    id: c.id,
                    description: c.description,
                    title: c.title,
                    address: c.address,
                    capacity: c.capacity,
                    city_slug: c.city_slug,
                    location_slug: c.location_slug,
                    event_date: c.event_date
                };
            });

            this.setState({
                dataEventsToDay: eventFiltered
            });
        })
        .catch((error) => {
            console.log('error', error);
        });
    }

    handleSubmit(event){
        event.preventDefault();
        this.props.receiveData(this.state.eventSelected);
    }

    handleChange(event){
        const idEvent = event.target.value;
        const eventDetail = this.state.dataEventsToDay.filter((c)=> c.id == idEvent);

        this.setState({
            eventSelected: eventDetail
        });
    }

    render(){
        const optionEvents = this.state.dataEventsToDay.map((value, key)=>{
            return (
                <option key={key} value={value.id}>{value.title}</option>
            )
        });

        return(
        <div className="row full-width no-margin">
            <div className="col-md-12 no-padding">
                <div className="alert alert-primary">
                    <h4 className="alert-heading">{this.props.label}</h4>
                    <form onSubmit={(event)=> this.handleSubmit(event)}>
                        <div className="form-row justify-content-md-center justify-content-sm-center banner-form">
                            <div className="form-group col-md-4 col-sm-5 col-12 no-margin pt-2">
                                <select className="custom-select" onChange={(event)=>this.handleChange(event)}>
                                    <option value="0" defaultValue="default">{this.props.placeholder}</option>
                                    {optionEvents}
                                </select>
                            </div>
                            <div className="form-group col-md-3 col-sm-2 col-4 no-margin pt-2">
                                <button type="submit" className="btn btn-light form-control">START</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        )
    }
}