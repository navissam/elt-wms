<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Sti_temp_model;
use PhpParser\Node\Stmt\Continue_;

class Stock_in extends BaseController
{
    protected $sti_temp_model;
    protected $template_header = [
        '序号',
        '公司',
        '物料代码',
        '物料名称与规格型号',
        '单位',
        '入库量',
        '库位',
        '备注'
    ];
    protected $field_header = [
        'num',
        'company',
        'goods_id',
        'name_type',
        'unit',
        'qty',
        'location',
        'remark'
    ];
    public function __construct()
    {
        $this->sti_temp_model = new Sti_temp_model();
    }

    public function index()
    {
        $data['active']['sti'] = true;
        $data['isset_temp'] = ($this->sti_temp_model->countAllResults() > 0);
        // $data['duplicate'] = count($this->sti_temp_model->getDuplicate());
        // $data['inconsistent'] = count($this->sti_temp_model->getInconsistent());
        // $data['new_goods'] = count($this->sti_temp_model->getNewGoods());
        // $data['new_location'] = count($this->sti_temp_model->getNewLocation());
        // $data['new_company'] = count($this->sti_temp_model->getNewCompany());
        // $data['invalid_qty'] = count($this->sti_temp_model->getInvalidQty());
        return view('tyck/stock_in/v_stock_in_index', $data);
    }

    public function duplicate()
    {
        return json_encode([
            'data' => $this->sti_temp_model->getDuplicate()
        ]);
    }
    public function new_company()
    {
        return json_encode([
            'data' => $this->sti_temp_model->getNewCompany()
        ]);
    }
    public function new_goods()
    {
        return json_encode([
            'data' => $this->sti_temp_model->getNewGoods()
        ]);
    }
    public function new_location()
    {
        return json_encode([
            'data' => $this->sti_temp_model->getNewLocation()
        ]);
    }
    public function inconsistent()
    {
        return json_encode([
            'data' => $this->sti_temp_model->getInconsistent()
        ]);
    }
    public function invalid_qty()
    {
        return json_encode([
            'data' => $this->sti_temp_model->getInvalidQty()
        ]);
    }

    public function temp()
    {
        return json_encode([
            'data' => $this->sti_temp_model->findAll()
        ]);
    }

    public function analysis()
    {
        $data['duplicate'] = count($this->sti_temp_model->getDuplicate());
        $data['inconsistent'] = count($this->sti_temp_model->getInconsistent());
        $data['new_goods'] = count($this->sti_temp_model->getNewGoods());
        $data['new_location'] = count($this->sti_temp_model->getNewLocation());
        $data['new_company'] = count($this->sti_temp_model->getNewCompany());
        $data['invalid_qty'] = count($this->sti_temp_model->getInvalidQty());
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
            return redirect()->to('/tyck/stock_in');
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
        $sti_temps = [];
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
                if ($index != 7)
                    $blank = $blank || (trim($cell) === '');
            }
            if ($blank) continue;

            $sti_temp = [];
            foreach ($row as $index => $cell) {
                $sti_temp[$this->field_header[$index]] = $cell;
            }
            array_push($sti_temps, $sti_temp);
        }
        try {
            if (count($sti_temps) == 0)
                return json_encode([
                    'status' => 'error',
                    'msg' => '数据不全'
                ]);
            $this->sti_temp_model->insertBatch($sti_temps);
            // return json_encode([
            //     'status' => 'error',
            //     'msg' => '',
            //     'data' => $sti_temps
            // ]);
        } catch (\Exception $e) {
            // $this->syslog->insert([
            //     'controller' => 'tyck/stock_in',
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
            return redirect()->to('/tyck/stock_in');
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
            'name_type' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'unit' => [
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
            'location' => [
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
            $this->sti_temp_model->update($this->request->getPost('sti_id'), $this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->update([
                'controller' => 'stock_in',
                'method' => 'update',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => json_encode($id),
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'stock_in',
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
            return redirect()->to('/tyck/stock_in');
        }
        try {
            $id = $this->request->getPost('sti_id') ?? '';
            $this->sti_temp_model->delete($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'stock_in',
                'method' => 'delete',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                // 'data' => 'goodsID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'stock_in',
            'method' => 'delete',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            // 'data' => 'goodsID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
    public function cancel()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/stock_in');
        }
        try {
            if ($this->request->getPost('cancel')) {
                $this->sti_temp_model->truncate();
            } else {
                return json_encode([
                    'status' => 'error',
                    'msg' => 'cancel not true'
                ]);
            };
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'stock_in',
                'method' => 'cancel',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                // 'data' => 'goodsID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'stock_in',
            'method' => 'cancel',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            // 'data' => 'goodsID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function import()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/stock_in');
        }
        try {
            if (count($this->sti_temp_model->getNewGoods()) > 0) {
                $this->sti_temp_model->insert_goods();
            }
            $this->sti_temp_model->migrate();
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'stock_in',
                'method' => 'import',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                // 'data' => 'goodsID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'stock_in',
            'method' => 'import',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            // 'data' => 'goodsID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
