Rigorix.filter "capitalize", ->
  (input, scope) ->
    input.substring(0,1).toUpperCase()+input.substring(1)

Rigorix.filter "varToTitle", ->
  (input, scope) ->
    input = input.split("_").join(" ")
    input.substring(0,1).toUpperCase()+input.substring(1)

Rigorix.filter "stringToDate", ->
  (input, scope) ->
    date = new Date(input)
    date
