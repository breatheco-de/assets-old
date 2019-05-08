import React from 'react';
import {tokens} from '../action';

export default class BannerHeader extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            messageSuccess: null,
            messageFailure: null
        };
    }

    handleStructureJson(filename){
        let fileJson = {
            cohortSlug: this.props.createJson[0],
            cohortBody: this.props.createJson[1]
        };
        console.log(fileJson);
        const text = JSON.stringify(fileJson);
		var element = document.createElement('a');
		element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
		element.setAttribute('download', filename);
		element.style.display = 'none';
		document.body.appendChild(element);
		element.click();
        document.body.removeChild(element);
    }

    postApi(){
        let slug = this.props.createJson[0];
        let url = process.env.ASSETS_HOST+'/apis/replit/cohort/'+slug+'?access_token='+tokens().assetsToken;
        fetch(url, {
            headers: {"content-type": "application/json"},
            method: 'POST',
            body: JSON.stringify(this.props.createJson[1])
        })
        .then(resp => {
            if(resp.status!=200){
                this.setState({
                    messageFailure: 'The was an error saving the replits'
                });
                setTimeout(()=>{
                    this.setState({
                        messageFailure: null
                    });
                }, 2000);
                throw new Error('The was an error saving the replits');
            }
            else return resp.json();
        })
        .then((data)=> {
            // console.log('Request success: ', data);
            this.setState({
                messageSuccess: 'The replits were save successfully'
            });
            setTimeout(()=>{
                this.setState({
                    messageSuccess: null
                });
            }, 2000);
        })
        .catch((error)=> {
            // console.log('Request failure: ', error);
            this.setState({
                messageFailure: 'The was an error saving the replits'
            });
            setTimeout(()=>{
                this.setState({
                    messageFailure: null
                });
            }, 2000);
        });
    }

    render(){
        return (
            <div className="row">
                <div className="col-12">
                    {
                        (this.state.messageSuccess) ?
                        <div className="alert alert-success text-center mb-0">{this.state.messageSuccess}</div>
                        :
                        ''
                    }
                    {
                        (this.state.messageFailure) ?
                        <div className="alert alert-danger text-center mb-0">{this.state.messageFailure}</div>
                        :
                        ''
                    }
                    <nav className="navbar navbar-light bg-light">
                        <a className="navbar-brand" href="#">
                            <img src="https://assets.breatheco.de/apis/img/images.php?blob&random&cat=icon&tags=breathecode,64" width="30" height="30" className="d-inline-block align-top mr-2" alt="" />
                            Cohort Maker - BreatheCode
                        </a>
                        {(this.props.button) ?
                            <div>
                            <button type="button"
                                    className="btn btn-primary float-right"
                                    onClick={()=>this.postApi()}>save</button>
                            <button
                                type="button"
                                className="btn btn-primary float-right margin-btn"
                                onClick={()=>this.handleStructureJson(this.props.createJson[0])}>
                            download progress</button>
                            </div>
                            : <span></span>
                        }
                    </nav>
                </div>
            </div>
        );
    }
}
