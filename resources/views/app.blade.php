<!DOCTYPE html>
<html>
<head>
  @include('sections.head')
</head>

<body class="hold-transition sidebar-mini">
  
  <div class="wrapper" id="app">
    <App ruta="{{ route('basepath') }}"></App>
  </div>
  <!-- ./wrapper -->

  @include('sections.script')
</body>
</html>