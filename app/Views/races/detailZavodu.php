<?=$this->extend("layout/template");?>
<?=$this->section("content");?>
<?php
    /**
     * @var object $race
     * 
     */
?>
<div class="container mt-4">
    <a href="<?= previous_url() ?>" class="btn btn-dark mb-3">Zpět</a>
    <h2 class="text-center"><?= esc($race->real_name) ?></h2>

    <table class="table table-bordered mt-3">
        <tr><th>Název</th>      <td><?= esc($race->real_name) ?></td></tr>
        <tr><th>Začátek závodu</th>    <td><?= date('d.m.Y', strtotime($race->start_date)) ?></td></tr>
        <tr><th>Konec závodu</th>      <td><?= date('d.m.Y', strtotime($race->end_date)) ?></td></tr>
        <tr><th>Logo</th>       <td><img src="<?= base_url('obrazky/loga/' . $race->logo) ?>" height="60"></td></tr>
        <tr><th>Země</th>       <td><span class="fi fi-<?= strtolower($race->country) ?>" style="font-size:2rem;"></span></td></tr>
    </table>
</div>

<?=$this->endSection();?>