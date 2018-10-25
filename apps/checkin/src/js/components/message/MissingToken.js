import React from 'react';

export default class MissingToken extends React.Component{
    constructor(props){
        super(props);
    }

    render(){
        return (
            <div className="col-md-12 warning pt-3 pb-3">
                <p className="text-center no-margin text-black">You must insert the token by url, example (?assets_token=...)</p>
            </div>
        )
    }
}