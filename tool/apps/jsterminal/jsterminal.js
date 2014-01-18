var Terminal = {
    serviceUrl: "service.php",
    collector: [],
    console: $("#jsterminal-code"),
    commandLine: $("#jsterminal-line"),

    init: function () {
        this.commandLine
            .on("keydown", function (e) {
                var charCode = e.which || e.keyCode;
                if (charCode == 13) // ENTER
                    Terminal.sendCommand ();
                if (charCode == 9) {    // TAB
                    e.preventDefault()
                    Terminal.completeCommand ();
                }
            })
            .on("keyup", function () {
                Terminal.checkCommandIntegrity();
            })
            .val ("> ")
            .focus();
    },

    sendCommand: function () {
        var command = this.getCommand();
        this.commandLine.attr("disabled", "disabled");
        this.collector.push( command );
        this.executeCommand ( command );
        this.resetCommandLine ();
    },

    executeCommand: function ( lineCommand ) {
        this.commandLine.attr("disabled", null);
        var commandObject = this.getCommandObject ( lineCommand );
        if ( this.commands[commandObject.command] ) {
            var executeObj = this.commands[commandObject.command];

            for ( var i=0; i<commandObject.params.length; i++) {
                if ( typeof executeObj[commandObject.params[i]] == "object")
                    executeObj = executeObj[commandObject.params[i]];
                else {
                    console.log ("here")
                    this.commands.system.unknownParam (commandObject.command, commandObject.params[i]);
                    this.resetCommandLine();
                    break;
                }
            }
            executeObj.execute.call (this);
        } else
            this.commands.system.unknownCommand ();
    },

    completeCommand: function () {
        var command = this.findCommand ();
        this.commandLine.val( "> " + command );
    },

    findCommand: function () {
        var returnCommand = "";
        var availableCommands = this.commands;
        $(this.getCommand().split(" ")).each( function (i, lineCommand) {
            for ( var commandName in availableCommands ) {
                if ( commandName.indexOf ( lineCommand ) == 0 && commandName != lineCommand ) {
                    returnCommand += commandName;
                    availableCommands = commandName;
                    break;
                }
            }
        })

        return returnCommand;
    },

    getCommand: function () {
        return this.commandLine.val().substring(2, this.commandLine.val().length);
    },

    resetCommandLine: function () {
        this.commandLine.val ("> ");
    },

    checkCommandIntegrity: function () {
        var command = this.commandLine.val();
        if ( command.indexOf("> ") != 0 )
            this.commandLine.val ( "> " + $.trim(command) );
    },

    getCommandObject: function ( command ) {
        var commandParameters = command.split (" ");
        return {
            "command": commandParameters.splice(0, 1),
            "params": commandParameters
        }
    },

    getLastCommand: function () {
        var commandParameters = this.collector[this.collector.length-1].split (" ");
        return {
            "command": commandParameters.splice(0, 1),
            "params": commandParameters
        }
    },

    setPosition: function (position) {
        alert ("new position: " + position)
    },

    systemExec: function () {
        $.get(this.serviceUrl + "?sysCommand=" + this.getLastCommand().command + "&sysParameters=" + this.getLastCommand().params, function (data) {
            Terminal.console.append ( $("<div>" + data + "</div>") );
        })
    },

    systemExecGet: function (callback) {
        $.get(this.serviceUrl + "?sysCommand=" + this.getLastCommand().command + "&sysParameters=" + this.getLastCommand().params, function (data) {
            console.log ( data)
//            callback(data);
        })
    }

};

Terminal.commands = {

    "cd": {
        "..": {
            "execute": function () {
                Terminal.systemExecGet (function (data) {
                    Terminal.setPosition (data.path);
                })
            }
        }
    },
    "clear": {
        execute: function () {
            this.console.html("");
        }
    },
    "cls": {
        execute: function () {
            this.console.html("");
        }
    },
    "ls": {
        "execute": Terminal.systemExec
    },
    "lssecond": {
        "execute": function () {
            alert ( "list")
        }
    },



    "system": {
        "unknownCommand": function () {
            var command = Terminal.getLastCommand();
            Terminal.console.append ( $("<div>Unknown command '"+command.command+"' or wrong parameters!</div>") );
        },
        "unknownParam": function (command, param) {
            Terminal.console.append ( $("<div>Unknown param '"+param+"' for command '"+command+"'!</div>") );
        }
    }

}

Terminal.init ();