<?php
/**
 * Файл template.php является каркасом шаблона, содержит основную верстку шаблона.
 *
 *
 *   Получить подробную информацию о доступных данных в массиве $data, можно вставив следующую строку кода в верстку файла.
 *   <code>
 *    <?php viewData($data); ?>
 *   </code>
 *
 *   Также доступны вставки, для вывода верстки из папки layout
 *   <code>
 *      <?php layout('cart'); ?>      // корзина
 *      <?php layout('auth'); ?>      // личный кабинет
 *      <?php layout('widget'); ?>    // виджиеы и коды счетчиков
 *      <?php layout('compare'); ?>   // информер товаров для сравнения
 *      <?php layout('content'); ?>   // содержание открытой страницы
 *      <?php layout('leftmenu'); ?>  // левое меню с категориями
 *      <?php layout('topmenu'); ?>   // верхнее горизонтаьное меню
 *      <?php layout('contacts'); ?>  // контакты в шапке
 *      <?php layout('search'); ?>    // форма для поиска
 *      <?php layout('content'); ?>   // вывод контента сгенерированного движком
 *   </code>
 * @author Авдеев Марк <mark-avdeev@mail.ru>
 * @package moguta.cms
 * @subpackage Views
 */
?>

<!DOCTYPE html>
<html>
<head>
    <?php mgMeta(); ?>
    <meta name="viewport" content="width=device-width">

    <?php mgAddMeta('<link href="' . PATH_SITE_TEMPLATE . '/css/owl.carousel.css" rel="stylesheet" type="text/css" />'); ?>
    <?php mgAddMeta('<link href="' . PATH_SITE_TEMPLATE . '/css/mobile.css" rel="stylesheet" type="text/css" />'); ?>
    <?php mgAddMeta('<script type="text/javascript" src="' . PATH_SITE_TEMPLATE . '/js/owl.carousel.js"></script>'); ?>
    <?php mgAddMeta('<script type="text/javascript" src="' . PATH_SITE_TEMPLATE . '/js/script.js"></script>'); ?>

</head>
<body <?php backgroundSite(); ?>>
  <?php if (class_exists('InfoNotice')): ?>
    [banner id=1]
  <?php endif; ?>  

<div class="wrapper <?php echo isIndex() ? 'main-page' : ''; echo isCatalog() && !isSearch() ? 'catalog-page' : ''; ?>">
    <!--Плагин прокрутки страницы-->
    <?php if (class_exists('ScrollTop')): ?>
        [scroll-top]
    <?php endif; ?>
    <!--/Плагин прокрутки страницы-->

    <div class="fixed-bar">
        <div class="centered clearfix">
            <div class="bar-left clearfix">
                <!-- плагин обратного звонка -->
                <?php if (class_exists('BackRing')): ?>
                    <div class='wrapper-back-ring'><button type='submit' class='back-ring-button default-btn'>Заказать бесплатный звонок</button></div>
                <?php endif; ?>
                <!--/ плагин обратного звонка -->

                <!--Вывод адреса магазина-->
                <?php layout('contacts-bar'); ?>
                <!--/Вывод адреса магазина-->
            </div>

            <div class="bar-right clearfix">
                <!--Индикатор сравнения товаров-->
                <?php layout('compare'); ?>
                <!--/Индикатор сравнения товаров-->
                <!--Вывод корзины-->
                <?php layout('cart'); ?>
                <!--/Вывод корзины-->
            </div>
        </div>
    </div>

    <!--Шапка сайта-->
    <div class="header">
        <div class="centered clearfix">
            <div class="top-bar clearfix">
                <div class="top-text">
      
                    <?php if (class_exists('SiteBlockEditor')): ?>
                        [site-block id=4]
                    <?php endif; ?>
  
                </div>

                <div class="top-auth clearfix">
                    <!--Индикатор сравнения товаров-->
                    <?php layout('compare'); ?>
                    <!--/Индикатор сравнения товаров-->

                    <!--Вывод авторизации-->
                    <div class="top-auth-block">
                        <?php layout('auth'); ?>
                    </div>
                    <!--/Вывод авторизации-->
                </div>
            </div>
        </div>


        <div class="middle-bar">
            <div class="centered clearfix">
                <!--Вывод логотипа сайта-->
                <div class="logo-block">
                    <a href="<?php echo SITE ?>">
                        <?php echo mgLogo(); ?>
                    </a>
                </div>
                <!--/Вывод логотипа сайта-->

                <!--Вывод адреса магазина-->
                <?php layout('contacts'); ?>
                <!--/Вывод адреса магазина-->

                <div class="top-search">
                    <!--Вывод аякс поиска-->
                    <?php layout('search'); ?>
                    <!--/Вывод аякс поиска-->
                    <!-- плагин обратного звонка -->
                    <?php if (class_exists('BackRing')): ?>
                        [back-ring]
                    <?php endif; ?>
                    <!--/ плагин обратного звонка -->
                </div>

                <!--Вывод корзины-->
                <?php layout('cart'); ?>
                <!--/Вывод корзины-->
            </div>
        </div>

        <div class="bottom-bar">
            <div class="centered clearfix">
                <!-- Вывод левого меню-->
                <div class="main-menu">
                    <a class="title title-desktop" href="<?php echo SITE ?>/catalog">Каталог товаров</a>
                    <a class="title title-mobile" href="javascript:void(0);">Каталог товаров</a>
                    <?php layout('leftmenu'); ?>
                </div>
                <!--/Вывод левого меню-->

                <!--Вывод верхнего меню-->
                <div class="top-menu-block clearfix">
                    <a href="javascript:void(0);" class="top-menu-toggle">
                        <span class="text">Меню</span>
                        <span class="toggle-wrapper">
                            <span class="toggle"></span>
                        </span>
                    </a>
                    <?php layout('topmenu'); ?>
                </div>
                <!--/Вывод верхнего меню-->
            </div>
        </div>
    </div>
    <!--/Шапка сайта-->

    <!--Вывод горизонтального меню, если оно подключено в настройках-->
    <?php horizontMenu(); ?>
        <!--/Вывод горизонтального меню, если оно подключено в настройках-->

    <!--Центральная часть сайта-->
    <div class="container">
        <?php if (isIndex()): ?>
            <div class="centered">
                <!--Плагин слайдера акций-->
                <?php if (class_exists('SliderAction')): ?>
                    [slider-action]
                <?php endif; ?>
                <!--/Плагин слайдера акций-->

                <!--Плагин триггеров-->
                <?php if (class_exists('trigger')): ?>
                    <div class="main-triggers">
                        [trigger-guarantee id="1"]
                    </div>
                <?php endif; ?>
                <!--/Плагин триггеров-->
            </div>
        <?php endif; ?>

        <?php if (isCatalog() || isIndex()) : ?>
            <div class="center-holder clearfix">
                <?php if (isCatalog()) : ?>
                <!--Плагин хлебных крошек-->
                <?php if (class_exists('BreadCrumbs')): ?>
                    <div class="breadcrumbs-holder">
                        [brcr]
                    </div>
                <?php endif; ?>
                <!--/Плагин хлебных крошек-->
                <?php endif; ?>

                <div class="clearfix">
                    <div class="side-menu">
                        <?php if (isIndex()): ?>
                            <?php if (class_exists('SiteBlockEditor')): ?>
                            <div class="side-banner">
                                [site-block id=1]
                            </div>
                            <?php endif; ?>
                            <!-- Блок новостей на главной-->
                     		 <?php if (class_exists('PluginNews')): ?>
                            	<?php layout('mockup_news'); ?>
                      		 <?php endif; ?>
                            <!--/Блок новостей-->
                          <?php if (class_exists('MgPoll')): ?>
                          [mg-poll id='1']
                          <?php endif; ?>
                        <?php endif; ?>

                        <?php if (isCatalog()) : ?>
<!--                            <div class="filter-block ">-->
<!--                                <a class="show-hide-filters" href="javascript:void(0);">Показать/скрыть фильтры</a>-->
<!--                                <div class="title">Фильтр</div>-->
<!--                                --><?php //filterCatalog(); ?>
<!--                            </div>-->

                            <div class="filter-block">
                                <a class="open-filter" href="javascript:void(0);">
                                    Показать фильтр
                                </a>
                                <div class="filter-overlay"></div>
                                <div class="filter-wrapper">
                                    <div class="title">Фильтр</div>
                                    <a class="close-filter" href="javascript:void(0);"><span></span></a>
                                    <?php filterCatalog(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="main-block">
                        <?php layout('content'); ?>
                    </div>
                </div>

                <!-- Плагин брендов-->
                <?php if (class_exists('brand')): ?>
                <div class="brands-holder">
                    <div class="centered">
                        <div class="brands-title">Популярные бренды</div>
                        [brand]
                    </div>
                </div>
                <?php endif; ?>
                <!--/ Плагин брендов-->

                <!-- тут описание главной страницы-->
            </div>
        <?php endif; ?>

        <?php if (!isCatalog() && !isIndex()) : ?>
            <div class="main-block">
                <?php layout('content'); ?>
            </div>
        <?php endif; ?>
    </div>
    <!--/Центральная часть сайта-->

</div>

<!--Подвал сайта-->
<div class="footer">
    <div class="footer-top">
        <div class="centered">
            <div class="col">
                <h2>Сайт</h2>
                <?php echo MG::get('pages')->getFooterPagesUl(); ?>
            </div>
            <div class="col">
                <h2>Продукция</h2>
                <ul>
                    <?php echo MG::get('category')->getCategoryListUl(0, 'public', false); ?>
                </ul>
            </div>
            <div class="col">
                <h2>Мы принимаем оплату</h2>
                <img src="<?php echo PATH_SITE_TEMPLATE ?>/images/payments.png"
                     title="Мы принимаем оплату"
                     alt="Мы принимаем оплату"/>
            </div>
            <div class="col">
                <h2>Мы в соцсетях</h2>
                <ul class="social-media">
                    <li><a href="javascript:void(0);" class="vk-icon" title="Vkontakte"><span></span></a></li>
                    <li><a href="javascript:void(0);" class="gplus-icon" title="Google+"><span></span></a></li>
                    <li><a href="javascript:void(0);" class="fb-icon" title="Facebook"><span></span></a></li>
                </ul>
                <div class="widget">
                    <!--Коды счетчиков-->
                    <?php layout('widget'); ?>
                    <!--/Коды счетчиков-->
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="centered clearfix">
            <?php copyrightMoguta(); ?>
            <div class="copyright"> <?php echo date('Y') ?> год. Все права защищены.</div>
            <div class="widget">
                <!--Коды счетчиков-->
                <?php layout('widget'); ?>
                <!--/Коды счетчиков-->
            </div>
        </div>
    </div>
    <!--Пустой блок для отступа фиксированной панели-->
    <div class="bar-height"></div>
    <!--/Пустой блок для отступа фиксированной панели-->
</div>
<!--/Подвал сайта-->


</body>
</html>