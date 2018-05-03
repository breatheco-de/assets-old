import React from 'react';
import GetQuiz from './GetQuiz';
import ShowQuiz from './ShowQuiz';
import logo from '../../img/logo.png';

import 'jquery';

export default class App extends React.Component {
	constructor(props){
        super(props);
        this.state = {
            url: '',
            titleBanner: ''
        }
    }

    urlAPI(url){
        this.setState({ url });
    }

	render () {
		return (
            <div>
    			<nav className="navbar fixed-top navbar-light bg-light">
    				<a className="navbar-brand" href="#">
    					<img src="https://assets.breatheco.de/apis/img/images.php?blob&random&cat=icon&tags=breathecode,64" width="30" height="30" className="d-inline-block align-top mr-2" alt="" />
    					Quiz Maker - BreatheCode
    				</a>
    			</nav>
                {
                    (this.state.url) ? 
                        <ShowQuiz data={this.state.url}/>
                        : 
                        <GetQuiz onSelect={this.urlAPI.bind(this)}/>    
                }
            </div>
		);
	}
}
