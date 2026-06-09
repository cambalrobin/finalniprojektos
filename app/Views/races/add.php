<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<?php
    /**
     * @var array $rocniky2
     * @var array $kategorie
     * @var array $zavodyV
     * @var bool $isFixed
     * @var string $minDate
     * @var string $maxDate
     */
?>
<div class="container mt-4">
    <h1>Přidat závod</h1>
    <a href="<?= previous_url() ?>" class="btn btn-dark mb-3">Zpět</a>

    <div class="row">
        <?= form_open_multipart('form-helper/races/create', ['class' => 'row']) ?>
            <div class="col-md-10">
                
                <div class="mb-3">
                    <label for="year" class="form-label">Ročník závodu</label>
                    <input type="text" class="form-control" id="year" name="year" value="<?= key($rocniky2) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="id_race" class="form-label">Závod</label>
                    <select class="form-select js-example-basic-single" id="id_race" name="id_race">
                        <option value=""></option>
                        <?php foreach($zavodyV as $race): ?>
                            <option value="<?= $race['id'] ?>">
                                <?= esc($race['default_name']) ?> (<?= esc($race['type']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php
                $atributyRaceName = [
                    'class' => 'form-control',
                    'id'    => 'real_name',
                    'placeholder' => 'Zadejte přesný název ročníku'
                ];

                // Kalendáře omezené na min a max daného roku
                $atributyZacatek = [
                    'class' => 'form-control',
                    'id'    => 'start_date',
                    'min'   => $minDate,
                    'max'   => $maxDate
                ];

                $atributyKonec = [
                    'class' => 'form-control',
                    'id'    => 'end_date',
                    'min'   => $minDate,
                    'max'   => $maxDate
                ];
                ?>

                <?= form_input_bs("real_name", $atributyRaceName, "Název závodu") ?>

                <?= form_input_bs("start_date", $atributyZacatek, "Datum startu závodu", "date") ?>

                <?= form_input_bs("end_date", $atributyKonec, "Datum konce závodu", "date") ?>

                <?= form_dropdown_bs("categories", $kategorie, [], 'mb-3', "Kategorie Závodů") ?>

                <div class="mb-3">
                    <label for="logo" class="form-label">Logo závodu</label>
                    <input type="file" name="logo" class="form-control" id="logo" accept=".jpg, .png">
                </div>

                <button type="submit" class="btn btn-dark mt-3">Uložit závod</button>
                
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

        // Automatické předvyplnění konce závodu podle startu
        $('#start_date').on('change', function() {
            let startDateValue = $(this).val();
            
            // Nastaví stejné datum do konce závodu
            $('#end_date').val(startDateValue);
            
            // Dynamicky upraví minimální datum pro konec závodu, aby nešel vybrat dřívější den než start
            $('#end_date').attr('min', startDateValue);
        });
    });
</script>
<?= $this->endSection() ?>
