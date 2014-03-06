Rigorix.directive "imageuploader", ($q) ->
  URL = window.URL or window.webkitURL

  getResizeArea = ->
    resizeAreaId = "fileupload-resize-area"
    resizeArea = document.getElementById(resizeAreaId)
    unless resizeArea
      resizeArea = document.createElement("canvas")
      resizeArea.id = resizeAreaId
      resizeArea.style.visibility = "hidden"
      document.body.appendChild resizeArea
    resizeArea

  resizeImage = (origImage, options) ->
    maxHeight = options.resizeMaxHeight or 300
    maxWidth = options.resizeMaxWidth or 250
    quality = options.resizeQuality or 0.7
    type = options.resizeType or "image/jpg"
    canvas = getResizeArea()
    height = origImage.height
    width = origImage.width

    # calculate the width and height, constraining the proportions
    if width > height
      if width > maxWidth
        height = Math.round(height *= maxWidth / width)
        width = maxWidth
    else
      if height > maxHeight
        width = Math.round(width *= maxHeight / height)
        height = maxHeight
    canvas.width = width
    canvas.height = height

    #draw image on canvas
    ctx = canvas.getContext("2d")
    ctx.drawImage origImage, 0, 0, width, height

    # get the data from canvas as 70% jpg (or specified type).
    canvas.toDataURL type, quality

  createImage = (url, callback) ->
    image = new Image()
    image.onload = ->
      callback image

    image.src = url

  fileToDataURL = (file) ->
    deferred = $q.defer()
    reader = new FileReader()
    reader.onload = (e) ->
      deferred.resolve e.target.result

    reader.readAsDataURL file
    deferred.promise

restrict: "A"
scope:
  image: "="
  resizeMaxHeight: "@?"
  resizeMaxWidth: "@?"
  resizeQuality: "@?"
  resizeType: "@?"

link: postLink = (scope, element, attrs, ctrl) ->
  doResizing = (imageResult, callback) ->
    createImage imageResult.url, (image) ->
      dataURL = resizeImage(image, scope)
      imageResult.resized =
        dataURL: dataURL
        type: dataURL.match(/:(.+\/.+);/)[1]

      callback imageResult

  applyScope = (imageResult) ->
    scope.$apply ->

      #console.log(imageResult);
      if attrs.multiple
        scope.image.push imageResult
      else
        scope.image = imageResult

  element.bind "change", (evt) ->

    #when multiple always return an array of images
    scope.image = []  if attrs.multiple
    files = evt.target.files
    i = 0

    while i < files.length

      #create a result object for each file in files
      imageResult =
        file: files[i]
        url: URL.createObjectURL(files[i])

      fileToDataURL(files[i]).then (dataURL) ->
        imageResult.dataURL = dataURL

      if scope.resizeMaxHeight or scope.resizeMaxWidth #resize image
        doResizing imageResult, (imageResult) ->
          applyScope imageResult

      else #no resizing
        applyScope imageResult
      i++