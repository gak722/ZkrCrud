<?php

namespace Larapi\Zkrcrud\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZkrRequest extends FormRequest
{
    public function authorize()
    {
        return true; // You can implement your authorization logic here
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if (!$this->route()) {
            return [];
        }
        if ($this->route()->getActionMethod() === 'store') {
            return array_merge($this->commonRules(), $this->storeRules());
        }
        if ($this->route()->getActionMethod() === 'update') {
            return array_merge($this->commonRules(), $this->updateRules());
        }
        return [];
    }
    /**
     * Get the custom messages for the validation rules.
     *
     * @return array
     */
    public function messages()
    {
        if (!$this->route()) {
            return [];
        }
        if ($this->route()->getActionMethod() === 'store') {
            return array_merge($this->commonMessages(), $this->storeMessages());
        }
        if ($this->route()->getActionMethod() === 'update') {
            return array_merge($this->commonMessages(), $this->updateMessages());
        }
    }
    /**
     * Common rules for all endpoints.
     *
     * @return array
     */
    public function commonRules(): array
    {
        return [];
    }

    /**
     * Rules for the "store" (POST) endpoint.
     *
     * @return array
     */
    public function storeRules(): array
    {
        return [];
    }
    /**
     * Rules for the "update" (POST) endpoint.
     *
     * @return array
     */
    public function updateRules(): array
    {
        return [];
    }
    /**
     * Default messages for the request.
     *
     * @return array
     */
    public function commonMessages(): array
    {
        return [];
    }
    /**
     * Messages for the "store" (POST) endpoint.
     *
     * @return array
     */
    function storeMessages(): array
    {
        return [];
    }
    /**
     * Messages for the "update" (POST) endpoint.
     *
     * @return array
     */
    function updateMessages(): array
    {
        return [];
    }
}
