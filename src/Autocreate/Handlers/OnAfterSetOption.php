<?php

namespace Detrena\BitrixMigrations\Autocreate\Handlers;

use Detrena\BitrixMigrations\Exceptions\SkipHandlerException;

class OnAfterSetOption extends BaseHandler implements HandlerInterface
{

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
        $this->previous = \Bitrix\Main\Config\Option::get($this->fields['moduleId'], '#' . $this->fields['name'], "", $this->fields['siteId']);

        if ($this->fields['value'] === $this->previous['value']) {
            throw new SkipHandlerException("Same value");
        }
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
