<?php
  $PAGE_TITLE = "A Posteriori Files";
  $ADDITIONAL_STYLESHEETS = '';
  $ADDITIONAL_SCRIPTS = '';
  include '../protected/top.php';
?>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-info sticky-top">
  <div class="navbar-brand mr-auto classDescription"></div>

  <div class="navbar-brand mr-auto getLink">Get Link</div>

  <ul class="navbar-nav">
    <li class="nav-item" id="usernameDisplay"><span></span>&nbsp;&nbsp;<i class="fas fa-edit"></i></li>
  </ul>
</nav>

<main class="class classMedia">
  <div class="leftCol">
    <div class="teachers">
      <h2>Teacher's Files</h2>
      <div class="teachersFiles"></div>
    </div>
    <div class="students">
      <h2>Students' Files</h2>
      <div class="studentsFiles"></div>
    </div>
  </div>
  <div class="rightCol">
    <div id="dropZone" class="dropArea">
      <div class="progressArea"></div>
      <div class="message">Drop files here<br>Max 50MB</div>
      <div class="input">
        <label for="upload">Select files</label>
        <input type="file" id="upload" multiple>
      </div>
    </div>
  </div>
</main>

<script src="js/page/mclass.js?v=a8e7d894"></script>
<script src="js/page/class-ws.js?v=b8543ae2"></script>

</body>
</html>
