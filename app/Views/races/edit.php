<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<?php
/** * @var stdClass $country 
 * @var array $kategorie
 * @var object[] $zavodyV
 * @var array $rocniky2
 */
?>
<div class="container mt-4">
    <h1>Editovat ročník závodu</h1>
    <a href="<?= base_url('index.php/rocnik/' . $country->year) ?>" class="btn btn-dark mb-3">Zpět</a>

    <div class="row">
        <?= form_open_multipart('form-helper/races/update/' . $country->id, ['class' => 'row']) ?>
            <div class="col-md-10">

                <?= form_dropdown_bs(
                    "year", 
                    $rocniky2, 
                    ['class' => 'form-select', 'id' => 'year'], 
                    'mb-3', 
                    "Ročník závodu", 
                    $country->year
                ) ?>

                <div class="mb-3">
                    <label for="id_race" class="form-label">Závod</label>
                    <select class="form-select js-example-basic-single" id="id_race" name="id_race">
                        <option value=""></option>
                        <?php foreach($zavodyV as $race): ?>
                            <?php $selected = ($race->id == $country->id_race) ? 'selected' : ''; ?>
                            <option value="<?= $race->id ?>" <?= $selected ?>>
                                <?= esc($race->default_name) ?> (<?= esc($race->type ?? '') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php
                // Příprava atributů pro textové inputy a kalendáře
                $atributyRaceName = [
                    'class' => 'form-control',
                    'id'    => 'real_name',
                    'placeholder' => 'Zadejte přesný název ročníku',
                    'value' => $country->real_name
                ];

                $atributyZacatek = [
                    'class' => 'form-control',
                    'id'    => 'start_date',
                    'value' => $country->start_date
                ];

                $atributyKonec = [
                    'class' => 'form-control',
                    'id'    => 'end_date',
                    'value' => $country->end_date
                ];
                ?>

                <?= form_input_bs("real_name", $atributyRaceName, "Název závodu") ?>

                <?= form_input_bs("start_date", $atributyZacatek, "Datum startu závodu", "date") ?>
                <?= form_input_bs("end_date", $atributyKonec, "Datum konce závodu", "date") ?>

                <?= form_dropdown_bs("category", $kategorie, ['class' => 'form-select'], 'mb-3', "Kategorie Závodů", $country->category) ?>

                <div class="mb-3">
                    <label for="logo" class="form-label">Logo závodu</label>
                    <?php if (!empty($country->logo)): ?>
                        <div class="mb-2">
                            <small class="text-muted">Aktuální logo: <?= esc($country->logo) ?></small>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="logo" class="form-control" id="logo" accept="image/*"> 
                </div>

                <?= form_hidden('_method', 'PUT') ?>
                <?= form_hidden('id', $country->id) ?>
                
                <button type="submit" class="btn btn-primary mt-3">Uložit změny</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#id_race').select2({
            width: '100%',
            placeholder: "Klikněte pro výběr nebo vyhledávání...",
            allowClear: true
        });
    });
</script>
<?= $this->endSection() ?>