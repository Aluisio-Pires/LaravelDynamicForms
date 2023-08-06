<?php

namespace App\Traits;

use App\Models\Form;
use App\Models\Field;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait HasForms
{
    public function getFieldsAttribute()
    {
        $fields = [];
        foreach ($this->formFields as $field) {
            $fieldName = $field->name;
            $fieldValue = $field->pivot->value;

            if (isset($fields[$fieldName])) {
                if (!is_array($fields[$fieldName])) {
                    $fields[$fieldName] = [$fields[$fieldName]];
                }
                $fields[$fieldName][] = $fieldValue;
            } else {
                $fields[$fieldName] = $fieldValue;
            }
        }
        return $fields === [] ? null : (Object) $fields;
    }

    public function __get($key)
    {
        if ($key !== 'fields' && !$this->getAttribute($key)) {
            return $this->fields?->$key ?? null;
        }

        return parent::__get($key);
    }

    public function formFields() {
        return $this->morphToMany(Field::class, 'fildable')->withPivot('value');
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function saveFields(array $fields)
    {
        $this->validateFields($fields);
        $this->updateFields($fields);
    }

    private function updateFields($fields = [])
    {
        $formFieldQuery = Field::whereHas('forms', function ($query) {
            $query->where('forms.id', $this->form_id);
        });

        $existingFields = $formFieldQuery->whereIn('name', array_keys($fields))->get()->keyBy('name');
        $existingFieldIds = $existingFields->pluck('id')->toArray();

        $this->formFields()->detach($existingFieldIds);

        foreach ($fields as $fieldName => $fieldValue) {
            $formField = $existingFields->get($fieldName);

            if (is_array($fieldValue)) {
                $values = [];
                foreach ($fieldValue as $value) {
                    $values[] = ['field_id' => $formField->id, 'value' => $value];
                }

                $this->formFields()->syncWithoutDetaching($values);
            } else {
                $existingField = $this->formFields()->where('name', $fieldName)->first();
                if ($existingField) {
                    $this->formFields()->updateExistingPivot($existingField->id, ['value' => $fieldValue]);
                } else {
                    $this->formFields()->attach($formField->id, ['value' => $fieldValue]);
                }
            }
        }

        $this->refresh();
    }

    /**
     * @throws ValidationException
     */
    private function validateFields($fields = [])
    {
        $formFields = Field::with('validations')->whereHas('forms', function ($query) {
            $query->where('forms.id', $this->form_id);
        })->get();

        $validations = [];

        foreach ($formFields as $formField) {
            $rules = $formField->validations->map(function ($validation) {
                $rule = $validation->type;
                if ($validation->complement) {
                    $rule .= ':' . $validation->complement;
                }
                return $rule;
            })->implode('|');

            $validations[$formField->name] = isset($validations[$formField->name]) ? $validations[$formField->name] . '|' . $rules : $rules;
        }

        $exceptions = array_diff_key(array_flip(array_keys($fields)), $validations);

        if (!empty($exceptions)) {
            throw ValidationException::withMessages(array_map(function ($key) {
                return "The field {$key} does not exist";
            }, $exceptions));
        }

        $validator = Validator::make($fields, $validations);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->messages());
        }
    }


}
