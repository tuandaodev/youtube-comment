<?php

echo "HTTP_REFERER: " . @$_SERVER['HTTP_REFERER'];
echo '<pre>';
print_r($_SERVER);
echo '</pre>';
exit;