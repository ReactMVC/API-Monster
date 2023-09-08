<?php

namespace Monster\App\Models;

class Lang
{
    private $language;
    private $langData;

    public function __construct($language = "en")
    {
        $this->language = $language;
        $this->loadLanguageData();
    }

    public function loadLanguageData()
    {
        $langFile = __DIR__ . '/../../routes/lang/' . $this->language . '.php';
        if (file_exists($langFile)) {
            $this->langData = include $langFile;
        } else {
            throw new \Exception("Language file not found for {$this->language}");
        }
    }

    public function get($key, $variables = [])
    {
        $value = $this->langData[$key] ?? $key;

        foreach ($variables as $variable => $replacement) {
            $value = str_replace(':' . $variable, $replacement, $value);
        }

        return $value;
    }
}
