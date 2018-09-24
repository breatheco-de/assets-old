import React from 'react';

export default class UserRegistered extends React.Component{
    constructor(props){
        super(props);
    }

    render(){
        return (
            <div className="col-md-12 danger pt-3 pb-3">
                <p className="text-center no-margin text-black">This user has already been checked or something is wrong in the mail</p>
            </div>
        )
    }
}