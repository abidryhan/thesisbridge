<?php

namespace App\Http\Requests;

use App\Models\ThesisGroup;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->currentGroup() !== null;
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            redirect()->route('thesis-groups.create')
                ->with('error', 'You need to be in a thesis group before submitting a proposal.')
        );
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'objectives' => 'required|string',
            'methodology' => 'required|string',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->currentGroup()?->proposal()->exists()) {
                $validator->errors()->add('title', 'Your group has already submitted a proposal.');
            }
        });
    }

    protected function currentGroup(): ?ThesisGroup
    {
        $student = auth()->user()->student;

        return $student?->thesisGroups->first();
    }
}
