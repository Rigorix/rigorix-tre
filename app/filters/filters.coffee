Rigorix.filter "capitalize", ->
  (input, scope) ->
    input.substring(0,1).toUpperCase()+input.substring(1)

Rigorix.filter "varToTitle", ->
  (input) ->
    input = input.split("_").join(" ") if input.split("_").length > 1
    input.substring(0,1).toUpperCase()+input.substring(1)

Rigorix.filter "stringToDate", ->
  (input) ->
    date = new Date(input)
    date

Rigorix.filter "formatStringDate", ->
  (input) ->
    moment(input).format "Do MMM YYYY"

Rigorix.filter "localizeMonth", ->
  months =
    Jan: 'Gen'
    Feb: 'Feb'
    Mar: 'Mar'
    Apr: 'Apr'
    May: 'Mag'
    Jun: 'Giu'
    Jul: 'Lug'
    Aug: 'Ago'
    Sep: 'Set'
    Oct: 'Ott'
    Nov: 'Nov'
    Dec: 'Dic'
  (input) ->
    months[input]

Rigorix.filter "length", ->
  (input) ->
    if input?
      if input.length? then input.length else Object.keys(input).length