<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePost extends FormRequest
{
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
     * @return array
     */
    public function rules()
    {
        $id = $this->segment(2);

        return [
            'title' => [
                'required',
                'min:3',
                'max:160',
                "unique:posts,title,{$id},id",
            ],
            'image' => ['required', 'image'],
            'content' => [
                'nullable',
                'min:5',
                'max:10000'
            ],
        ];
    }
}
