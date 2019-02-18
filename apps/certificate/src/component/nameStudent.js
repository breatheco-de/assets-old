import React, { Component } from 'react'
import moment from 'moment'


export default class NameStudent extends Component {
    constructor(props){
        super(props)
        
        this.state={
            first_name: '',
            last_name: ''
        }
    }

    componentDidMount(){
        const full_name = this.props.dataStudent.full_name.split(' ');
        this.setState({
            first_name: full_name[0],
            last_name: full_name[1]
        })
    }

    render(){
        return(
            <div>
                <div className="row full-width justify-content-center data-student">
                        <div className="col-auto">
                            <p className="text-recognize text-uppercase no-margin">Recognizes that</p>
                            <h1 className="first-name no-margin text-uppercase">&#60;{this.state.first_name}</h1>
                            <h1 className="last-name no-margin text-uppercase">{this.state.last_name}/&#62;</h1>
                        </div>
                </div>
                <div className="row full-width justify-content-center description-certificate">
                    <div className="col-auto pt-2">
                        <p className="text-center text-uppercase no-margin font-bold">has successfully completed</p>
                        <p className="text-center text-uppercase no-margin">The {this.props.dataProfile.name} program.</p>
                        <h3 className="text-center text-uppercase no-margin">{this.props.dataProfile.duration_in_hours}+ hours</h3>
                        <p className="text-center text-uppercase no-margin">{this.props.dataCohort.name}</p>
                        <p className="text-center no-margin pt-2">{moment(this.props.dataCohort.ending_date).format("MMM Do YYYY")}</p>
                    </div>
                </div>
            </div>    
        )
    }
}