const merge = require('webpack-merge');
const common = require('./webpack.common.js');
const Dotenv = require('dotenv-webpack');

module.exports = merge(common, {
    mode: 'development',
    devtool: 'source-map',
    plugins: [
        new Dotenv({
            path: './.env.prod',
            systemvars: true
        }),
    ]
});
