<?php

declare(strict_types=1);

namespace core\datatable;

use core\Pagination;

class Datatable
{
    const TYPE_TEXT = 'text';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TIME = 'time';
    const TYPE_BOOLEAN = 'bool';
    const TYPE_SELECT = 'select';
    const TYPE_CUSTOM = 'custom';

    private array $options = [
        'data_table' => '',
        'data_sql' => '',
        'items_per_page' => 10,
        'default_order_id' => null,
        'default_order_dir' => 'asc',
        'columns' => [],
    ];

    private $totalRows = 0;

    /**
     * Current page, starts from 1
     *
     * @var int
     */
    private $page = 0;

    public function __construct(array $options = [])
    {
        $this->options = array_replace_recursive($this->options, $options);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function getOption($key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * @param string $id Field id
     * @param string $dbFieldName Database field name
     * @param string $label Column label
     * @param string $type
     * @param array $options
     *
     * @return self
     */
    public function addColumn($id, $dbFieldName, $label, $type = self::TYPE_TEXT, $options = [])
    {
        $column = [
            'id' => $id,
            'field_name' => $dbFieldName,
            'label' => $label,
            'type' => $type,
        ];

        if (isset($options['width']) && !empty($options['width'])) {
            $column['width'] = $options['width'];
        }

        if (isset($options['class']) && !empty($options['class'])) {
            $column['class'] = $options['class'];
        }

        if (isset($options['cellContent']) && !empty($options['cellContent'])) {
            $column['cellContent'] = $options['cellContent'];
        }

        $column['sortable'] = (bool)($options['sortable'] ?? false);
        $column['searchable'] = (bool)($options['searchable'] ?? false);

        $this->options['columns'][] = $column;

        return $this;
    }

    private function getColumnNameById($id)
    {
        foreach ($this->options['columns'] as $column) {
            if ($column['id'] === $id) {
                return $column['field_name'];
            }
        }

        return null;
    }

    private function getColumnIdByName($name)
    {
        foreach ($this->options['columns'] as $column) {
            if ($column['field_name'] === $name) {
                return $column['id'];
            }
        }

        return null;
    }

    public function getRows()
    {
        // get current pagination page from request
        $page = request()->get('page', 1);
        if ($page < 1) {
            $page = 1;
        }

        $this->page = $page;


        // get current pagination limit from request
        $limit = $this->getOption('items_per_page', 10);

        // get current pagination offset from request
        $offset = ($page - 1) * $limit;

        // set limit and offset
        if ($page > 1) {
            $limitSql = " LIMIT {$offset}, {$limit}";
        } else {
            $limitSql = " LIMIT {$limit}";
        }

        // get current sort column from request
        $sortColumn = request()->get('sortColumn', $this->getOption('default_order_id'));
        // get database column name from options array
        $sortColumn = $this->getColumnNameById($sortColumn);


        // get current sort direction from request
        $sortDirection = request()->get('sortDirection', 'asc');

        // get current search query from request
        // $searchQuery = request()->get('searchQuery', '');

        // get current search columns from request
        // $searchColumns = request()->get('searchColumns', []);

        // make sql query

        // make order by sql
        $orderBySql = '';
        if (!empty($sortColumn)) {
            $orderBySql = ' ORDER BY ' . $sortColumn . ' ' . $sortDirection;
        }

        // make filter sql
        $filterSql = '';
        if (!empty($searchQuery)) {
            $filterSql = ' WHERE ' . $sortColumn . ' ' . $sortDirection;
        }

        // if search query is set then use it
        if (isset($this->options['data_sql']) && !empty($this->options['data_sql'])) {
            $sql = $this->options['data_sql'];
        } else {
            // get fields names from fields array
            $fieldsNames = array_map(function ($column) {
                return $column['field_name'];
            }, $this->options['columns']);

            // var_dump($fieldsNames);

            // combine selected fields to string
            $fields = implode(', ', array_filter($fieldsNames));

            $sql = "SELECT SQL_CALC_FOUND_ROWS {$fields} FROM {$this->options['data_table']} ";
        }


        $sql .= $orderBySql . $limitSql;

        //var_dump($sql);
        //exit();

        $rows = db()->query($sql);

        // get row count
        $rowCountResult = db()->queryOne("SELECT FOUND_ROWS() AS total");
        $rowCount = $rowCountResult['total'] ?? 0;
        $this->totalRows = $rowCount;

        //var_dump($rowCount);


        // $all_rows = db::query_first("SELECT FOUND_ROWS() as `count`;");

        //var_dump($rows);

        return $rows;
    }

    public function getHtml()
    {
        $urlParams = $_GET;
        $dir = $this->options['default_order_dir'] ?? 'asc';

        $rows = $this->getRows();

        $html = '<table class="data-table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">';

        $html .= '<thead>';
        $html .= '<tr>';

        foreach ($this->options['columns'] as $column) {
            // $html .= '<th>' . $column['label'] . '</th>';

            if ($column['sortable']) {
                // add link to sort column
                // default_order_dir

                $urlParams['sortColumn'] = $this->getColumnIdByName($column['field_name']);
                $urlParams['sortDirection'] = ($urlParams['sortDirection'] ?? $dir) === 'asc' ? 'desc' : 'asc';

                // http_build_query($urlParams)

                $html .= '<th>';
                $html .= '<a href="?' . http_build_query($urlParams) . '" class="sort-desc2">' . $column['label']; // . '</a>';
                $html .= $urlParams['sortDirection'] === 'asc' ? '<span>▼</span>' : '<span>▲</span>' ;
                $html .= '</a></th>';

                // $html .= '<th><a href="?sortColumn=' . $column['field_name'] . '&sortDirection=asc">' . $column['label'] . '</a></th>';
            } else {
                $html .= '<th>' . $column['label'] . '</th>';
            }
        }

        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody>';

        foreach ($rows as $row) {
            $html .= '<tr>';

            foreach ($this->options['columns'] as $column) {
                $val = '';
                if ($column['field_name']) {
                    $val = $this->processFields($row[$column['field_name']], $column['type'], $column, $row);
                }

                // call cellContent
                if (isset($column['cellContent']) && is_callable($column['cellContent'])) {
                    $val = $column['cellContent']($val, $row);
                }

                $html .= '<td>' . $val . '</td>';

                // $html .= '<td>' . $row[$column['field_name']] . '</td>';
            }

            $html .= '</tr>';
        }

        $html .= '</tbody>';

        $html .= '</table>';

        $html .= '<div class="row">';
        $html .= '<div class="col-md-5 col-sm-12">';
        // $html .= '<div class="dataTables_info" id="sample_1_info" role="status" aria-live="polite">Showing 1 to 10 of ' . $this->tatalRows . ' entries</div>';
        $html .= '</div>';

        $html .= $this->getPaginationHtml();

        return $html;
    }

    public function getPaginationHtml()
    {
        $pagination = new Pagination($this->totalRows, $this->page, $this->getOption('items_per_page', 10));
        return $pagination->getPaginationHtml();

    }

    private function processFields($value, $type, $column, $row)
    {
        if ($type === self::TYPE_TEXT) {
            return $value;
        }

        if ($type === self::TYPE_BOOLEAN) {
            return $value ? 'Yes' : 'No';
        }

        return $value;
    }
}
