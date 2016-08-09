<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_200353_menu extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `menu_items` (
              `id` int(11) NOT NULL,
              `label` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
              `url` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
              `owner_id` int(11) NOT NULL DEFAULT '0',
              `status` enum('ACTIVE','DEACTIVATED','DELETED') COLLATE utf8_unicode_ci DEFAULT 'ACTIVE',
              `level` int(11) DEFAULT NULL,
              `position` int(11) DEFAULT NULL,
              `icon` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
              `login_state_id` int(11) NOT NULL DEFAULT '1',
              `new_window` smallint(1) DEFAULT '0'
            ) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

            --
            -- Dumping data for table `menu_items`
            --

            INSERT INTO `menu_items` (`id`, `label`, `url`, `owner_id`, `status`, `level`, `position`, `icon`, `login_state_id`, `new_window`) VALUES
            (1, 'Система учета газа ', '/admin/counter/', 0, 'ACTIVE', 1, 1, 'fa-clock-o', 1, 0),
            (35, 'Пользователи', '', 0, 'ACTIVE', 1, 22, 'fa-user', 1, 0),
            (36, 'Список', '/admin/users/index', 35, 'ACTIVE', 2, 2, 'fa-table', 1, 0),
            (37, 'Добавить Юзера', '/admin/users/adduser', 35, 'ACTIVE', 2, 3, 'fa-table', 1, 0),
            (154, 'Меню', '/admin/menus/index', 0, 'ACTIVE', 1, 22, 'fa-th-list', 1, 0),
            (156, 'Logout', '/site/logout', 0, 'ACTIVE', 1, 22, 'fa-sign-out', 1, 0);

            -- --------------------------------------------------------

            --
            -- Table structure for table `menu_items_access`
            --

            CREATE TABLE IF NOT EXISTS `menu_items_access` (
              `id` int(11) NOT NULL,
              `menu_id` int(11) DEFAULT NULL,
              `role_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

            --
            -- Dumping data for table `menu_items_access`
            --

            INSERT INTO `menu_items_access` (`id`, `menu_id`, `role_name`) VALUES
            (1, 14, 'admin');

            -- --------------------------------------------------------

            --
            -- Table structure for table `menu_items_login_states`
            --

            CREATE TABLE IF NOT EXISTS `menu_items_login_states` (
              `id` int(11) NOT NULL,
              `name` varchar(32) NOT NULL
            ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

            --
            -- Dumping data for table `menu_items_login_states`
            --

            INSERT INTO `menu_items_login_states` (`id`, `name`) VALUES
            (1, 'All'),
            (2, 'Logged in'),
            (3, 'Not logged in'),
            (4, 'Logged in under admin');

            --
            -- Indexes for dumped tables
            --

            --
            -- Indexes for table `menu_items`
            --
            ALTER TABLE `menu_items`
              ADD PRIMARY KEY (`id`), ADD KEY `menu_items_login_state_id_idx` (`login_state_id`);

            --
            -- Indexes for table `menu_items_access`
            --
            ALTER TABLE `menu_items_access`
              ADD PRIMARY KEY (`id`);

            --
            -- Indexes for table `menu_items_login_states`
            --
            ALTER TABLE `menu_items_login_states`
              ADD PRIMARY KEY (`id`);

            --
            -- AUTO_INCREMENT for dumped tables
            --

            --
            -- AUTO_INCREMENT for table `menu_items`
            --
            ALTER TABLE `menu_items`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=158;
            --
            -- AUTO_INCREMENT for table `menu_items_access`
            --
            ALTER TABLE `menu_items_access`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
            --
            -- AUTO_INCREMENT for table `menu_items_login_states`
            --
            ALTER TABLE `menu_items_login_states`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
            /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
            /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
            /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

      ");
    }

    public function down()
    {
        echo "m150323_200355_alter_user_counters_add_last_indications cannot be reverted.\n";

        return false;
    }
}
