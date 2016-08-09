<div class="container">

    <div class="col-md-3"></div>

    <div class="col-md-6">

        <div style="margin-top: 5%;">
            <form>
                <div class="form-group">
                    <label for="exampleInputUserModemId">Введите номер GSM модема</label>
                    <input type="text" class="form-control" name='modem_id' id="user_modem_id"
                           placeholder="Введите номер GSM модема"
                           value="<?= Yii::$app->request->get('modem_id', '') ?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputSerialNumber">Введите номер радиомодуля</label>
                    <input type="text" class="form-control" name="serial_number" id="serial_number"
                           placeholder="Введите номер радиомодуля"
                           value="<?= Yii::$app->request->get('serial_number', '') ?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputSerialNumber">Введите номер счетчика</label>
                    <input type="text" class="form-control" name="counter_id" id="serial_number"
                           placeholder="Введите номер счетчика"
                           value="<?= Yii::$app->request->get('counter_id', '') ?>">
                </div>
                <button type="submit" class="btn btn-info" style="width: 100%;">Найти</button>
            </form>
        </div>

        <?php if (!empty($rmodule)) { ?>
            <div style="margin-top: 5%;">
                <div class="table-responsive">
                    <table class="table table-bordered" style="text-align: center;width: 544px">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Номер радиомодуля</th>
                            <th>Номер счетчика</th>
                            <th>Номер GSM модема</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rmodule as $r): ?>
                            <tr>
                                <th scope="row">1</th>
                                <td><?= $r['serial_number'] ?></td>
                                <td><?php if(isset($r->counter)){echo $r->counter->serial_number;} ?></td>
                                <td><?= $r['modem_id'] ?></td>
                                <td>
                                    <?php echo \yii\helpers\Html::a('Подключить', array('step/step1',
                                        'serial_number' => $r['serial_number']), [
                                        'class' => 'btn btn-info'
                                    ]); ?>
                                </td>
                                <td>
                                    <?php echo \yii\helpers\Html::a('История показаний', array('step/indicationshistory',
                                        'counter_id' => $r['counter_id']), [
                                        'class' => 'btn btn-info'
                                    ]); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        <?php } else if (isset($message)) { ?>

            <h3 style="text-align: center; color: #57889c;"><?= $message ?></h3>

        <?php } ?>

    </div>
    <div class="col-md-3"></div>

</div>