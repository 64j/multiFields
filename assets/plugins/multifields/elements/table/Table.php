<?php

namespace Multifields\Elements\Table;

class Table extends \Multifields\Base\Elements
{
    protected $styles = 'view/css/table.css';
    protected $scripts = 'view/js/table.js';
    protected $tpl = 'view/table.tpl';

    protected $actions = [
        'add',
        'move',
        'del',
    ];

    protected $types = [
        'separator' => '',
        'text' => '[+lang.type.text+]',
        'number' => '[+lang.type.number+]',
        'date' => '[+lang.type.date+]',
        'image' => '[+lang.type.image+]',
        'file' => '[+lang.type.file+]',
    ];

    protected $template = '
        <div id="[+id+]" class="mf-table col col-12 row m-0 [+class+]" data-type="table" data-name="[+name+]" [+attr+]>
            [+title+]
            [+value+]
            [+actions+]
            <div class="row m-0 col-12 p-0">
                <div class="mf-column-menu contextMenu">
                    <div onclick="Multifields.elements.table.addColumn(event);" data-action="addColumn">
                        <i class="fa fa-plus fa-fw"></i> [+lang.add_column+]
                    </div>
                    <div onclick="Multifields.elements.table.delColumn(event);" data-action="delColumn">
                        <i class="fa fa-minus fa-fw"></i> [+lang.del_column+]
                    </div>
                    [+types+]
                </div>
            </div>
            <div class="mf-items mf-items-table row m-0 col-12 p-0 [+items.class+]">
                <div class="position-relative w-100 m-0 p-0">
                    <div class="col-resize"></div>
                    <table class="table table-sm data table-hover table-bordered">
                        [+items+]
                    </table>
                </div>
            </div>
        </div>';

    protected function setValue()
    {
        if (isset(self::$params['value']) && self::$params['value'] !== false) {
            if (is_bool(self::$params['value'])) {
                self::$params['value'] = '';
            }

            self::$params['value'] = '
            <div class="mf-value mf-text">
                <input type="text" class="form-control" name="' . self::$params['id'] . '_value" value="' . stripcslashes(self::$params['value']) . '"' . (isset(self::$params['placeholder']) ? ' placeholder="' . self::$params['placeholder'] . '"' : '') . ' data-value>
            </div>';
        }
    }

    protected function setMenu()
    {
        if (empty(self::$params['types'])) {
            self::$params['types'] = $this->types;
        }

        foreach (self::$params['types'] as $k => &$v) {
            if (stripos($k, 'separator') !== false) {
                $v = '<div class="separator cntxMnuSeparator"></div>';
            } else {
                $v = '<div onclick="Multifields.elements.table.setType(event, \'' . $k . '\');" data-type="' . $k . '">' . $v . '</div>';
            }
        }

        self::$params['types'] = implode('', self::$params['types']);
    }

    /**
     * @return string
     */
    public function render()
    {
        if (!isset(self::$params['items'])) {
            self::$params['items'] = '';
        }

        $this->setValue();
        $this->setMenu();

        parent::setActions();

        return parent::render();
    }

    /**
     * @param array $params
     * @return string
     */
    public function actionGetElementByType($params = [])
    {
        $params['html'] = $this->renderFormElement([
            'type' => $params['type'],
            'name' => $params['name'],
            'id' => $params['id'],
        ]);

        return json_encode($params, JSON_UNESCAPED_UNICODE);
    }
}
