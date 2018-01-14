<?php
/**
 *  Файл представления Feedback - выводит сгенерированную движком информацию на странице обратной связи.
 *  В этом файле доступны следующие данные:
 *   <code>
 *    $data['message'] => Сообщение,
 *    $data['dislpayForm'] => Флаг скрывающий форму,
 *    $data['meta_title'] => 'Значение meta тега для страницы '
 *    $data['meta_keywords'] => 'Значение meta_keywords тега для страницы '
 *    $data['meta_desc'] => 'Значение meta_desc тега для страницы '
 *   </code>
 *
 *   Получить подробную информацию о каждом элементе массива $data, можно вставив следующую строку кода в верстку файла.
 *   <code>
 *    <?php viewData($data['message']); ?>
 *   </code>
 *
 *   Вывести содержание элементов массива $data, можно вставив следующую строку кода в верстку файла.
 *   <code>
 *    <?php echo $data['message']; ?>
 *   </code>
 *
 *   <b>Внимание!</b> Файл предназначен только для форматированного вывода данных на страницу магазина. Категорически не рекомендуется выполнять в нем запросы к БД сайта или реализовывать сложную программную логику логику.
 * @author Авдеев Марк <mark-avdeev@mail.ru>
 * @package moguta.cms
 * @subpackage Views
 */

// Установка значений в метатеги title, keywords, description.
mgSEO($data);
?>

<div class="feedback-form-wrapper">
    <h1 class="title">Обратная связь</h1>

    <?php if (!empty($data['error'])): ?>
        <div class="msgError">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>

    <?php if ($data['dislpayForm']) { ?>
        <?php if (!empty($data['html_content']) && $data['html_content'] != '&nbsp;'):?>
            <div class="page-desc">
                <?php echo $data['html_content'] ?>
            </div>
        <?php endif; ?>
        <form action="" method="post" name="feedback">
            <div class="row clearfix">
                <div class="col">
                    <ul class="form-list">
                        <li><input type="text" name="fio" placeholder="Ф.И.О." value="<?php echo !empty($_POST['fio']) ? $_POST['fio'] : '' ?>"></li>
                        <li><input type="text" name="email" placeholder="Email" value="<?php echo !empty($_POST['email']) ? $_POST['email'] : '' ?>"></li>
                    </ul>
                </div>
                <div class="col">
                    <ul class="form-list">
                        <li><textarea class="address-area" placeholder="Сообщение" name="message"><?php echo !empty($_REQUEST['message']) ? $_REQUEST['message'] : '' ?></textarea>
                        </li>
                        <?php if (MG::getSetting('useCaptcha') == "true"): ?>
                            <li>Введите текст с картинки:</li>
                            <li><img src="captcha.html" width="140" height="36"></li>
                            <li><input type="text" name="capcha" class="captcha"></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php echo MG::addAgreementCheckbox('feedback-btn'); ?>
            <div class="form-buttons text-center">
                <input type="submit" name="send" class="default-btn feedback-btn" value="Отправить сообщение">
                <br>
                <a href="<?php echo SITE ?>/catalog" class="go-back">Вернутся к покупкам</a>
            </div>
        </form>
        <?php mgFormValid('feedback', 'feedback'); ?>
    <?php } else { ?>
        <div class='successSend'> <?php echo $data['message'] ?> </div>
    <?php }; ?>
</div>
