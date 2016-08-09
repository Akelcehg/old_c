<div class="container">

    <div class="col-md-3"></div>

    <div class="col-md-6">

        <?php if (!empty($indications)) { ?>
            <div style="margin-top: 5%;">
                <div class="table-responsive">
                    <table class="table table-bordered" style="text-align: center;">
                        <thead>
                        <tr>

                            <th>Показания</th>
                            <th>Импульсы</th>
                            <th> Дата и время </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php  foreach ($indications as $c): ?>
                            <tr>

                                <td><?= $c->indications ?></td>
                                <td><?= $c->impulse->impulse ?></td>
                                <td><?= $c->created_at?></td>

                            </tr>
                        <?php  endforeach; ?>

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