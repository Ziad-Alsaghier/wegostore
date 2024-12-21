<?php

namespace App;

trait Translation
{
    //
    public function updateOrCreateTranslations($model, array $translations)
{
    foreach ($translations as $translation) {
        $model->translations()->updateOrCreate(
            [
                'locale' => $translation['locale'], // Match existing translation by locale
                'key' => $translation['key'],       // Match existing translation by key
            ],
            [
                'value' => $translation['value'],   // Update or create with this value
            ]
        );
    }
}
}
