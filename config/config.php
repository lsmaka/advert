<?php


$config = array();

// Путь для загружаемых файлов 
//  Для тестов убран в директорую плагина. 
$config['path_to_files'] = 'plugins/advert/templates/skin/default/images/files/';
//$config['path_to_files'] = 'uploads/advert/';

// Страница которая выводиться если у пользователя недостаточно прав на использования данного фунционала
// Если параметр не задан выводиться имформационное сообешние согласно текста в lang
// Для создания страницы используйте плагин Static Page
// Примеры использования :
// $config['help_page'] = 'help';
// $config['help_page'] = 'help/advert';
$config['help_page'] = '';

// Только администраторы могут размещать рекламу. Для пользователей данный сервис доступен не будет
// 1 - только админы
// 0 - все пользователи
$config['admin_mode'] = 0;

// Пользователь чья реклама будет показываться по умолчанию
$config['defuser'] = 'admin';

// Список привилегированных пользователей. Нахождение в данном списке дает возможность обходить ограничения
// Пример : $config['allow_users'] = array('user1','user2','user3');
$config['allow_users'] = array('');

// Заблокированные пользователи
// Пример : $config['allow_users'] = array('user1','user2','user3');
$config['disable_users'] = array('');

// Включение режима модерации
$config['moderation'] = 1;

//Значение рейтинга по достижению которого пользователь имеет возможность пользоваться данным функционалом
$config['access_rate'] = -1;

// Actions на которых будет отображаться реклама (для пользователя по умолчанию) 
$config['block_actions_admin'] = array(
	'index', 
	'blogs', 
	'blog', 
	'stream', 
	'people', 
	'page', 
	'profile', 
	'feed', 
	'tag', 
	'search', 
	'comments'
);
// Action на которых будет обображаться реклама для пользователя. blog и profile это все что есть у пользователя. 
// Не думаю что имеет смысл что-то добавлять.
$config['block_actions_user'] = array('blog', 'profile');

// Чести action где будет показываться реклама для пользователя по умолчанию 
// Важно ! Не изменяйте ключи массива. 
$config['block_places_admin'] = array(
	'header'=>'Шапка сайта',
	'fotter'=>'Подвал сайта',
	'sidebar_fix_first'=>'Fix первый (sidebar)',
	'sidebar_fix_last'=>'Fix последний (sidebar)',
	'sidebar_float'=>'Плавающий (sidebar)',
	'topic' => 'Топик (topic)'
);

// Тот же смысл что и для пользователя по умолчанию
$config['block_places_user'] = array(
	'sidebar_fix_first'=>'Fix первый (sidebar)',
	'sidebar_fix_last'=>'Fix последний (sidebar)',
	'sidebar_float'=>'Плавающий (sidebar)',
	'topic' => 'Топик (topic)'
);

// Начальные приорите для блоков 
// Ничего не меняйте если в том нет необходимости
$config['block_priority_sidebar_fix_first'] = 1000;
$config['block_priority_sidebar_fix_last'] = 0;
$config['block_priority_sidebar_float'] = -100;



// Настройки ролей и типов рекламы а также стилей CSS с использованием которых будет отображаться блок. Ролей две : admin и user ( не меняйте )
// Например : у роли admin (пользователь по умолчанию, пользоватеть (-ли) с провами администратора) есть возможность добавлять три типа блока рекламы
// 1 - text, 2 - banner, 3 - код. Под каждый из трех типов есть свои CSS стили для блока. 
// Стили задаются в файле advert/templates/skin/default/css/style_user.css
$config['block_types'] = array (
	'admin' => array(
		'text' => array(
			'name' => 'Текстовый',
			'css'  => array(
				'-def' => 'Cтили шаблона',
				'-redBorder' => 'Красная рамка',
				'-blueBorder' => 'Синяя рамка',
				'-greyBorderDashed' => 'Серая рамка (пунктир)',
				'-adsStyle' => 'ADS Style',
				'-emptyCenter' => 'Стили отключены'
			),
		),
		'banner' => array(	
			'name' => 'Баннер',
			'css'  => array(
				'-def' => 'Cтили шаблона',
				'-emptyCenter' => 'Стилии отключены'
			),
		),
		'code' => array(	
			'name' => 'Код',
			'css'  => array(
				'-def' => 'Cтили шаблона',
				'-emptyCenter' => 'Стилии отключены'
			),
		),		
	),	

	'user' => array(
		'text' => array(
			'name' => 'Текстовый',
			'css'  => array(
				'-def' => 'Cтили шаблона',
				'-redBorder' => 'Красная рамка',
				'-blueBorder' => 'Синяя рамка',
				'-adsStyle' => 'ADS Style',
				'-emptyCenter' => 'Стили отключены'
			),
		),
		'banner' => array(	
			'name' => 'Баннер',
			'css'  => array(
				'-def' => 'Cтили шаблона',
				'-emptyCenter' => 'Стилии отключены'
			),
		),			
	),		
	
);
//
Config::Set('router.page.advert', 'PluginAdvert_ActionAdvert');
$config['table']['page']= '___db.table.prefix___advert';

return $config;
