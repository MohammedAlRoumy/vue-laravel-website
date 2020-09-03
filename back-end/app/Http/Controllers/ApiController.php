<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    protected $module_name;
    protected $model;

    /*-----------------------------*/
    public function storeContact(request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:2|max:50',
                'email' => 'required|email|min:2|max:50',
                'mobile' => 'required|min:6|max:20',
                'message' => 'required|min:2|max:500',
            ]
        );
        if ($validation->fails()) {
            return $this->res($validation->errors(), false, 'we get an error');
        }
        Contact::create($request->all());
        return $this->res([], true, 'thanks you for contact us');
    }

    /* ------------------------------*/
    public function index($module_name)
    {
        try {
            $this->setModuleName($module_name);
            $check = $this->initModel();
            if ($check === false) {
                return $this->res([], false, 'you can not access to this data');
            }
            $data = $this->model->paginate(16);
            return $this->res($data, true, 'here what we found in' . $this->module_name);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        };
    }
    /* ------------------------------*/
    public function getById($module_name, $id)
    {
        try {
            $this->setModuleName($module_name);
            $check = $this->initModel();
            if ($check === false) {
                return $this->res([], false, 'you can not access to this data');
            }
            $data = $this->model->find($id);
            if ($data) {
                return $this->res($data, true, 'here what we found in' . $this->module_name);
            }
            return $this->res([], false, 'you can not find this id');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        };
    }
    /* ------------------------------*/
    // response function
    protected function res($data = [], $status = true, $message = '')
    {
        $data = [
            'payload' => $data,
            'status' => $status,
            'message' => $message
        ];
        return response()->json($data);
    }

    /* ------------------------------*/
    protected function errorResponse($e)
    {
        return $this->res([], false, $e);
    }

    /* ------------------------------*/
    protected function setModuleName($module_name)
    {
        $this->module_name = $module_name;
    }

    /* ------------------------------*/
    protected function initModel()
    {
        $module = Str::lower($this->module_name);
        $module = Str::singular($module);
        $module = Str::camel($module);
        $module = Str::ucfirst($module);
        if (in_array($module, $this->expectModeles())) {
            return false;
        }
        $namespace = 'App\\' . $module;
        $this->model = new $namespace;
    }

    /* ------------------------------*/
    protected function expectModeles()
    {
        return ['Contact'];
    }
}
