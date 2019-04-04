var publishTextValue, orig1, orig2, dv, panes = 2, highlight = true, connect = "align", collapse = false;

window.onload = function() 
{
  $('#previewDiv').hide();

  publishTextValue = document.getElementById('publishTextarea').value;
  revisionTextValue = document.getElementById('revisionTextarea').value;

  document.getElementById('publishText').innerHTML = publishTextValue;
  document.getElementById('revisionText').innerHTML = revisionTextValue;

  orig1=publishTextValue;
  orig2=revisionTextValue;

  initUI();
  let d = document.createElement("div"); d.style.cssText = "width: 50px; margin: 7px; height: 14px"; dv.editor().addLineWidget(57, d)

  $('#previewDivShow').click(function(){
    $('#previewDiv').slideToggle();    
  })
};

function initUI() 
{
  if (publishTextValue == null) return;
  var target = document.getElementById("comparisonView");        
  target.innerHTML = "";

  dv = CodeMirror.MergeView(target, {
    value: publishTextValue,
    origLeft: panes == 3 ? orig1 : null,
    orig: orig2,
    lineNumbers: true,
    lineWrapping: true,
    viewportMargin: Infinity,
    revertButtons: true,
    allowEditingOriginals:true,
    mode: "xml",
    highlightDifferences: true,
    connect: 'align',
    collapseIdentical: false
  });
}

function toggleDifferences() 
{
  dv.setShowDifferences(highlight = !highlight);
}

function mergeViewHeight(mergeView) 
{
  function editorHeight(editor) 
  {
    if (!editor) 
      return 0;
    
    return editor.getScrollInfo().height;
  }
  return Math.max(editorHeight(mergeView.leftOriginal()),
    editorHeight(mergeView.editor()),
    editorHeight(mergeView.rightOriginal()));
}
function resize(mergeView) 
{
  var height = mergeViewHeight(mergeView);
  for(;;) {
    if (mergeView.leftOriginal())
      mergeView.leftOriginal().setSize(null, height);
    mergeView.editor().setSize(null, height);
    if (mergeView.rightOriginal())
      mergeView.rightOriginal().setSize(null, height);

    var newHeight = mergeViewHeight(mergeView);
    if (newHeight >= height) break;
    else height = newHeight;
  }
  mergeView.wrap.style.height = height + "px";
}

function assignDataToForm()
{
  updatedTextValue = dv.editor().getValue();
  document.getElementById('updatedText').value = updatedTextValue;
  console.log(updatedTextValue);
}