Rigorix.service "RigorixUI", ["$modal", ($modal)->

  @loader = angular.element '.rigorix-loading'

  updateLoader: (percentage)=>
    @loader.find(".progress-bar").css "width", percentage+"%"

]