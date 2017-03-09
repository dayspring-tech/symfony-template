var webpack = require('webpack');
var HtmlWebpackPlugin = require('html-webpack-plugin');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var helpers = require('./helpers');

module.exports = {
  entry: {
    'polyfills': './src/polyfills.ts',
    'vendor': './src/vendor.ts',
    'app': './src/main.ts'
  },

  resolve: {
    extensions: ['.ts', '.js']
  },

  module: {
    rules: [
      {
        test: /\.ts$/,
        loaders: ['awesome-typescript-loader', 'angular2-template-loader']
      },
      {
        test: /\.html$/,
        loader: 'html-loader'
      },
      {
        test: /\.(png|jpe?g|gif|svg|woff|woff2|ttf|eot|ico)$/,
        loader: 'file-loader?name=assets/[name].[hash].[ext]'
      },
      {
        test: /\.css$/,
        exclude: [
          /node_modules/,
          helpers.root('src', 'app')
        ],
        loader: ExtractTextPlugin.extract({
          fallbackLoader: 'style-loader', 
          loader: [
            {
              loader: 'css-loader',
              options: {sourceMap: true }
            }
          ]
        })
      },
      {
        test: /\.scss$/,
        exclude: [
          /node_modules/,
          helpers.root('src', 'app')
        ],
        loader: ExtractTextPlugin.extract({
          fallbackLoader: 'style-loader', 
          loader: [
            {
              loader: 'css-loader',
              options: {sourceMap: true }
            },
            {
              loader: 'sass-loader',
              options: {
                outputStyle: 'expanded',
                sourceMap: true 
              }
            }
          ]
        })
      },

      {
        test: /\.css$/,
        include: helpers.root('src', 'app'),
        loader: 'raw-loader'
      },
      {
        test: /\.scss$/,
        include: helpers.root('src', 'app'),
        loader: 'raw-loader!sass-loader'
      }
    ]
  },

  plugins: [
    new webpack.optimize.CommonsChunkPlugin({
      name: ['app', 'vendor', 'polyfills']
    }),

    new HtmlWebpackPlugin({
      template: 'src/index.html'
    }),

    new webpack.ContextReplacementPlugin(
      // The (\\|\/) piece accounts for path separators in *nix and Windows
      /angular(\\|\/)core(\\|\/)(esm(\\|\/)src|src)(\\|\/)linker/,
      './src',
      {} // a map of your routes 
    )
  ]
};
