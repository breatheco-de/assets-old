import React, { Component } from 'react';

import ImgIcon from './imgIcon';
import GeeksText from './4GeeksHeader';

import logo from '../image/logo.png'
import floridaDepartament from '../image/floridadepartment.png'

export default class Header extends Component{

    render(){
        return (
            <div className="container-header">
                <div className="row full-width no-margin pt-5 pb-5">
                    <div className="col-3 middle-center">
                        <GeeksText/>
                    </div>
                    <div className="col-6 text-center">
                        <ImgIcon class="logo" data={logo}/>
                    </div>
                    <div className="col-3 middle-center">
                        <ImgIcon class="w-100" data={floridaDepartament}/>
                    </div>
                </div>
            </div>
        )
    }
}
