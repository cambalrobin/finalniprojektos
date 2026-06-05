<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Race;
use App\Models\RaceType;
use App\Models\RaceYear;
use App\Libraries\ArrayLib;
use Config\KonfiguracniSoubor;
// Nastaveno na přesný název tvé knihovny Upload
use App\Libraries\Upload;

class Main extends BaseController
{
    public function index()
    {
        $raceYearModel = new RaceYear();
        $zavody = $raceYearModel
            ->select("year")
            ->where('sex', 'W')
            ->orderBy('year', 'DESC')
            ->distinct()
            ->findAll();

        $data = [
            'zavodyRoky' => $zavody
        ];

        echo view('races/uvodniStranka', $data);
    }

    public function rocnik($rok)
    {
        $config = new KonfiguracniSoubor();
        $perPage = $config->strankovani;

        $raceYearModel = new RaceYear();

        $zavodyProRok = $raceYearModel
            ->where('year', $rok)
            ->where('sex', 'W')
            ->paginate($perPage);

        $pager = $raceYearModel->pager;

        $data = [
            'rok'    => $rok,
            'zavody' => $zavodyProRok,
            'pager'  => $pager
        ];

        echo view('races/rocnikStranka', $data);
    }

    public function zavody($id)
    {
        $RaceYear = new RaceYear();
        $data = [
            "race" => $RaceYear->find($id)
        ];

        echo view("races/detailZavodu", $data);
    }

    public function add($zvolenyRok = null)
    {
        $db = new RaceYear();
        $raceModel = new Race();
        $arrayLib = new ArrayLib();

        $years2 = [$zvolenyRok => $zvolenyRok];

        $categories = $db->table('race_type')->distinct()->findColumn('category') ?? [];
        $categories2 = $arrayLib->setValueToKey($categories);

        $zavodyV = $raceModel
            ->table('cyklo_race')
            ->select('id, default_name, type')
            ->orderBy('default_name', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            "kategorie" => $categories2,
            "rocniky2"  => $years2,
            "zavodyV"   => $zavodyV,
            "isFixed"   => true,
            "minDate"   => $zvolenyRok . "-01-01",
            "maxDate"   => $zvolenyRok . "-12-31"
        ];

        echo view('races/add', $data);
    }
    

    public function create()
{
    
    $id_race     = $this->request->getPost('id_race');
    $real_name   = $this->request->getPost('real_name');
    $year        = $this->request->getPost('year');
    $start_date  = $this->request->getPost('start_date');
    $end_date    = $this->request->getPost('end_date');
    $category    = $this->request->getPost('categories');

    // 1. Získání souboru přes getFile podle tutoriálu
    $logoFile = $this->request->getFile('logo');
    
    // 2. Definování tří věcí pro upload: objekt, cesta, název
    $uploadLib = new Upload();
    $path = FCPATH . 'obrazky/loga';
    $name = url_title($real_name, '-', true) . '-' . time();

    // Zavolání metody ze třídy pro upload
    $uploadResult = $uploadLib->uploadFile($logoFile, $path, $name);

    $raceModel = new RaceYear();

    // 3. Info o uploadu přidat do databáze
    $data = [
        'id_race'    => $id_race,
        'real_name'  => $real_name,
        'year'       => $year,
        'start_date' => $start_date,
        'end_date'   => $end_date,
        'category'   => $category,
        'logo'       => $uploadResult['name'], // Vrací celý název z metody
        'sex'        => 'W'
    ];

    $raceModel->save($data);

    return redirect()->to(base_url('index.php/rocnik/' . $year));
}

    public function edit($id)
    {
        $raceYearModel = new RaceYear();
        $raceModel = new Race();
        $arrayLib = new ArrayLib();

        $zavodProEditaci = $raceYearModel->find($id);

        $zvolenyRok = $zavodProEditaci->year;
        $years2 = [$zvolenyRok => $zvolenyRok];

        $categories = $raceYearModel->table('race_type')->distinct()->findColumn('category') ?? [];
        $categories2 = $arrayLib->setValueToKey($categories);

        $zavodyV = $raceModel
            ->table('cyklo_race')
            ->select('id, default_name, type') 
            ->orderBy('default_name', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            "country"   => $zavodProEditaci,
            "rocniky2"  => $years2,
            "zavodyV"   => $zavodyV,
            "kategorie" => $categories2 
        ];

        echo view('races/edit', $data);
    }

    public function update($id)
{
    $raceYearModel = new RaceYear();

    // Zachycení běžných políček z formuláře
    $data = [
        'real_name'  => $this->request->getPost('real_name'),
        'category'   => $this->request->getPost('category'),
        'start_date' => $this->request->getPost('start_date'),
        'end_date'   => $this->request->getPost('end_date'),
        'id_race'    => $this->request->getPost('id_race'),
        'year'       => $this->request->getPost('year'),
    ];

    // Získání souboru a provedení uploadu přesně podle tutoriálu
    $logoFile = $this->request->getFile('logo');
    
    $uploadLib = new Upload();
    $path = FCPATH . 'obrazky/loga';
    $name = url_title($real_name, '-', true) . '-' . time();

    $uploadResult = $uploadLib->uploadFile($logoFile, $path, $name);

    // Zápis vygenerovaného názvu do pole dat pro databázi
    $data['logo'] = $uploadResult['name'];

    // Provedení změny v databázi
    $raceYearModel->update($id, $data);

    return redirect()->to(base_url('index.php/rocnik/' . $data['year']));
}

    public function delete(int $id)
    {
        $raceYearModel = new RaceYear();
        $zavod = $raceYearModel->find($id);

        if ($zavod) {
            $rok = $zavod->year;
            $raceYearModel->delete($id);

            return redirect()->to(base_url('index.php/rocnik/' . $rok));
        }

        return redirect()->to(base_url());
    }
}