import React, { Component } from 'react';
import jsPDF from 'jspdf';
import html2canvas from 'html2canvas';

import Header from './header'
import NameStudent from './nameStudent'

export default class Certificate extends Component {
    constructor(props){
        super(props)
        this.state = {
            studies_finished: false,
            access_token: '',
            student_id: '',
            cohort_slug: '',
            data_cohort: '',
            data_student: '',
            data_profile: ''
        }
    }

    static getDerivedStateFromProps(nextProps, prevState){
        console.log(nextProps, 'nextProps');
        console.log(prevState, 'prevState');
        if(nextProps.student_id !== prevState.student_id && nextProps.cohort_slug !== prevState.student_id){
          return { 
              student_id: nextProps.student_id,
              cohort_slug: nextProps.cohort_slug
            };
        }
        else return null;
     }

    componentDidMount(){
        let url = new URL(window.location.href);
        let tokenUrl = url.searchParams.get("access_token");
        console.log(tokenUrl, '"access_token"')

        this.getDataCohort(tokenUrl);
        this.getDataStudent(tokenUrl);
    }

    getDataStudent(token){
        const endpoint = 'https://talenttree-alesanchezr.c9users.io/student/'+this.state.student_id+'?access_token='+token;
        fetch(endpoint)
        .then((response)=>{
            return response.json();
        })
        .then((data)=>{
            //Aqui chequeamos si el estudiando se graduo y asignamos guardamos datos
            if(data.data.status == "studies_finished"){
                this.setState({
                    data_student: data.data,
                    studies_finished: true
                })
                console.log(data, 'response')
            }
        })
    }

    getDataCohort(token){
        const endpoint = 'https://talenttree-alesanchezr.c9users.io/cohort/'+this.state.cohort_slug+'?access_token='+token;
        fetch(endpoint)
        .then((response)=>{
            return response.json();
        })
        .then((data)=>{
            const profile_id = data.data.profile_id;
            this.setState({
                data_cohort: data.data
            })
            this.getProfile(profile_id, token);
            console.log(data, 'data Cohort')
        })
    }

    getProfile(profile_id, token){
        const endpoint = 'https://talenttree-alesanchezr.c9users.io/profile/'+profile_id+'?access_token='+token;
        fetch(endpoint)
        .then((response)=>{
            return response.json();
        })
        .then((data)=>{
            this.setState({
                data_profile: data.data
            })
            console.log(data, 'Profile')
        })
    }

    handleCreatePDF(){
        const input = document.getElementById('certificate');
        html2canvas(input)
        .then((canvas) => {
        let imgData = canvas.toDataURL('image/jpeg', 1.0)
        let pdf = new jsPDF('l', 'mm', 'letter');
        pdf.addImage(imgData, 'JPEG', 0, 0, 280, 205);
        pdf.save("download.pdf");
        });

    }

    render(){
        return(
            <div>
                {/* Aqui chequeamos si el estudiante se graduo para renderizar*/}
                {(this.state.studies_finished) ? 
                    <div>
                    <div id="certificate">
                        <div className="bg-black">
                            <Header/>
                        </div>
                            <NameStudent 
                                dataStudent={this.state.data_student}
                                dataProfile={this.state.data_profile}
                                dataCohort={this.state.data_cohort}/>
                    </div>
                        <button type="button" className="btn btn-primary ml-4 mb-4" onClick={(e)=>this.handleCreatePDF(e)}>Download PDF</button>
                    </div>
                    : <h1>There was an error with the student</h1>}
            </div>
        )
    }
}