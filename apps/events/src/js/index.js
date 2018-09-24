import React from 'react'
import ReactDOM from 'react-dom'
import 'bootstrap/scss/bootstrap.scss';

// Import app styles
require('../style/style.scss');

// Import component
import App from './App'

ReactDOM.render(<App />, document.getElementById('app'))