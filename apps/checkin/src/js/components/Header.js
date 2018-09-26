import React from 'React';

import rigoImage from '../../img/logo.png';

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
                            <img src={rigoImage} width="30" height="30" className="d-inline-block align-top mr-2" alt="" />
                            Event Checkin - BreatheCode
                        </a>
                        {(this.props.backToHome) ?
                            <div className="ml-auto">
                                <a href="/">
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