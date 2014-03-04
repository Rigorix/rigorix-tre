Rigorix.filter "capitalize", ->
  (input, scope) ->
    input.substring(0,1).toUpperCase()+input.substring(1)

Rigorix.filter "varToTitle", ->
  (input) ->
    input = input.split("_").join(" ") if input.split("_").length > 1
    input.substring(0,1).toUpperCase()+input.substring(1)

Rigorix.filter "stringToDate", ->
  (input) ->
    moment(input)._d

Rigorix.filter "formatStringDate", ->
  (input) ->
    moment(input).format "Do MMM YYYY"

Rigorix.filter "length", ->
  (input) ->
    if input?
      if input.length? then input.length else Object.keys(input).length

Rigorix.filter "htmlToText", ->
  (input)->
    input = input.replace /<\/?[^>]+(>|$)/g, ""