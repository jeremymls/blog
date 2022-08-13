<?php
if(! isset($_SESSION)){
    session_start();
}
require_once 'vendor/autoload.php';
define('ROOT', __DIR__);
require_once 'src/config/routes.php';
?>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-BZ4S14M78H"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-BZ4S14M78H');
</script>