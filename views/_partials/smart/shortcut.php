<?php if (!empty(Yii::app()->params['menu'])): ?>
    <div id="shortcut">
        <ul>
            <?php foreach (Yii::app()->params['menu'] as $item): ?>
                <?php if (!empty($item['visible']) || !isset($item['visible'])): ?>
                    <li>
                        <?php
                            $item['linkOptions'] = isset($item['linkOptions']) ? $item['linkOptions'] : array();
                            $item['linkOptions']['class'] = (isset($item['linkOptions']['class'])?$item['linkOptions']['class']:'').' jarvismetro-tile big-cubes bg-color-'.(isset($item['shortcutColor'])?$item['shortcutColor']:'blue');
                        ?>
                        <?php
                            echo CHtml::openTag('a',CMap::mergeArray(array('href'=>CHtml::normalizeUrl($item['url'])),$item['linkOptions']));
                                echo CHtml::openTag('span',array("class"=>"iconbox"));
                                    echo CHtml::openTag('i',array("class"=>"fa ". (isset($item['icon']) ? $item['icon'] : 'fa-table') ." fa-4x"));
                                    echo CHtml::closeTag('i');
                                    echo CHtml::openTag('span');
                                    echo $item['label'];
                                            if (!empty($item["counter"])){
                                                echo CHtml::openTag('span',array("class"=>"label pull-right bg-color-darken"));
                                                echo $item["counter"];
                                                CHtml::closeTag('span');
                                            }
                                    echo CHtml::closeTag('span');
                                echo CHtml::closeTag('span');

                            echo CHtml::closeTag('a');
                        ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>

        </ul>
    </div>
<?php endif; ?>