<?php
  $PAGE_TITLE = "A Posteriori Files Sharing";
  $ADDITIONAL_STYLESHEETS = '';
  $ADDITIONAL_SCRIPTS = '';
  include '../protected/teacherTop.php';
?>

<main class="container-fluid teacher">
  <h1>Classes</h1>
  <h2>How to use</h2>
  <ol>
    <li>Start by adding a class. This will create a link to the class page. This is for you only; don't share this link.</li>
    <li>In the class page, use the "Get Link" button to obtain a link that you can share with the class.</li>
    <li>Students will be asked for a name when they first visit the class page. They can use any name they want.</li>
    <li>Files uploaded by the teacher will be visible to all students immediately.</li>
    <li>Only teachers can see files uploaded by students, but the teacher can choose to share it with the rest of the class.</li>
    <li>Students can see their own uploaded files, but only on the same computer. If they change computer, browser, or clear browsing data, they will lose access to their own uploads unless the teacher share it.</li>
  </ol>

  <span class="addClass clickable"><i class="fa fa-plus-circle"></i>&nbsp;Add Class</span>

  <table class="classes">
    <tbody>
    </tbody>
  </table>
</main>

<script src="js/page/teacher.js"></script>

</body>
</html>
