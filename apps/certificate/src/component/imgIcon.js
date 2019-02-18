import React, { Component } from 'react'

const ImgIcon = (props) => {
    console.log(props, 'Son los props:');
    return (
        <img className={props.class + ' text-center'} src={props.data}/>
    )
}

export default ImgIcon;