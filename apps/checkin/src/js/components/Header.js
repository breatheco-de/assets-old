import React from 'react';

export default class Header extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            oldEvents: [],
            textBtnOldEvents: true
        }
    }

    showOldEvents(){
        this.props.sendOldEventsToApp(this.state.oldEvents);
        this.setState({textBtnOldEvents: !this.state.textBtnOldEvents})
    }

    searchOldEvent(){
        const endpoint = process.env.BREATHECODE+'all?status=past';
        fetch(endpoint)
        .then((response)=>{
            return response.json();
        })
        .then((data)=>{
            this.setState({
                oldEvents: data
            })
        })
        .catch((error)=>{
            console.log(error);
        })
    }

    componentDidMount(){
        this.searchOldEvent();
    }

    render(){
        return(
            <div className="row full-width no-margin">
                <div className="col-md-12 no-padding">    
                    <nav className="navbar navbar-light bg-light">
                        <a className="navbar-brand" href="#">
                            <img src="http://assets.breatheco.de/apis/img/images.php?blob&cat=icon&tags=breathecode" width="30" height="30" className="d-inline-block align-top mr-2" alt="" />
                            Event Checkin - BreatheCode
                        </a>
                        <div className="ml-auto align-btn-header">

                        {(this.props.backToHome) ?
                            <div className="div-btn-header">
                                <a onClick={()=> window.location.reload()}>
                                    <button 
                                        type="button" 
                                        className="btn btn-primary"
                                        >Choose another event</button>
                                </a>
                            </div>
                        :
                        ''
                        }
                        {(this.props.btnSeeOldEvents) ?
                            <div>
                                <button 
                                    type="button" 
                                    className="btn btn-primary"
                                    onClick={()=> this.showOldEvents()}
                                    >{(this.state.textBtnOldEvents ? 'See Old Events' : 'Hidden Old Events')}</button>
                            </div>
                            : ''
                        }
                        </div>
                    </nav>
                </div>
            </div>
        )
    }
}