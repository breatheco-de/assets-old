//import react into the bundle
import React from 'react';
import ReactDOM from 'react-dom';
import queryString from 'query-string';

//include your index.scss file into the bundle
import '../styles/index.scss';
//import your own components
import Layout from './Layout.jsx';

import NPSApi from './utils/NPSApi';

const parsed = queryString.parse(location.search);
if(typeof parsed.access_token == 'undefined'){
    const app = document.querySelector('#app').innerHTML = `
        <div class='alert alert-danger'>This view could not be displayed because of a configuration issue: Missing API Token</div>`;
}
else{
    NPSApi.init(process.env.ASSETS_HOST,parsed.access_token);
    console.log('NPS ',process.env.MODE)
    
    //render your react application
    ReactDOM.render(
        <Layout />,
        document.querySelector('#app')
    );
}