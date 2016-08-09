<?php

namespace app\components;
use yii\base\Component;

/**
 * Excel Widget for generate Excel File.
 *
 * Usage
 * -----
 *
 * Once the extension is installed, simply use it in your code by  :
 *
 * ```php
 * <?php
 *  \moonland\phpexcel\Excel::widget([
 * 		'models' => $allModels,
 * 		'columns' => ['column1','column2','column3'],
 * 		//without header working, because the header will be get label from attribute label.
 * 		'header' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 * 	]); ?>
 * ```
 *
 * @author Moh Khoirul Anam <moh.khoirul.anaam@gmail.com>
 * @copyright 2014
 * @since 1
 */
class Excel extends Component
{



}
