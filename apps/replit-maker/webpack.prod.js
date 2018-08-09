const merge = require('webpack-merge');
const path = require('path');
const common = require('./webpack.common.js');
const Dotenv = require('dotenv-webpack');

module.exports = merge(common, {
  devtool: "source-map",
  devServer: {
    contentBase: path.resolve(__dirname, 'dist'),
    hot: true,
    disableHostCheck: true,
    historyApiFallback: true
  },
  plugins: [
    new Dotenv({
        path: './.env',
        systemvars: true
    }),
  ]
})