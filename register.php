<?php
require $_SERVER['DOCUMENT_ROOT'].'/api/private/core.php';
users::requireLoggedOut();

$errors = ["username" => false, "password" => false, "confirmpassword" => false];
$username = $password = $confirmpassword = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmpassword = isset($_POST['confirmpassword']) ? $_POST['confirmpassword'] : '';
    
    // Validation
    if(!$username) {
        $errors["username"] = "Please enter a username";
    } elseif(strlen($username) < 3 || strlen($username) > 20) {
        $errors["username"] = "Your username can only be 3 - 20 characters long";
    } elseif(preg_match('/[^A-Za-z0-9]/', $username)) {
        $errors["username"] = "Your username can only contain alphanumeric characters";
    } else {
        $query = $pdo->prepare("SELECT COUNT(*) FROM users WHERE lower(username) = lower(:name)");
        $query->bindParam(":name", $username, PDO::PARAM_STR);
        $query->execute();
        if($query->fetchColumn()) {
            $errors["username"] = "Someone already has that username! Try choosing a different one.";
        }
    }
    
    if(!$password) {
        $errors["password"] = "Please enter a password";
    } elseif(strlen(preg_replace('/[^0-9]/', "", $password)) < 2) {
        $errors["password"] = "Your password is too weak. Make sure it contains at least two numbers";
    } elseif(strlen(preg_replace('/[0-9]/', "", $password)) < 6) {
        $errors["password"] = "Your password is too weak. Make sure it contains at least six non-numeric characters";
    }
    
    if($password != $confirmpassword) {
        $errors["confirmpassword"] = "Passwords do not match";
    }
    
    // Removed regpass check - was blocking all registrations!
    
    if(!$errors["username"] && !$errors["password"] && !$errors["confirmpassword"]) {
        $pwhash = password_hash($password, PASSWORD_BCRYPT);
        $ip = $_SERVER["REMOTE_ADDR"];
        // FIXED: Added all required columns for SESSION
        $query = $pdo->prepare("INSERT INTO users (username, password, email, jointime, lastonline, regip, status, currency, nextCurrencyStipend, adminlevel, filter, pageanim) VALUES (:username, :password, 'placeholder', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :ip, 'I\'m new to avalanche!', 0, UNIX_TIMESTAMP(), 0, 1, 1)");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->bindParam(":password", $pwhash, PDO::PARAM_STR);
        $query->bindParam(":ip", $ip, PDO::PARAM_STR);
        if($query->execute()) {
            $query = $pdo->prepare("SELECT id FROM users WHERE username = :username");
            $query->bindParam(":username", $username, PDO::PARAM_STR);
            $query->execute();
            session::createSession($query->fetchColumn());
            header("Location: /home");  // FIXED: redirect to /home
            exit;
        } else {
            die("An unexpected error occured! We're sorry.");
        }
    }
}

pageBuilder::buildHeader();
?>
<h1 class="text-center"> Sign up </h1>
<div class="row mt-5">
    <div class="col-sm-8 divider-right align-self-center">
        <form method="post">
            <div class="form-group row">
                <label for="username" class="col-4 col-form-label">Username: </label>
                <div class="col-6">
                    <input type="text" class="form-control<?=$_SERVER["REQUEST_METHOD"] == "POST" && $errors["username"] ? ' is-invalid' : ' is-valid'?>" name="username" id="username" value="<?=htmlspecialchars($username)?>" autocomplete="username">
                    <p class="invalid-feedback username-err"<?=$errors["username"]?' style="display:block"':''?>><?=$errors["username"]?></p>
                    <small class="form-text text-muted">3 - 20 alphanumeric characters, no spaces or underscores.</small>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-4 col-form-label">Password: </label>
                <div class="col-6">
                    <input type="password" class="form-control<?=$_SERVER["REQUEST_METHOD"] == "POST" && $errors["password"] ? ' is-invalid' : ' is-valid'?>" name="password" id="password" autocomplete="new-password">
                    <p class="invalid-feedback password-err"<?=$errors["password"]?' style="display:block"':''?>><?=$errors["password"]?></p>
                    <small class="form-text text-muted">8 - 64 characters, must have at least 6 characters and 2 numbers</small>
                </div>
            </div>
            <div class="form-group row">
                <label for="confirmpassword" class="col-4 col-form-label">Confirm Password: </label>
                <div class="col-6">
                    <input type="password" class="form-control<?=$_SERVER["REQUEST_METHOD"] == "POST" && $errors["confirmpassword"] ? ' is-invalid' : ' is-valid'?>" name="confirmpassword" id="confirmpassword">
                    <p class="invalid-feedback confirmpassword-err"<?=$errors["confirmpassword"]?' style="display:block"':''?>><?=$errors["confirmpassword"]?></p>
                </div>
            </div>
            <!-- Removed regpass field -->
            <button type="submit" class="btn btn-lg btn-success mx-auto d-block">Sign Up</button>
        </form>
    </div>
    <div class="col-sm-4 p-0">
        <div class="pl-3 pb-3">
            Already registered? <a class="btn btn-light my-1 mx-2 px-3" href="/login">Login</a>
        </div>
        <div class="divider-top"></div>
        <div class="pl-3 pt-3">
            By clicking Sign Up, you agree to our <a href="/info/rules">rules</a> and <a href="/info/privacy">privacy policy</a>.
        </div>
    </div>
</div>
<?php pageBuilder::buildFooter(); ?>