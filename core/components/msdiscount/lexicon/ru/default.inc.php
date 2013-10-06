<?php

include_once 'setting.inc.php';

$_lang['msdiscount'] = 'Скидки и акции';
$_lang['msdiscount_menu_desc'] = 'Управление системой скидок';

$_lang['msd_sales'] = 'Скидки и акции';
$_lang['msd_sales_desc'] = 'Здесь вы создаёте акцию, указываете скидку и можете назначить группы товаров и пользователей, для которых она будет действовать.<br/>Если вы группы не указаны - скидка действует для всех.';
$_lang['msd_users'] = 'Группы пользователей';
$_lang['msd_users_desc'] = 'Управление группами пользователей. Вы можете указать персональную скидку группы, которую получат все её пользователи.';
$_lang['msd_products'] = 'Группы товаров';
$_lang['msd_products_desc'] = 'Управление группами товаров. Вы можете указать персональную скидку группы, которая будет действовать на все товары, которые в неё входят.';


$_lang['msd_submit'] = 'Проверить';
$_lang['msd_clear'] = 'Очистить';
$_lang['msd_menu_create'] = 'Создать новую запись';
$_lang['msd_menu_update'] = 'Изменить запись';
$_lang['msd_menu_remove'] = 'Удалить запись';
$_lang['msd_menu_remove_confirm'] = 'Вы действительно хотите удалить эту запись?';

$_lang['msd_err_ae'] = 'Это поле должно быть уникально';
$_lang['msd_err_ns'] = 'Это поле обязательно для заполнения';

$_lang['msd_sales_main'] = 'Основные свойства';
$_lang['msd_sales_usergroups'] = 'Пользователи';
$_lang['msd_sales_productgroups'] = 'Товары';
$_lang['msd_sales_create'] = 'Новая скидка';
$_lang['msd_sales_update'] = 'Редактирование ';
$_lang['msd_sales_id'] = 'Id';
$_lang['msd_sales_discount'] = 'Скидка';
$_lang['msd_sales_name'] = 'Название';
$_lang['msd_sales_description'] = 'Описание';
$_lang['msd_sales_begins'] = 'Начинается';
$_lang['msd_sales_ends'] = 'Заканчивается';
$_lang['msd_sales_active'] = 'Включена';
$_lang['msd_sales_resource'] = 'Связана со страницей';
$_lang['msd_sales_image'] = 'Изображение';

$_lang['msd_group_create'] = 'Создать группу';
$_lang['msd_group_update'] = 'Редактирование ';

$_lang['msd_group_id'] = 'Id';
$_lang['msd_group_name'] = 'Название';
$_lang['msd_group_discount'] = 'Скидка';
$_lang['msd_group_joinsum'] = 'Требуется сумма';

$_lang['msd_members_group'] = 'Группа';
$_lang['msd_members_relation'] = 'Режим';
$_lang['msd_members_select'] = 'Выберите группу';
$_lang['msd_members_relation_in'] = 'Включает';
$_lang['msd_members_relation_out'] = 'Исключает';

$_lang['msd_check'] = 'Проверка';
$_lang['msd_check_desc'] = 'Здесь вы можете проверить, как будет насчитываться скидка.';
$_lang['msd_check_product'] = 'Товар';
$_lang['msd_check_user'] = 'Пользователь';
$_lang['msd_check_date'] = 'Дата';
$_lang['msd_check_log'] = 'Лог';
$_lang['msd_check_select'] = 'Выберите из списка';
$_lang['msd_check_err_pid'] = 'Вы должны указать товар для проверки';
$_lang['msd_check_err_product'] = 'Не могу найти указанный товар';



// Debug messages
$_lang['msd_dbg_initial_price'] = 'Начальная цена товара (id=[[+product_id]]): <b>[[+price]]</b>.';

$_lang['msd_dbg_get_users'] = 'Получены группы пользователя (id=[[+user_id]]): <b>[[+count]]</b> <small>шт.</small>';
$_lang['msd_dbg_get_products'] = 'Получены группы товара (id=[[+product_id]]): <b>[[+count]]</b> <small>шт.</small>';
$_lang['msd_dbg_get_sales'] = "Получены активные акции: <b>[[+count]]</b> <small>шт.</small>\n";

$_lang['msd_dbg_sale_start'] = 'Пытаемся применить акцию "[[+name]]"';
$_lang['msd_dbg_sale_all'] = '	У "[[+name]]" нет требований к группам';

$_lang['msd_dbg_sale_group_both'] = '	И товар и пользователь удовлетворяют правилам акции, поэтому: <b>[[+discount]]</b>.';
$_lang['msd_dbg_sale_group_users'] = '	Пользователь входит в требуемые группы "[[+name]]", поэтому: <b>[[+discount]]</b>.';
$_lang['msd_dbg_sale_group_products'] = '	Товар входит в требуемые группы "[[+name]]", поэтому: <b>[[+discount]]</b>.';
$_lang['msd_dbg_sale_personal_users'] = '	У пользователя есть персональная скидка: <b>[[+discount]]</b>.';
$_lang['msd_dbg_sale_personal_products'] = '	На товар установлена персональная скидка: <b>[[+discount]]</b>.';
$_lang['msd_dbg_sale_group_no'] = '	Пользователь и\или товар не входят в список требуемых групп "[[+name]]".';
$_lang['msd_dbg_sale_users_exclude'] = '	Правила "[[+name]]" исключают группу пользователя (id = [[+group_id]]).';
$_lang['msd_dbg_sale_products_exclude'] = '	Правила "[[+name]]" исключают группу товара (id = [[+group_id]]).';
$_lang['msd_dbg_sale_end'] = "Проверены все активные акции.\n";

$_lang['msd_dbg_personal_users'] = 'Персональная скидка пользователя от группы (id = [[+group_id]]): <b>[[+discount]]</b>.';
$_lang['msd_dbg_personal_products'] = 'Скидка на все товары из группы (id = [[+group_id]]): <b>[[+discount]]</b>.';

$_lang['msd_dbg_discount_percent_to_abs'] = 'Считаем процент [[+percent]] от цены товара [[+price]] - выходит <b>[[+discount]]</b>.';
$_lang['msd_dbg_discount_abs_vs_percent'] = 'Сравниваем абсолютную скидку [[+absolute]] с посчитанным процентом [[+percent]].';
$_lang['msd_dbg_discount_total'] = "Итоговая скидка <b>[[+discount]]</b>\nИтоговая цена товара <b>[[+price]]</b>.";
$_lang['msd_dbg_discount_less'] = "	Пропускаем скидку <b>[[+discount]]</b>, так как она меньше текущей.";
$_lang['msd_dbg_discount_no'] = 'Скидки нет, возвращаем начальную цену <b>[[+price]]</b>.';
$_lang['msd_dbg_time'] = "\nЗатрачено времени: <b>[[+time]]</b> <small>сек.</small>";