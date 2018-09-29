const merge = require('webpack-merge');
const path = require('path');
const common = require('./webpack.common.js');
const Dotenv = require('dotenv-webpack');

module.exports = merge(common, {
    mode: 'production',
    plugins: [
        new Dotenv({
          path: './.env.prod', // load this now instead of the ones in '.env'
          safe: false, // load '.env.example' to verify the '.env' variables are all set. Can also be a string to a different file.
          systemvars: false, // load all the predefined 'process.env' variables which will trump anything local per dotenv specs.
          silent: false // hide any errors
        })
      ]
})