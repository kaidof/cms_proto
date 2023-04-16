<?php

declare(strict_types=1);

namespace core;

class Pagination
{
    private $total;
    private $page;
    private $limit;
    private $num_links;
    private $url;
    private $text_first;
    private $text_last;
    private $text_next;
    private $text_prev;
    private $style_links;
    private $style_results;

    private $totalItems; // Total number of items
    private $perPage; // Number of items per page
    private $currentPage; // Current page
    private $totalPages; // Total number of pages



    public function __construct(
        $total,
        $page = 1,
        $limit = 20,
        $num_links = 5,
        $url = '',
        $text_first = 'First',
        $text_last = 'Last',
        $text_next = 'Next',
        $text_prev = 'Prev',
        $style_links = 'links',
        $style_results = 'results'
    ) {
        $this->total = $total;
        $this->page = $page;
        $this->limit = $limit;
        $this->num_links = $num_links;
        //$this->url = $url;
        //$this->text_first = $text_first;
        //$this->text_last = $text_last;
        //$this->text_next = $text_next;
        //$this->text_prev = $text_prev;
        //$this->style_links = $style_links;
        //$this->style_results = $style_results;


        $this->totalItems = $total;
        $this->perPage = $limit;
        $this->totalPages = ceil($this->totalItems / $this->perPage);
        $this->currentPage = $page;
        // $this->currentPage = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $this->totalPages ? $_GET['page'] : 1;
    }

    /*
    public function render()
    {
        $numPages = ceil($this->total / $this->limit);

        $output = '';

        if ($numPages > 1) {
            $output .= '<div class="' . $this->style_links . '">';

            if ($this->page > 1) {
                $output .= ' <a href="' . str_replace('{page}', '1', $this->url) . '">' . $this->text_first . '</a> ';
                $output .= ' <a href="' . str_replace('{page}', (string)($this->page - 1), $this->url) . '">' . $this->text_prev . '</a> ';
            }

            if ($this->page < $numPages) {
                $output .= ' <a href="' . str_replace('{page}', (string)($this->page + 1), $this->url) . '">' . $this->text_next . '</a> ';
                $output .= ' <a href="' . str_replace('{page}', (string)$numPages, $this->url) . '">' . $this->text_last . '</a> ';
            }

            $output .= '</div>';
        }

        $output .= '<div class="' . $this->style_results . '">' . sprintf('Showing %d to %d of %d (%d Pages)', ($this->page - 1) * $this->limit + 1, min($this->page * $this->limit, $this->total), $this->total, $numPages) . '</div>';

        return $output;
    }
    */

    public function getPaginationHtml() {
        $html = '';

        // $html .= '<div class="dataTables_info" id="sample_1_info" role="status" aria-live="polite">Showing ' . ($this->perPage * ($this->page - 1)) + 1 . ' to ' . min($this->perPage * $this->page, $this->totalItems) . ' of ' . $this->totalItems . ' entries</div>';

        // get current url params
        $urlParams = $_GET;

        if ($this->totalPages > 1) {
            $html .= '<ul class="pagination">';

            if ($this->currentPage > 1) {
                // replace URL page param with 1

                // make parameter string
                $urlParams['page'] = $this->currentPage - 1;

                // $html .= '<li class="page-item"><a class="page-link" href="?page=' . ($this->currentPage - 1) . '">Back</a></li>';
                $html .= '<li class="page-item"><a class="page-link" href="?' . http_build_query($urlParams) . '">Back</a></li>';
            }

            // Generate numeric buttons in the middle
            $numButtons = 5;
            $start = max(1, $this->currentPage - floor($numButtons / 2));
            $end = min($this->totalPages, $start + $numButtons - 1);

            if ($start > 1) {
                $urlParams['page'] = 1;
                $html .= '<li class="page-item"><a class="page-link" href="?' . http_build_query($urlParams) . '">1</a></li>';
                if ($start > 2) {
                    $html .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                $urlParams['page'] = $i;
                $html .= '<li class="page-item ' . ($i == $this->currentPage ? 'active' : '') . '"><a class="page-link" href="?' . http_build_query($urlParams) . '">' . $i . '</a></li>';
            }

            if ($end < $this->totalPages) {
                if ($end < $this->totalPages - 1) {
                    $html .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                }
                $urlParams['page'] = $this->totalPages;
                $html .= '<li class="page-item"><a class="page-link" href="?' . http_build_query($urlParams) . '">' . $this->totalPages . '</a></li>';
            }

            if ($this->currentPage < $this->totalPages) {
                $urlParams['page'] = $this->currentPage + 1;
                $paramString = http_build_query($urlParams);
                $html .= '<li class="page-item"><a class="page-link" href="?' . $paramString . '">Next</a></li>';
                //$html .= '<li class="page-item"><a class="page-link" href="?page=' . ($this->currentPage + 1) . '">Next</a></li>';
            }

            $html .= '</ul>';
        }

        return $html;
    }

}