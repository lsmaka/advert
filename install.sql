--
-- Структура таблицы `prefix_advert`
--

CREATE TABLE IF NOT EXISTS `prefix_advert` (
  `advert_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_owner_id` int(11) unsigned NOT NULL,
  `user_owner_login` varchar(50) NOT NULL,
  `advert_status` int(11) DEFAULT NULL,
  `advert_talk_id` int(11) DEFAULT NULL,
  `advert_hint` varchar(200) NOT NULL,
  `advert_data_text` text NOT NULL,
  `advert_data_url` varchar(200) DEFAULT NULL,
  `advert_data_img` varchar(200) DEFAULT NULL,
  `advert_block_type` varchar(20) NOT NULL,
  `advert_block_place` varchar(200) NOT NULL,
  `advert_block_rewrite` int(11) NOT NULL DEFAULT '0',
  `advert_block_priority` int(11) NOT NULL,
  `advert_block_css` varchar(200) DEFAULT NULL,
  `advert_rules` text NOT NULL,
  `advert_view` int(11) NOT NULL DEFAULT '0',
  `advert_view_count` int(11) NOT NULL DEFAULT '0',
  `advert_click` int(11) NOT NULL DEFAULT '0',
  `advert_click_count` int(11) NOT NULL DEFAULT '0',
  `advert_date_add` datetime DEFAULT NULL,
  `advert_date_edit` datetime DEFAULT NULL,
  `advert_date_start` datetime DEFAULT NULL,
  `advert_date_stop` datetime DEFAULT NULL,
  `advert_date_tostop` datetime DEFAULT NULL,
  `advert_date_tostart` datetime DEFAULT NULL,
  `advert_date_tostart_flag` int(11) DEFAULT '0',
  PRIMARY KEY (`advert_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


