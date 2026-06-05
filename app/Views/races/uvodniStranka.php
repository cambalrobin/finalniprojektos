<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<h1>Ženské závody – přehled ročníků</h1>

<div class="container mt-4">
        <h3 class="text-center">Závody dospělých žen</h3>
        <?php
            $table = new \CodeIgniter\View\Table();

            $table->setHeading("Rok");
        
            foreach($zavodyRoky as $row){
                $table->addRow( anchor('rocnik/'.$row->year, $row->year) );
            }
            
            $template = array(
                'table_open'=> '<table class="table table-bordered">',
                'thead_open'=> '<thead>',
                'thead_close'=> '</thead>',
                'heading_row_start'=> '<tr>',
                'heading_row_end'=>' </tr>',
                'heading_cell_start'=> '<th>',
                'heading_cell_end' => '</th>',
                'tbody_open' => '<tbody>',
                'tbody_close' => '</tbody>',
                'row_start' => '<tr>',
                'row_end'  => '</tr>',
                'cell_start' => '<td>',
                'cell_end' => '</td>',
                'row_alt_start' => '<tr>',
                'row_alt_end' => '</tr>',
                'cell_alt_start' => '<td>',
                'cell_alt_end' => '</td>',
                'table_close' => '</table>'
                );
                $table->setTemplate($template);
                
            echo $table->generate();  
            ?>
    </div>

<?= $this->endSection() ?>