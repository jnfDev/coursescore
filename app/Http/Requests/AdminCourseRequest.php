<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class AdminCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules( Request $request )
    {
        if ( ! in_array($request->getMethod(), [ 'POST', 'PATCH' ]) ) {
            return [];
        }

        $rules = [
            'name' => 'required|string|max:60',
            'description' => 'string|max:2000|nullable',
            'url' => 'string|url|nullable',
            'score' => 'not_in',
        ];

        if ('POST' === $request->getMethod()) {
            $rules['user_id'] = 'required|integer';
            $rules['source_id'] = 'required|integer';
        }

        return $rules;
    }
}
