<?php
/**
 *  Файл представления Registration - выводит сгенерированную движком информацию на странице регистрации нового пользователя.
 *  В этом файле доступны следующие данные:
 *   <code>
 *    $data['error'] => Сообщение об ошибке.
 *    $data['message'] => Информационное сообщение.
 *    $data['form'] =>  Отображение формы,
 *    $data['meta_title'] => 'Значение meta тега для страницы '
 *    $data['meta_keywords'] => 'Значение meta_keywords тега для страницы '
 *    $data['meta_desc'] => 'Значение meta_desc тега для страницы '
 *   </code>
 *
 *   Получить подробную информацию о каждом элементе массива $data, можно вставив следующую строку кода в верстку файла.
 *   <code>
 *    <php viewData($data['message']); ?>
 *   </code>
 *
 *   Вывести содержание элементов массива $data, можно вставив следующую строку кода в верстку файла.
 *   <code>
 *    <php echo $data['message']; ?>
 *   </code>
 *
 *   <b>Внимание!</b> Файл предназначен только для форматированного вывода данных на страницу магазина. Категорически не рекомендуется выполнять в нем запросы к БД сайта или реализовывать сложную программную логику логику.
 * @author Авдеев Марк <mark-avdeev@mail.ru>
 * @package moguta.cms
 * @subpackage Views
 */
// Установка значений в метатеги title, keywords, description

mgSEO($data);
?>
<script type="text/javascript" src="<?php echo SITE ?>/mg-core/script/jquery.maskedinput.min.js"></script>

<?php if ($data['form']){?>
    <div class="create-user-account-form">
        <div class="title">Новый пользователь</div>
        <?php if ($data['message']): ?>
            <div class="mg-success"><?php echo $data['message'] ?></div>
        <?php endif; ?>
        <?php if ($data['error']): ?>
            <div class="msgError"><?php echo $data['error'] ?></div>
        <?php endif; ?>
        <p class="custom-text">Заполните форму ниже, чтобы получить дополнительные возможности в нашем
            интерент-магазине.</p>
        <form action="<?php echo SITE ?>/registration" method="POST">
            <ul class="form-list">
                <li><input type="text" name="email" placeholder="Email" value="<?php echo $_POST['email'] ?>"></li>
                <li><input type="password" placeholder="Пароль" name="pass"></li>
                <li><input type="password" placeholder="Подтвердите пароль" name="pass2"></li>
                <li><input type="text" name="name" placeholder="Имя" value="<?php echo $_POST['name'] ?>"></li>
                <li><input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR'] ?>"></li>
                <?php if (MG::getSetting('useCaptcha') == "true"):?>
                    <li>Введите текст с картинки:</li>
                    <li><img
                            style="margin-top: 5px; border: 1px solid gray; background: url('<?php echo PATH_TEMPLATE ?>/images/cap.png');"
                            src="captcha.html" width="140" height="36"></li>
                    <li><input type="text" name="capcha" class="captcha"></li>
                <?php endif; ?>
            </ul>
            <?php echo MG::addAgreementCheckbox('register-btn'); ?>
            <div class="form-buttons text-center">
                <button type="submit" name="registration" class="register-btn default-btn">Зарегистрироваться</button>
            </div>
        </form>
    </div>
 <?php } else { ?>

    <?php if ($data['message']): ?>
            <div class="mg-success"><?php echo $data['message'] ?></div>
    <?php endif; ?>

 <?php } ?>
