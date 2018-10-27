import React from 'react';

export default class Header extends React.Component{
    constructor(props){
        super(props);
    }

    render(){
        return(
            <div className="row full-width no-margin">
                <div className="col-md-12">    
                    <nav className="navbar navbar-light bg-light">
                        <a className="navbar-brand" href="#">
                            <img src="http://assets.breatheco.de/apis/img/images.php?blob&cat=icon&tags=breathecode" width="30" height="30" className="d-inline-block align-top mr-2" alt="" />
                            Event Checkin - BreatheCode
                        </a>
                        {(this.props.backToHome) ?
                            <div className="ml-auto">
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
                    </nav>
                </div>
            </div>
        )
    }
}