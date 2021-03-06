const path = require('path')
const UglifyJSPlugin = require('uglifyjs-webpack-plugin')
const ExtractTextPlugin = require("extract-text-webpack-plugin")
const ManifestPlugin = require('webpack-manifest-plugin')
const CleanWebpackPlugin = require('clean-webpack-plugin')

const dev = process.env.NODE_ENV === "dev"

let cssLoaders = [
    { loader: 'css-loader', options: { importLoaders: 1, minimize: !dev } }
]

if(!dev) {
  cssLoaders.push({
          loader: 'postcss-loader',
          options: {
              plugins: (loader) => [
                  require('autoprefixer')({
                      browsers: ['last 2 versions', 'ie >= 8']
                  }),
              ]
          }
      }
  )}

let config = {
  entry: {
      app: [
          './resources/assets/front/sass/app.scss',
          './resources/assets/front/js/app.js'
      ],
      admin: [
          './resources/assets/admin/sass/admin.scss',
          './resources/assets/admin/js/admin.js'
      ]
  },
  watch: dev,
  output: {
    path: path.resolve('./public/dist'),
    filename: dev ? '[name.js]' : '[name].[chunkhash:8].js',
    publicPath: "/dist/"
  },
  resolve: {
      alias: {
          '@': path.resolve(__dirname, './resources/assets/'),
          '@vendor': path.resolve(__dirname, 'vendor/'),

          '@js': path.resolve(__dirname, './resources/assets/front/js'),
          '@css': path.resolve(__dirname, './resources/assets/front/css/'),
          '@sass': path.resolve(__dirname, './resources/assets/front/sass/'),
          '@images': path.resolve(__dirname, './resources/assets/front/images/'),
          '@font': path.resolve(__dirname, './resources/assets/front/fonts/'),

          '@adminjs': path.resolve(__dirname, './resources/assets/admin/js'),
          '@admicss': path.resolve(__dirname, './resources/assets/admin/css/'),
          '@admisass': path.resolve(__dirname, './resources/assets/admin/sass/'),
          '@admiimages': path.resolve(__dirname, './resources/assets/admin/images/'),
          '@admifont': path.resolve(__dirname, './resources/assets/admin/fonts/')

      }
  },
    devtool: dev ? "cheap-module-eval-source-map" : false,
    module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: ['babel-loader']
      },
      {
        test: /\.css$/,
        use: ExtractTextPlugin.extract({
              fallback: "style-loader",
              use: cssLoaders
          })
      },
      {
        test: /\.scss$/,
          use: ExtractTextPlugin.extract({
              fallback: "style-loader",
              use: [...cssLoaders, 'sass-loader']
          })
      },
      {
          test: /\.(woff|woff2|eot|eot|ttf|otf)(\?.*)?$/,
          loader: "file-loader"
      },
      {

          test: /\.(png|jpe?g|gif|svg)$/,
          use: [
              {
                  loader: 'url-loader',
                  options: {
                      limit: 8192,
                      name: '[name].[hash:8].[ext]'
                  }
              }
          ]
      }
    ]
  },
  plugins: [
    new ExtractTextPlugin({
        filename: dev ? '[name].css' : '[name].[contenthash:8].css',
        disable: dev
    })
  ]
}

if(!dev){
  config.plugins.push(new UglifyJSPlugin({
      sourceMap: false
  }))
  config.plugins.push(new ManifestPlugin())
  config.plugins.push(new CleanWebpackPlugin(['public/dist'], {
    root: path.resolve('./'),
    verbose: true,
    // dry: true,
    // exclude: ['shared']
  }))
}
module.exports = config