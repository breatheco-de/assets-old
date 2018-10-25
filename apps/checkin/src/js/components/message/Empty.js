import React from 'react';

export default class Empty extends React.Component{
    constructor(props){
        super(props);
    }

    render(){
        return (
            <div className="col-md-12 danger pt-3 pb-3">
                <p className="text-center no-margin text-black">It cant be empty</p>
            </div>
        )
    }
}