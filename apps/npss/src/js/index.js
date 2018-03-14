//import react into the bundle
import React from 'react';
import ReactDOM from 'react-dom';

//include jquery into the bundle and store its contents into the $ variable
import $ from "jquery";
//include bootstrap npm library into the bundle
import 'bootstrap';

//include your index.scss file into the bundle
import '../styles/index.scss';

//import your own components
import Layout from './Layout.jsx';

import BreatheCodeAPI from './utils/BreatheCodeAPI';
BreatheCodeAPI.init(process.env.BC_HOST,process.env.BC_TOKEN);
import NPSApi from './utils/NPSApi';
NPSApi.init(process.env.ASSETS_HOST,process.env.ASSETS_TOKEN);

//render your react application
ReactDOM.render(
    <Layout />,
    document.querySelector('#app')
);