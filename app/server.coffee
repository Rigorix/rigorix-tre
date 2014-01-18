express = require 'express'
http = require 'http'
path = require 'path'
fs = require 'fs'

app = express()

app.set "port", process.env.port

app.use express.static(path.join(__dirname, '/'))
app.use express.favicon()

# Routes
app.get "/", (req, res) ->
  res.render 'index', req

http.createServer(app).listen app.get("port"), ->
  console.log "Express server listening on port " + app.get("port")