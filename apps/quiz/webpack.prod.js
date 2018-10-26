const merge = require('webpack-merge');
const path = require('path');
const common = require('./webpack.common.js');
const Dotenv = require('dotenv-webpack');

module.exports = merge(common, {
    output: {
        path: path.resolve(__dirname, 'public'),
        filename: '[name].js'
    },
    mode: 'development',
    devtool: 'source-map',
    plugins: [
        new Dotenv({
            path: './.env.prod',
            systemvars: true
        }),
    ]
});
