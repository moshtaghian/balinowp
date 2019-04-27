<?php
class BalinoServicesController extends WP_List_Table
{
    private static $tablename = "service";
    /** Class constructor */
    public function __construct()
    {
        parent::__construct([
            'singular' => __('Service', 'balino-plugin'), //singular name of the listed records
            'plural'   => __('Services', 'balino-plugin'), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ]);
    }
    private static $ssssql;
    /**
     * Retrieve services data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public function get_data($per_page = 5, $page_number = 1)
    {
        global $wpdb;
        $sql = "SELECT service_id, service_title, service_price, service_isMulti, service_isVisit  FROM {$wpdb->prefix}" . self::$tablename;
        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
        // $this->ssssql = $sql;
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        return $result;
    }


    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public function record_count()
    {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}" . self::$tablename;
        return $wpdb->get_var($sql);
    }
    /** Text displayed when no customer data is available */
    public function no_items()
    {
        // print_r(self::get_data());
        // var_dump($this->items);
        _e('No Services avaliable.', 'balino-plugin');
    }
    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        return $item[$column_name];
        switch ($column_name) {
            case 'service_id':
            case 'service_title':
            case 'service_price':
            case 'service_isMulti':
            case 'service_isVisit':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'service_id' => __('service_id', 'balino-plugin'),
            'service_title'    => __('service_title', 'balino-plugin'),
            'service_price'      => __('service_price', 'balino-plugin'),
            'service_isMulti'      => __('service_isMulti', 'balino-plugin'),
            'service_isVisit'      => __('service_isVisit', 'balino-plugin')
        );
        return $columns;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items()
    {
        // $this->_column_headers = $this->get_column_info();
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        /** Process bulk action */
        // $this->process_bulk_action();

        $per_page     = $this->get_items_per_page('services_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items  = $this->record_count();
        
        $this->set_pagination_args([
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ]);
        $this->items = $this->get_data($per_page, $current_page);
        // self::$PN = $current_page;
        // self::$PP = $per_page;
    }
}
