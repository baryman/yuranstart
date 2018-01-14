<?php if ($thisUser = $data['thisUser']): ?>
    <div class="auth">
        <span class="greetings">Здравствуйте,</span>
        <a href="<?php echo SITE ?>/personal" class="personal-link">
            <span class="user-icon"></span>
            <span class="user-name"><?php echo empty($thisUser->name) ? $thisUser->email : $thisUser->name ?></span>
        </a>
        <span class="slash">/</span>
        <a href="<?php echo SITE ?>/enter?logout=1">выход</a>
    </div>
<?php else: ?>
    <div class="auth">
        <div class="enter-on">
            <a href="javascript:void(0);" class="open-link"><span class="lock-icon"></span>Вход</a>
            <div class="enter-form">
                <form action="<?php echo SITE ?>/enter" method="POST">
                    <ul class="form-list">
                        <li><input type="text" name="email" placeholder="Email" value="<?php echo !empty($_POST['email']) ? $_POST['email'] : '' ?>"></li>
                        <li><input type="password" name="pass" placeholder="Пароль"></li>
                        <?php echo !empty($data['checkCapcha']) ? $data['checkCapcha'] : '' ?>
                    </ul>

                    <a href="<?php echo SITE ?>/forgotpass" class="forgot-link">Забыли пароль?</a>
                    <button type="submit" class="enter-btn default-btn">Войти</button>
                </form>
            </div>
        </div>
        <span class="slash">/</span>
        <span class="key-icon"></span>
        <a href="<?php echo SITE ?>/registration">Регистрация</a>
    </div>
<?php endif; ?>	  
