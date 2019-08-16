window.iframeToClipboard = (iframeId) => {

  // Get the HTML content of the iframe
  var iFrame = document.getElementById(iframeId);

  // Check if the iframe was found
  if( iFrame === null ) {
    console.error('iFrame not found!');
    return;
  };

  var iFrameDocument = iFrame.contentDocument || iFrame.contentWindow.document;
  var serializer = new XMLSerializer();
  var content = serializer.serializeToString(iFrameDocument);

  // Create an auxiliary hidden input
  var aux = document.createElement("input");

  // Get the text from the element passed into the input
  aux.setAttribute("value", content);

  // Append the aux input to the body
  document.body.appendChild(aux);

  // Highlight the content
  aux.select();

  // Execute the copy command
  document.execCommand("copy");

  // Remove the input from the body
  document.body.removeChild(aux);
}
