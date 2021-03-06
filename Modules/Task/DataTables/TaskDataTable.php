<?php

namespace Modules\Task\DataTables;

use Modules\Task\Enums\TaskEnum;
use Modules\Task\Models\Task;
use function strip_tags;
use function substr;
use Yajra\DataTables\Services\DataTable;

class TaskDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @param $query
     * @return mixed
     * @throws \Exception
     */
    public function dataTable($query)
    {
        return datatables()
            ->of($query)
            ->editColumn('action', function ($object) {
                $actions = '';

                $actions .= $this->buttonMarkComplete(route('tasks.complete', [$object]), $object->completed);
                $actions .= listingEditButton(route('tasks.edit', [$object]));
                $actions .= listingDeleteButton(route('tasks.destroy', [$object]), 'Task');

                return tdCenter($actions);
            })
            ->editColumn('completed', function ($object) {
                $type = TaskEnum::COMPLETE === $object->completed ? 'success' : 'danger';
                $text = TaskEnum::COMPLETE === $object->completed ? 'Yes' : 'No';

                return tdLabel($type, $text);
            })
            ->editColumn('description', function ($object) {
                return substr(strip_tags($object->description), 0, 80);
            })
            ->setRowClass(function ($object) {
                //return $object->completed === 'Yes' ? 'table-success' : '';
            })
            ->rawColumns(['description', 'completed', 'action'])
            ->blacklist(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // ->get() has impact on search/filters
        $query = Task::where('user_id', user()->id);

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px'])
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'description',
            'completed' => ['width' => '1px'], // auto-width to content
            'created_at' => ['width' => '180px'],
        ];
    }

    /**
     * Get default builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        return [
            'order' => [[2, 'desc']],
            'dom' => 'Bfrtip',
            'ordering' => config('main.datatable.ordering'),
            'pageLength' => config('main.datatable.pageLength'),
            'autoWidth' => config('main.datatable.autoWidth'),
            'responsive' => config('main.datatable.responsive'),
            'bLengthChange' => config('main.datatable.bLengthChange'),
            'processing' => config('main.datatable.processing'),
            'buttons' => config('main.datatable.buttons'),
            ### for filters ###
            //'initComplete' => filterColumns(static::ajax(), static::filterColumns()),
        ];
    }

    /**
     * Columns for which filter dropdown will be displayed.
     *
     * @return array
     */
    public function filterColumns()
    {
        return [
            'completed',
            'created_at',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Task_' . date('YmdHis');
    }

    protected function buttonMarkComplete($link, $status): string
    {
        $title = $status == 0 ? 'Mark as complete' : 'Mark as un-complete';
        $type = $status == 0 ? 'secondary' : 'success';

        $html = <<< HTML
        <a data-placement="top" data-tooltip data-original-title="$title" title="$title" style="text-decoration: none;" href="$link">
            <b class="btn btn-$type fa fa-check-square"></b>
        </a>
HTML;

        return $html;
    }
}
