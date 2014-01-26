Rigorix.filter "capitalize", ->
  (input, scope) ->
    input.substring(0,1).toUpperCase()+input.substring(1)

Rigorix.filter "varToTitle", ->
  (input) ->
    input = input.split("_").join(" ")
    input.substring(0,1).toUpperCase()+input.substring(1)

Rigorix.filter "stringToDate", ->
  (input) ->
    date = new Date(input)
    date

Rigorix.filter "formatStringDate", ->
  (input) ->
    moment(input).format "Do MMM YYYY"