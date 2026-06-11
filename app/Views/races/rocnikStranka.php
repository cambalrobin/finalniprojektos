<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h1 class="text-center">Závody pro rok <?= esc($rok) ?></h1>
    <?= anchor('/', 'Zpět na přehled roků', ['class' => 'btn btn-dark mb-3']) ?>
    <?= anchor('form-helper/races/add/' . $rok, 'Přidat závod do roku ' . esc($rok), ['class' => 'btn btn-dark mb-3']) ?>

    <?php
    $table = new \CodeIgniter\View\Table();

    // Hlavička obsahuje sloupec "Akce"
    $table->setHeading("Název závodu", "Pohlaví", "Akce");

    foreach ($zavody as $row) {
        // Tlačítko pro editaci
        $editButton = anchor('form-helper/races/edit/' . $row->id,'Editovat', ['class' => 'btn btn-warning btn-sm me-1']
        );

        // Tlačítko pro bezpečný Soft Delete s potvrzovacím dialogem
        $deleteButton = anchor('form-helper/races/delete/' . $row->id, 'Smazat', ['class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Opravdu chcete tento závod smazat?');"]
        );

        // Přidání řádku – do sloupce akcí spojíme editaci i mazání za sebe
        $table->addRow(
            anchor(base_url('index.php/zavody/' . $row->id), $row->real_name),
            esc($row->sex),
            $editButton . $deleteButton
        );
    }

    $template = [
        'table_open' => '<table class="table table-striped table-bordered">'
    ];
    $table->setTemplate($template);

    echo $table->generate();
    echo $pager->links();
    ?>
</div>

<?= $this->endSection() ?>