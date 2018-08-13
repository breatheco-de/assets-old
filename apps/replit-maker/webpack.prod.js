const merge = require('webpack-merge');
const common = require('./webpack.common.js');
const Dotenv = require('dotenv-webpack');

module.exports = merge(common, {
  plugins: [
    new Dotenv({
        path: './.env.prod',
        systemvars: true
    }),
  ]
})