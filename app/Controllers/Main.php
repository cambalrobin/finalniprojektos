<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Race;
use App\Models\RaceType;
use App\Models\RaceYear;
use App\Libraries\ArrayLib;
use Config\KonfiguracniSoubor;

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
            'pager' => $pager
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

    // Rok je vždy zafixovaný z URL (např. 2024 nebo 2026)
    $years2 = [$zvolenyRok => $zvolenyRok];

    // Načtení kategorií
    $categories = $db->table('race_type')->distinct()->findColumn('category') ?? [];
    $categories2 = $arrayLib->setValueToKey($categories);

    // Načtení závodů (ploché pole pro spolehlivý Select2)
    $zavodyV = $raceModel
        ->table('cyklo_race')
        ->select('id, default_name, type')
        ->orderBy('default_name', 'ASC')
        ->get()
        ->getResultArray();

    $data = [
        "kategorie"    => $categories2,
        "rocniky2"     => $years2,
        "zavodyV" => $zavodyV,
        "isFixed"      => true,
        "minDate"      => $zvolenyRok . "-01-01", 
        "maxDate"      => $zvolenyRok . "-12-31"  
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
        
        // Zpracování souboru s logem
        $logoFile = $this->request->getFile('logo');
        $logoName = '';

        $raceModel = new RaceYear();

        $data = [
            'id_race'    => $id_race, 
            'real_name'  => $real_name,
            'year'       => $year,
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'category'   => $category,
            'logo'       => $logoName,
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

    // Načteme záznam z databáze jako objekt
    $zavodProEditaci = $raceYearModel->find($id);

    $zvolenyRok = $zavodProEditaci->year; 
    $years2 = [$zvolenyRok => $zvolenyRok];

    // NOVOST: Načtení kategorií pro dropdown (stejně jako v metodě add)
    $categories = $raceYearModel->table('race_type')->distinct()->findColumn('category') ?? [];
    $categories2 = $arrayLib->setValueToKey($categories);

    // Seznam závodů pro select
    $zavodyV = $raceModel
        ->table('cyklo_race')
        ->select('id, default_name, type') // Přidán type, pokud ho šablona vyžaduje
        ->orderBy('default_name', 'ASC')
        ->get()
        ->getResultArray();

    $data = [
        "country"   => $zavodProEditaci,
        "rocniky2"  => $years2,
        "zavodyV"   => $zavodyV,
        "kategorie" => $categories2 // Posíláme kategorie do View
    ];

    echo view('races/edit', $data);
}
public function update($id)
{
    $raceYearModel = new RaceYear();

    $data = [
        'real_name'  => $this->request->getPost('real_name'),
        'country'    => $this->request->getPost('country'),
        'category'   => $this->request->getPost('category'),
        'start_date' => $this->request->getPost('start_date'),
        'end_date'   => $this->request->getPost('end_date'),
        'id_race'    => $this->request->getPost('id_race'),
        'year'       => $this->request->getPost('year'),
    ];

    $raceYearModel->update($id, $data);

    // Přesměrujeme uživatele zpět na přehled daného ročníku
    return redirect()->to(base_url('rocnik/' . $data['year']));
}

public function delete(int $id)
    {
        $raceYearModel = new RaceYear();

        // 1. Najdeme si závod jako objekt, abychom bezpečně zjistili rok před Soft Delete akcí
        $zavod = $raceYearModel->find($id);

        if ($zavod) {
            $rok = $zavod->year;

            // 2. Provede se Soft Delete (pouze se zapíše timestamp do deleted_at v DB)
            $raceYearModel->delete($id);

            // 3. Přesměrujeme zpět na přehled daného ročníku
            return redirect()->to(base_url('index.php/rocnik/' . $rok));
        }

        return redirect()->to(base_url());
    }
}