//import react into the bundle
import React from 'react';
import ReactDOM from 'react-dom';

//include your index.scss file into the bundle
import '../styles/index.scss';
//import your own components
import Layout from './Layout.jsx';

import NPSApi from './utils/NPSApi';
NPSApi.init(process.env.ASSETS_HOST,process.env.ASSETS_TOKEN);

//render your react application
ReactDOM.render(
    <Layout />,
    document.querySelector('#app')
);