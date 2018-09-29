var path = require('path');

var APP_DIR = path.resolve(__dirname, 'src/js');
var BUILD_DIR = path.resolve(__dirname, 'public');

module.exports = {
  output: {
    path: BUILD_DIR,
    filename: '[name].js'
  },
  entry: {
    index: APP_DIR + '/index.js',
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
  }
};
