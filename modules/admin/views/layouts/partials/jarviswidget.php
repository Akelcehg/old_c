<?php
    $class = isset($class)?is_array($class)?implode(' ', $class):$class:'jarviswidget-color-blueDark';
    $dataWidgetCollapsed =isset($dataWidgetCollapsed)?$dataWidgetCollapsed:'false';
    $id=isset($id)?$id:uniqid();
    $defaultDisabledButtons = [
        'togglebutton' => 'data-widget-togglebutton="false"',
        'fullscreenbutton' => 'data-widget-fullscreenbutton="false"',

    ];

    if(!isset($disabledButtons))
        $disabledButtons = [];

    $disabledKeys = array_intersect($disabledButtons, array_keys($defaultDisabledButtons));

    $disabledButtons = [];

    foreach($disabledKeys as $disabledKey) {
        $disabledButtons[] = $defaultDisabledButtons[$disabledKey];
    }

?>
<!-- Widget ID (each widget will need unique ID)-->
    <div class="jarviswidget <?php echo $class; ?>" id="<?php echo  $id; ?>"
        data-widget-editbutton="false"
        data-widget-colorbutton="false"
        data-widget-deletebutton="false"
        data-widget-sortable="false"
        data-widget-custombutton="false"
        data-widget-collapsed=<?php echo $dataWidgetCollapsed ?>

        <?php echo implode(" ", $disabledButtons); ?>
        >
        <header>
            <?php echo isset($control) ? $control : '' ?>
            <?php echo $header; ?>
        </header>
        <!-- widget div-->
        <div class="content">
            <?php if(isset($editbox)) {
                ?>
            <!-- widget edit box -->
            <div class="jarviswidget-editbox">
                <!-- This area used as dropdown edit box -->
                <?php echo $editbox; ?>
            </div>
            <!-- end widget edit box -->
                <?php
            }?>
            <!-- widget content -->
            <div class="widget-body">
                <?php if(isset($toolbar)) {
                    ?>
                    <div class="widget-body-toolbar">
                        <?php echo $toolbar; ?>
                    </div>
                    <?php
                }?>
                <?php echo $content; ?>
            </div>
            <!-- end widget content -->

        </div>
        <!-- end widget div -->
    </div>
<!-- end widget -->