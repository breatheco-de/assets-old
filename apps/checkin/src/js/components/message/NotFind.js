import React from 'react';

export default class NotFind extends React.Component{
    constructor(props){
        super(props);
    }

    render(){
        return (
            <div className="col-md-12 warning pt-3 pb-3">
                <p className="text-center no-margin text-black">This is what we've found about this email</p>
            </div>
        )
    }
}