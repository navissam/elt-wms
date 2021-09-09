<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Swc_temp_model;
use PhpParser\Node\Stmt\Continue_;

class Switch_ extends BaseController
{
    protected $swc_temp_model;
    protected $template_header = [
        '序号',
        '公司',
        '物料代码',
        '物料名称与规格型号',
        '单位',
        '变更数量',
        '原库位',
        '现库位',
        '原库位数量',
        '现库位数量',
        '备注'
    ];
    protected $field_header = [
        'num',
        'company',
        'goods_id',
        'name_type',
        'unit',
        'qty',
        'from_location',
        'to_location',
        'old_stock',
        'new_stock',
        'remark'
    ];
    public function __construct()
    {
        $this->swc_temp_model = new Swc_temp_model();
    }

    public function index()
    {
        $data['active']['swc'] = true;
        $data['isset_temp'] = ($this->swc_temp_model->countAllResults() > 0);
        // $data['duplicate'] = count($this->swc_temp_model->getDuplicate());
        // $data['inconsistent'] = count($this->swc_temp_model->getInconsistent());
        // $data['new_goods'] = count($this->swc_temp_model->getNewGoods());
        // $data['new_location'] = count($this->swc_temp_model->getNewLocation());
        // $data['new_company'] = count($this->swc_temp_model->getNewCompany());
        // $data['invalid_qty'] = count($this->swc_temp_model->getInvalidQty());
        return view('tyck/switch/v_switch_index', $data);
    }

    public function duplicate()
    {
        return json_encode([
            'data' => $this->swc_temp_model->getDuplicate()
        ]);
    }
    public function new_company()
    {
        return json_encode([
            'data' => $this->swc_temp_model->getNewCompany()
        ]);
    }
    public function new_goods()
    {
        return json_encode([
            'data' => $this->swc_temp_model->getNewGoods()
        ]);
    }
    public function new_location()
    {
        return json_encode([
            'data' => $this->swc_temp_model->getNewLocation()
        ]);
    }
    public function inventory_null()
    {
        return json_encode([
            'data' => $this->swc_temp_model->getInventoryNull()
        ]);
    }
    public function min_qty()
    {
        return json_encode([
            'data' => $this->swc_temp_model->getMinQty()
        ]);
    }

    public function temp()
    {
        return json_encode([
            'data' => $this->swc_temp_model->findAll()
        ]);
    }

    public function analysis()
    {
        $data['duplicate'] = count($this->swc_temp_model->getDuplicate());
        $data['inventory_null'] = count($this->swc_temp_model->getinventoryNull());
        $data['new_goods'] = count($this->swc_temp_model->getNewGoods());
        $data['new_location'] = count($this->swc_temp_model->getNewLocation());
        $data['new_company'] = count($this->swc_temp_model->getNewCompany());
        $data['min_qty'] = count($this->swc_temp_model->getMinQty());
        return json_encode([
            'data' => $data
        ]);
    }

    private function validateTemplate(array $excelHeader)
    {
        $header = $this->template_header;

        if (count($header) === count($excelHeader)) {
            foreach ($excelHeader as $key => $value) {
                if ($value != $header[$key])
                    return false;
            }
            return true;
        } else return false;
    }

    public function upload()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/switch_');
        }
        $validation =  \Config\Services::validation();

        $valid = [];
        $uploaded = false;
        if ($this->request->getFile('file') != null) {
            $valid['file'] = [
                'rules' => 'ext_in[file,xlsx,xls]',
                'errors' => [
                    'ext_in' => '文件扩展名不符, 必须Excel文件扩展名（xls, xlsx）'
                ]
            ];
            $uploaded = true;
        }
        $validation->setRules($valid);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'invalid',
                'errors' => $uploaded ? $validation->getErrors() : ['file' => '文件未选']
            ]);
        }
        $file_excel = $this->request->getFile('file');
        $ext = $file_excel->getClientExtension();
        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($file_excel);
        $data = $spreadsheet->getActiveSheet()->toArray();
        // dd($data);
        $swc_temps = [];
        foreach ($data as $d => $row) {
            if ($d == 0) {
                if ($this->validateTemplate($row)) {
                    continue;
                } else {
                    return json_encode([
                        'status' => 'invalid',
                        'errors' => ['file' => '模板不符']
                    ]);
                }
            }

            $blank = false;
            foreach ($row as $index => $cell) {
                if ($index != 10)
                    $blank = $blank || (trim($cell) === '');
            }
            if ($blank) continue;

            $swc_temp = [];
            foreach ($row as $index => $cell) {
                $swc_temp[$this->field_header[$index]] = $cell;
            }
            array_push($swc_temps, $swc_temp);
        }
        try {
            if (count($swc_temps) == 0)
                return json_encode([
                    'status' => 'error',
                    'msg' => '数据不全'
                ]);
            $this->swc_temp_model->insertBatch($swc_temps);
            // return json_encode([
            //     'status' => 'error',
            //     'msg' => '',
            //     'data' => $swc_temps
            // ]);
        } catch (\Exception $e) {
            // $this->syslog->insert([
            //     'controller' => 'tyck/switch_',
            //     'method' => 'upload',
            //     'userID' => session()->get('userID') ?? '',
            //     'status' => 0,
            //     'response' => $e->getMessage()
            // ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        // $this->syslog->insert([
        //     'controller' => 'user',
        //     'method' => 'save',
        //     'userID' => session()->get('userID') ?? '',
        //     'status' => 1,
        // ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
    public function update()
    {
        $id = $this->request->getPost('goods_id');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/switch_');
        }
        $validation =  \Config\Services::validation();
        $valid = [
            'company' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'goods_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'qty' => [
                'rules' => 'required|greater_than[0]|numeric',
                'errors' => [
                    'greater_than' => '不可低于或等于零',
                    'required' => '不允许为空',
                    'numeric' => '必须数字',
                ]
            ],
            'from_location' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'to_location' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
        ];
        $validation->setRules($valid);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'invalid',
                'errors' => $validation->getErrors(),
            ]);
        }

        try {
            $this->swc_temp_model->update($this->request->getPost('swc_id'), $this->request->getPost());
        } catch (\Exception $e) {
            // $this->syslog->update([
            //     'controller' => 'goods',
            //     'method' => 'update',
            //     'userID' => session()->get('userID') ?? '',
            //     'status' => 0,
            //     'data' => json_encode($id),
            //     'response' => $e->getMessage()
            // ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'goods',
            'method' => 'update',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => json_encode($id)
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function delete()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/switch_');
        }
        try {
            $id = $this->request->getPost('swc_id') ?? '';
            $this->swc_temp_model->delete($id);
        } catch (\Exception $e) {
            // $this->syslog->insert([
            //     'controller' => 'goods',
            //     'method' => 'delete',
            //     'userID' => session()->get('userID') ?? '',
            //     'status' => 0,
            //     'data' => 'goodsID = ' .  $id,
            //     'response' => $e->getMessage()
            // ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        // $this->syslog->insert([
        //     'controller' => 'goods',
        //     'method' => 'delete',
        //     'userID' => session()->get('userID') ?? '',
        //     'status' => 1,
        //     'data' => 'goodsID = ' . $id
        // ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
    public function cancel()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/switch_');
        }
        try {
            if ($this->request->getPost('cancel')) {
                $this->swc_temp_model->truncate();
            } else {
                return json_encode([
                    'status' => 'error',
                    'msg' => 'cancel not true'
                ]);
            };
        } catch (\Exception $e) {
            // $this->syslog->insert([
            //     'controller' => 'goods',
            //     'method' => 'delete',
            //     'userID' => session()->get('userID') ?? '',
            //     'status' => 0,
            //     'data' => 'goodsID = ' .  $id,
            //     'response' => $e->getMessage()
            // ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        // $this->syslog->insert([
        //     'controller' => 'goods',
        //     'method' => 'delete',
        //     'userID' => session()->get('userID') ?? '',
        //     'status' => 1,
        //     'data' => 'goodsID = ' . $id
        // ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function import()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/switch_');
        }
        try {
            $this->swc_temp_model->migrate();
        } catch (\Exception $e) {
            // $this->syslog->insert([
            //     'controller' => 'goods',
            //     'method' => 'delete',
            //     'userID' => session()->get('userID') ?? '',
            //     'status' => 0,
            //     'data' => 'goodsID = ' .  $id,
            //     'response' => $e->getMessage()
            // ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        // $this->syslog->insert([
        //     'controller' => 'goods',
        //     'method' => 'delete',
        //     'userID' => session()->get('userID') ?? '',
        //     'status' => 1,
        //     'data' => 'goodsID = ' . $id
        // ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
