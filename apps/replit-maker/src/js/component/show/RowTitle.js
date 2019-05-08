import React from 'react';

export default class RowTitle extends React.Component{
    constructor(props){
        super(props);
    }

    render(){
        return (
            <div className="container-fluid p-0">
                <div className="row">
                    <div className="col-12">
                        <nav className="navbar navbar-dark bg-dark">
                            <a className="navbar-brand" href="#">
                              {this.props.title}  
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        );
    }
}
