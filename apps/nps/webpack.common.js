const webpack = require('webpack');
const path = require('path');
const Dotenv = require('dotenv-webpack');
const HtmlWebpackPlugin = require('html-webpack-plugin');

module.exports = {
  entry: [
    './src/js/index.js', 
  ],
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, 'dist')
  },
  module: {
    rules: [
        { test: /\.jsx?$/, exclude: /node_modules/, loader: "babel-loader" },
        {
          test: /\.scss$/,
          use: [{
              loader: "style-loader" // creates style nodes from JS strings
          }, {
              loader: "css-loader" // translates CSS into CommonJS
          }, {
              loader: "sass-loader" // compiles Sass to CSS
          }]
        }, //css only files
        { 
          test: /\.(png|svg|jpg|gif)$/, use: {
            loader: 'file-loader',
            options: { name: '[name].[ext]' } 
          }
        }, //for images
        { test: /\.woff($|\?)|\.woff2($|\?)|\.ttf($|\?)|\.eot($|\?)|\.svg($|\?)/, use: ['file-loader'] } //for fonts
    ]
  },
  resolve: {
    extensions: ['*', '.js', '.jsx'],
  },
  plugins: [
    new webpack.HotModuleReplacementPlugin(),
    new Dotenv(),
    new HtmlWebpackPlugin({
        title: 'Student Survey',
        favicon: 'bc-icon.ico',
        template: 'template.html'
    })
  ]
};