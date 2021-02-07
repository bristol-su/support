<?php

namespace BristolSU\Support\Theme\Settings;

use BristolSU\Support\Settings\Definition\GlobalSetting;
use FormSchema\Schema\Field;
use Twigger\Blade\Foundation\ThemeStore;

class ChosenTheme extends GlobalSetting
{
    /**
     * @var ThemeStore
     */
    protected ThemeStore $themeStore;

    public function __construct(ThemeStore $themeStore)
    {
        $this->themeStore = $themeStore;
    }

    /**
     * Return the validation rules for the setting.
     *
     * You may also override the validator method to customise the validator further
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            $this->inputName() => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!$this->themeStore->hasTheme($value)) {
                        $fail('The ' . $attribute . ' is not a valid theme.');
                    }
                }
            ]
        ];
    }

    /**
     * The key for the setting.
     *
     * @return string
     */
    public function key(): string
    {
        return 'appearance.theme.chosen-theme';
    }

    /**
     * The default value of the setting.
     *
     * @throws \Exception
     * @return mixed
     */
    public function defaultValue()
    {
        $themes = $this->themeStore->allThemes();
        if (empty($themes)) {
            throw new \Exception('No theme has been registered');
        }

        return $themes[array_keys($themes)[0]]->id();
    }

    /**
     * The field schema to show the user when editing the value.
     *
     * @return Field
     */
    public function fieldOptions(): Field
    {
        $themes = $this->themeStore->allThemes();
        $options = [];
        foreach ($themes as $theme) {
            $options[] = ['id' => $theme->id(), 'name' => $theme->name()];
        }

        return \FormSchema\Generator\Field::select($this->inputName())
            ->label('Theme')
            ->default($this->defaultValue())
            ->hint('The theme to use to render your site')
            ->help('You can choose any theme, or register a new one.')
            ->values(
                $options
            )
            ->getSchema();
    }
}
