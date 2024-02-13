<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Netto\Http\Requests\CurrencyRequest as WorkRequest;
use Netto\Models\Currency as WorkModel;
use Netto\Services\CmsService;
use Netto\Services\CurrencyService;
use Netto\Traits\CrudControllerActions;

class CurrencyController extends Abstract\AdminCrudController
{
    use CrudControllerActions;

    protected string $class = WorkModel::class;
    protected string $id = 'currency';

    protected array $list = [
        'columns' => [
            'name' => [
                'title' => 'cms::main.attr_name',
                'width' => 80
            ],
            'slug' => [
                'title' => 'cms::main.attr_slug',
                'width' => 10
            ],
            'is_default' => [
                'title' => 'cms::main.attr_is_default',
                'width' => 10
            ],
        ],
        'params' => [
            'page' => 1,
            'perPage' => 10,
            'sort' => 'name',
            'sortDir' => 'asc',
        ],
        'relations' => [],
        'select' => [
            'id',
            'name',
            'slug',
            'is_default',
        ],
        'title' => 'cms-currency::main.list',
        'url' => [
            'create',
            'delete',
        ],
    ];

    protected array $messages = [
        'create' => 'cms-currency::main.create',
    ];

    protected array $route = [
        'index' => 'admin.currency.index',
        'create' => 'admin.currency.create',
        'delete' => 'admin.currency.delete',
        'destroy' => 'admin.currency.destroy',
        'edit' => 'admin.currency.edit',
        'store' => 'admin.currency.store',
        'update' => 'admin.currency.update',
    ];

    protected string $title = 'cms-currency::main.list';

    protected array $view = [
        'index' => 'cms-currency::currency.index',
        'edit' => 'cms-currency::currency.currency'
    ];

    /**
     * @return View
     */
    public function index(): View
    {
        $this->crumbs[] = [
            'title' => __($this->title)
        ];

        $rates = [];
        foreach (CurrencyService::getRates() as $from => $rate) {
            foreach ($rate as $to => $value) {
                $rates[] = [
                    'from' => format_currency(1, $from),
                    'to' => format_currency($value, $to, 4),
                ];
            }
        }

        return $this->getView($this->view['index'], [
            'rates' => $rates,
        ]);
    }

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function store(WorkRequest $request): RedirectResponse
    {
        return $this->_store($request);
    }

    /**
     * @param WorkRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(WorkRequest $request, string $id): RedirectResponse
    {
        return $this->_update($request, $id);
    }

    /**
     * @param WorkModel $object
     * @return array
     */
    protected function getItem($object): array
    {
        return [
            'name' => $object->name,
            'slug' => $object->slug,
            'is_default' => $object->is_default ? __('cms::main.general_yes') : __('cms::main.general_no'),
        ];
    }

    /**
     * @param $object
     * @return array
     */
    protected function getReference($object): array
    {
        return [
            'boolean' => CmsService::getBooleanLabels(),
        ];
    }
}
