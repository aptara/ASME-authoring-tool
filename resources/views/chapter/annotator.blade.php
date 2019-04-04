<html>
<head>
    <link rel="stylesheet" href="{{asset('js/annotator/annotator.min.css')}}"/>
</head>
<body>
<div id="content" style="margin:50px;">
       Add some content here...
   </div>
 <script   src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous"></script>
    <script src="{{asset('js/annotator/annotator-full.min.js')}}"></script>
      <script src="{{asset('js/annotator/annotator.store.min.js')}}"></script>
<script>
 $(function(){
      var annotation =$("#content").annotator();
    annotation.annotator('addPlugin', 'Store', {
    prefix: '/annotation',
    loadFromSearch : {
        page : 1
    },
    annotationData : {
        page : 1
    },
    urls: {
        create:  '/test/store',
        update:  '/test/update/:id',
        destroy: '/delete/:id',
        search:  '/test/search'
    }
  });

 });
</script>
</body>
</html>
