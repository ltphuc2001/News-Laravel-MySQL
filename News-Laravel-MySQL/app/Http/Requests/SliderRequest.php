<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    private $table = "slider";
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $id = $this->id;
        $conditionThumb = 'bail|required|image|max:100';
        $conditionName  = "bail|required|between:5,100|unique:$this->table,name";
        if(!empty($id)){ // Trường hợp edit
            $conditionThumb = 'bail|image|max:100';
            $conditionName  .= ",$id";
        }
        return [
            'name' => $conditionName,
            'description' => 'bail|required',
            'link' => 'bail|required|min:5|url',
            'status' => 'bail|in:active,inactive',
            'thumb' => $conditionThumb,

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name không được rỗng ',
            'name.min' => 'Name :input chiều dài phải có ít nhất :min ký tự',
        ];
    }
}
