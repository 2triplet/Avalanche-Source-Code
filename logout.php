<?php
require $_SERVER['DOCUMENT_ROOT'].'/api/private/core.php';
if(isset($_COOKIE['avalanche_session'])) {
    session::destroySession($_COOKIE['avalanche_session']);
    session::invalidateSession($_COOKIE['avalanche_session']);
}
header("Location: /");
exit;