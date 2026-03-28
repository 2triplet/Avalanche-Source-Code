<?php
require $_SERVER['DOCUMENT_ROOT'].'/api/private/core.php';
users::requireLogin();

pageBuilder::$pageConfig["title"] = "Home";
pageBuilder::buildHeader();
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Welcome, <?=htmlspecialchars(SESSION["userName"])?>!</h4>
                </div>
                <div class="card-body">
                    <p><strong>Currency:</strong> <?=SESSION["currency"]?> <?=SITE_CONFIG["site"]["currencyName"]?></p>
                    <p><strong>Status:</strong> <?=htmlspecialchars(SESSION["status"] ?? 'No status set')?></p>
                    <p><strong>Friend Requests:</strong> <?=SESSION["friendRequests"]?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Quick Actions</div>
                <div class="card-body">
                    <a href="/logout" class="btn btn-danger btn-block">Log Out</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php pageBuilder::buildFooter(); ?>