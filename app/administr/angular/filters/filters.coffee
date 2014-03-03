RigorixAdmin.filter "locationToTitle", ->
  (input)->
    title = input.split("/").join("")
    title.substring(0,1).toUpperCase()+title.substring(1)


RigorixAdmin.filter "logFileToPath", ->
  (input)->
    input.split(" ").join("_") + "_log.txt"