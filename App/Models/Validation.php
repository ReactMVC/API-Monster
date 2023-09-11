<?php

namespace Monster\App\Models;

class Validation
{
    private $data;
    private $rules;
    private $errors;
    private $lang;

    public function __construct(array $data, array $rules, string $lang = 'en')
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->errors = [];
        $this->lang = $this->loadLanguageFile($lang);
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field => $rule) {
            $rules = explode('|', $rule);

            foreach ($rules as $singleRule) {
                [$ruleName, $params] = $this->parseRule($singleRule);

                if (!$this->executeRule($field, $ruleName, $params)) {
                    $this->addError($field, $ruleName, $params);
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function parseRule(string $rule): array
    {
        $params = [];

        if (strpos($rule, ':') !== false) {
            [$ruleName, $paramsString] = explode(':', $rule, 2);
            $params = explode(',', $paramsString);
        } else {
            $ruleName = $rule;
        }

        return [$ruleName, $params];
    }

    private function executeRule(string $field, string $ruleName, array $params): bool
    {
        $value = $this->data[$field] ?? null;

        switch ($ruleName) {
            case 'required':
                return isset($value) && $value !== '';
            case 'min':
                return strlen($value) >= $params[0];
            case 'max':
                return strlen($value) <= $params[0];
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            case 'number':
                return is_numeric($value);
            case 'url':
                return filter_var($value, FILTER_VALIDATE_URL) !== false;
            case 'regex':
                return preg_match($params[0], $value) === 1;
                // Add more validation rules as needed
            default:
                return false;
        }
    }

    private function addError(string $field, string $ruleName, array $params)
    {
        $message = $this->lang[$ruleName] ?? $this->lang['error'] ?? 'Invalid value';
        $replacements = [':key' => $field, ':value' => $params[0] ?? ''];

        foreach ($replacements as $key => $value) {
            $message = str_replace($key, $value, $message);
        }

        $this->errors[$field][] = $message;
    }

    private function loadLanguageFile(string $lang): array
    {
        $path = __DIR__ . "/../../routes/lang/errors/{$lang}.php";

        if (file_exists($path)) {
            return include $path;
        }

        // Fallback language file
        return include __DIR__ . '/../../routes/lang/errors/en.php';
    }
}
