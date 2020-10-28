<?php

namespace Detrena\BitrixMigrations\Autocreate\Handlers;

use Detrena\BitrixMigrations\Exceptions\SkipHandlerException;

class OnAfterSetOption extends BaseHandler implements HandlerInterface
{
    protected static $updating = false;

    protected $previous;

    /**
     * Constructor.
     *
     * @param array $params
     * @throws SkipHandlerException
     */
    public function __construct($params)
    {
        $this->fields = $params[0]->getParameters();

        if (self::$updating) {
            self::$updating = false;
            throw new SkipHandlerException("Updating previous value");
        }

        $prev_value = \Bitrix\Main\Config\Option::get($this->fields['moduleId'], '#' . $this->fields['name'], "", $this->fields['siteId']);

        if ((string)$this->fields['value'] === (string)$prev_value) {

            throw new SkipHandlerException("Same value");
        }

        $this->previous = array(
            'moduleId' => $this->fields['moduleId'],
            'name' => "#" . $this->fields['name'],
            'value' => $prev_value,
            'siteId' => $this->fields['siteId'],
        );
        self::$updating = true;
        \Bitrix\Main\Config\Option::set($this->fields['moduleId'], $this->previous['name'], $this->fields['value'], $this->fields['siteId']);

    }

    /**
     * Get migration name.
     *
     * @return string
     */
    public function getName()
    {
        return "auto_set_option_{$this->fields['moduleId']}_{$this->fields['name']}";
    }

    /**
     * Get template name.
     *
     * @return string
     */
    public function getTemplate()
    {
        return 'auto_set_option';
    }

    /**
     * Get array of placeholders to replace.
     *
     * @return array
     */
    public function getReplace()
    {
        return [
            'fields' => var_export($this->fields, true),
            'previous' => var_export($this->previous, true),
        ];
    }
}
