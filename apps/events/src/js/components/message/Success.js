import React from 'react';

export default class Success extends React.Component{
    constructor(props){
        super(props);
    }

    render(){
        return (
            <div className="col-md-12 success pt-3 pb-3">
                <p className="text-center no-margin text-black">Great! The user was found on BreatheCode and it was successfully check in</p>
            </div>
        )
    }
}