Terminal = {
    serviceUrl: "service.php"
    collector: []
    collectorPointer: 0
    console: $("#jsterminal-code")
    commandLine: $("#jsterminal-line")

    init: () ->
        this.commandLine
            .on("keydown", (e) ->
                Terminal.keyboardMap.execute.call(this, e)
            )
}            