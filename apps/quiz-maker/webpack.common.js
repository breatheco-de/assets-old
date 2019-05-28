var path = require('path');
const Dotenv = require('dotenv-webpack');

module.exports = {
  entry: {
    index: path.resolve(__dirname, 'src/js/index.js'),
  },
  output: {
      path: path.resolve(__dirname, 'public'),
      filename: '[name].js'
  },
  optimization: {
    splitChunks: {
        cacheGroups: {
            commons: {
                test: /[\\/]node_modules[\\/]/,
                name: 'vendor',
                chunks: 'all'
            }
        }
    }
  },
  module: {
  	rules: [
      	{
      		test: /\.js$/,
      		exclude: /(node_modules)/,
    			use: {
    				loader: 'babel-loader'
    			}
    	},
      {
        test: /\.scss$/,
        use: [{
          loader: "style-loader" // creates style nodes from JS strings
        }, {
            loader: "css-loader" // translates CSS into CommonJS
        }, {
            loader: "sass-loader", // compiles Sass to CSS
        }]
      },
      {
        test: /\.css$/,
        use: [ 'style-loader', 'css-loader' ]
      },
        {
          test: /\.(png|svg|jpg|gif)$/, use: {
            loader: 'file-loader',
            options: { name: '[name].[ext]' }
          }
        } //for images
  	]
  },
    plugins: [
        new Dotenv({
            path: './.env',
            systemvars: true
        }),
    ]
//   devtool: 'source-map',
//   devServer: {
//     contentBase:  './dist',
//   }
};
